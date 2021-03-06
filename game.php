<?php
	
	session_start();
    include_once 'Login/dbconnect.php';
	
    $startingGridNum;
    $gameMode;
    $music = $_POST['gameMusic'];
    $sound = $_POST['soundEffect'];
    
    $gameMode = $_POST['game-mode'];
    if ($gameMode == 'easy') {
        $startingGridNum = 4;
    } else if ($gameMode == 'normal') {
        $startingGridNum = 5;
    } else if ($gameMode == 'hard') {
        $startingGridNum = 6;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Memory Path</title>
		<link href="style.css" rel="stylesheet" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
		<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <script src="sound.js" type="text/javascript"></script>
        <script src="blackhole.js" type="text/javascript"></script>
        <script src="removeblackhole.js" type="text/javascript"></script>
        <script src="vortex.js" type="text/javascript"></script>

        <script>var size = <?php echo json_encode($startingGridNum) ?>; </script>
        <script>var mode = <?php echo json_encode($gameMode) ?>; </script>
        <script>var music = <?php echo json_encode($music) ?>; </script>
        <script>var soundEffect = <?php echo json_encode($sound) ?>; </script>
        
        <script>
            var gameStatus = false;         // False if game is paused/stopped, true otherwise.
            var gameClear = false;          // Represents the clear status of a stage. Becomes true when player reaches the last column.
            var stageNumber = 1;            // Represents the current stage number.
            var totalScore = 0;                 // Total score of player for this game.
            var stageScore = 0;                 // Score for current stage.
            var life = 3;                   // Player's life for current stage.
            var tries = 3;
			var timer = 60;               // Placeholder variable to represent timer, acts as an integer
            var pathArray = [];                  // Records the correct path's coordinate.
            var stepOrder = 0;
            var isDown = false;
            var path_col;
            var path_row;
            var length;  // Path length desired
            var gridChangeInterval = 5;
            //timer object
            var counter;
            //the place where current time is storaged when you pause
            var currentTime;
            //execute pause function
            var pauseOn = false;
            timer = new Countdown();

            $(document).ready(
                resize
            );

            $(document).ready(

                function () {
                    if (mode == "easy") {
                        length = size;
                    } else if (mode == "normal") {
                        length = size + 2;
                    } else if (mode == "hard") {
                        length = size + 2;
                    }

                    if (music == "Off") {
                        removeGameSound();
                    } else {
                        gameSound();
                    }
                }
            );
            //  Changes grid to a square based on viewport size ratio
            function resize() {
                var cw = $('.game-panel').width();
                var screenRatio = $(window).width() / $(window).height();
                if (2.0 < screenRatio) {
                    $('.game-panel').css({ 'width': 30 + '%' });
                    cw = $('.game-panel').width();
                } else if (1.7 < screenRatio) {
                    $('.game-panel').css({ 'width': 35 + '%' });
                    cw = $('.game-panel').width();
                } else if (1.3 < screenRatio) {
                    $('.game-panel').css({ 'width': 40 + '%' });
                    cw = $('.game-panel').width();
                } else if (1.0 < screenRatio) {
                    $('.game-panel').css({ 'width': 50 + '%' });
                    cw = $('.game-panel').width();
                } else if (.80 < screenRatio) {
                    $('.game-panel').css({ 'width': 85 + '%' });
                    cw = $('.game-panel').width();
                }
                $('.game-panel').css({ 'height': cw + 'px' });
            }

            //  Dynamically changes grid size when resizing
            $(document).mouseleave(function () {
                var number = 1;
                var resizing = setInterval(resize, (1000 / 6));

                $(document).mouseenter(function () {
                    clearInterval(resizing);
                });
            });

            $(document).mousedown(function () {
                isDown = true;      // When mouse goes down, set isDown to true
            })
            $(document).mouseup(function () {
                isDown = false;    // When mouse goes up, set isDown to false
            });
            // If game has been started, the cells of the table will
            // respond to clicks.
            $(document).on("finishShowPath", function () {
                $("td.panel").on('mousedown', function () {
                    checkCell(this);
                });

                $("td.panel").on('mouseover', function () {
                    if (isDown) {
                        checkCell(this);
                    }
                });

                $("td.panel").on('touchstart', function (e) {
                    checkCell(this);
                });

                $("td.panel").on('touchmove', function (event) {
                    var lastLocation;
                    var myLocation = event.originalEvent.changedTouches[0];
                    var tile = document.elementFromPoint(myLocation.clientX, myLocation.clientY);

                    if (lastLocation == myLocation) {
                        return true;
                    } else {
                        lastLocation = myLocation;
                        checkCell(tile);
                        return false;
                    }
                });
                var vortex; // object for blackhole animation
                function checkCell(cell) {
                    if (gameStatus) {
                        if ((!$(cell).hasClass("clicked") || $(cell).hasClass("wrong")) && $(cell).hasClass("step" + stepOrder)) {

                            if ($(cell).hasClass("clicked")) {
                                $(cell).empty();
                            }
                            $(cell).css("background-color", "green");
                            stageScore += 10;
                            updateScoreMessage();
                            stepOrder++;
                            if (stepOrder == pathArray.length) {
                                stageClearScreen();
                                gameClear = true;
                            }
                            correctTileSound(cell);
                        } else if (!$(cell).hasClass("clicked") && $(cell).hasClass('panel')) {
                            var pointer = $(cell);
                            vortexGenerate(pointer);
                            $(cell).addClass("wrong");
                            life--;
							updateLifeMessage();
							if (life > 0) {
								incorrectTileSound(cell);
							} else {
								failPuzzle();
							}
                        }
                        $(cell).addClass("clicked");
                    }
                }
            });
            // If timer counts down to zero, check if player's life is zero.
            // If life is zero, continue onto gameover process.
            // If life is not zero, take away one life and restart stage or reset stage.
            if (timer == 0) {
				life--;
            }
            // If the stage is cleared, a list of functions will be called.
            // It will end with starting a new stage.
            // **This code snippet needs to be constantly checked, possibly need to do something more.**
            if (gameClear) {
                stageClearScreen();
            }

            // Shows a stage clear screen.
            // May be a popup, or just some texts.
            function stageClearScreen() {
                //removeBlackhole();
                updateGameClearPopup();
                clearInterval(counter);
                $("#hiddenScore").val(totalScore);
                $("#timer").html("Time: ");
                $("#clear").popup("open");
                stageScore = 0;
            }

            // This function starts the game.
            function gameAct() {
                if (!gameStatus) {
                    startGame();
                    gameStatus = true;
                    // Try to make the button disappear. (Not done)
                }
            }

            // This function basically makes the timer start counting down.
            // It will be called when the start button is clicked on, or when 
            // the menu popup is cleared.
            function gameStart() {
                // Implementation needed.
            }
            // This function will pause the game, which means making the timer stop.
            // It will only be called when the menu button is clicked on and the menu 
            // popup appears.
            function pauseGame() {
                // Implementation needed.
            }

            // This function will start the game. Timer will start to count down.
            // Only called by the start button.
            function startGame() {
                updateOldTotalScore();
                chosenPath();           // Generate random path. 
                showPath();             // Show the user the path by making the corresponding grid/cell change color. (Not done)
                gameStart();            // Implementation of resuming the timer. (Not done)
            }

            // Function to reset all game status.
            // Used for testing stage
            var gamesound = false;
            function resetGame() {
                life = 3;
				resetTable();
                gameStatus = false;
                stageScore = 0;
                stepOrder = 0;
                if (gamesound) {
                    removeGameSound();
                    gamesound = false;
                } else {
                    gameSound();
                    gamesound = true;
                }
                updateLifeMessage();
                updateStageNumber();
                pathArray = [];
                updateScoreMessage();
                resetGrid();
                resetGridClass();

            }
            // Function to continue onto next stage
            function nextGame() {
				resetTable();
				life = 3;
                gameStatus = false;
                gameClear = false;
                updateLifeMessage();
                stepOrder = 0;
                stageNumber++;
                updateStageNumber();
                pathArray = [];
                updateScoreMessage();
                increaseDifficulty();
                resetGrid();
                resetGridClass();
            }

            // This function contains the entire gameover process.
            // Includes showing gameover screen, showing score achieved, entering name
            // for ranking, and anything else that needs to be done.
            function failPuzzle() {
                tries--;
                updateTriesMessage();
                clearInterval(counter);
                $("#hiddenScore").val(totalScore);
				$(".gameOldTotalScore").text('Score: ' + totalScore);
                currentTime = 0;
                $("#timer").html("Time: ");
                removeGameSound();
                if(tries > 0){
					$("#puzzleover").popup("open");
				} else {
					$("#gameover").popup("open");
				}
				if (soundEffect == "On") {
                    gameoverSound();
                }
                gameoverBlackhole();
            }
			
            function updateGridSize() {
                $("#game-screen").reload();
            }
			function updateTriesMessage() {
				$(".gameTries").text('Attempts Remaining: ' + tries);
			}
            function updateLifeMessage() {
                $("#footer #life").html('Life: ' + life);
            }
            function updateScoreMessage() {
                $("#footer #score").html('Stage Score: ' + stageScore);
            }
            function updateStageNumber() {
                $("#headerForGamePage h2").html('Stage ' + stageNumber);
            }
            // Shows the score achieved by the player for the current stage.
			function updateOldTotalScore() {
				$(".gameOldTotalScore").html('Old Total Score: ' + totalScore);
			}
			
			// Shows the score achieved by the player for the current stage.
            function updateGameClearPopup() {
                // Implementation here.
                gridSizeMultiplier = size - 3;
                timeMultiplier = 1 + (timer.seconds / timer.baseTime);
                totalStageScore = (stageScore * gridSizeMultiplier * timeMultiplier) | 0;
                totalScore += totalStageScore;
                $(".gameStageNumber").html('Stage ' + stageNumber);
                $(".gameStageScore").html('Stage Score: ' + stageScore);
                $(".gameGridMultiplier").html('Grid Size Multiplier: x' + gridSizeMultiplier);
                $(".gameTimeMultiplier").html('Time Multiplier: x' + timeMultiplier);
                $(".gameTotalStageScore").html('Total Stage Score: ' + totalStageScore);
                $(".gameNewTotalScore").html('New Total Score: ' + totalScore);

            }

            // Reset timer, grid/table, life remaining, start button, and update total score.
            // Grid/table size may be changed depending on stage number.
            function startNewStage() {
                // Implementation here.
            }

            // This function will make the game more difficult
            function increaseDifficulty() {
                if (mode == "easy") {
                    if (size == 4) {
                        length++;
                    } else if (size < 7) {
                        length += 2;
                    } else {
                        length += 3;
                    }

                    if (stageNumber % gridChangeInterval == 0 && size < 10) {
                        increaseGridSize();
                        length = size + ((stageNumber / 3) | 0);
                    }
                } else if (mode == "normal") {
                    if (size < 7) {
                        length += 2;
                    } else {
                        length += 3;
                    }

                    if (stageNumber % gridChangeInterval == 0 && size < 10) {
                        increaseGridSize();
                        length = size + ((stageNumber / 3) | 0);
                    }
                } else if (mode == "hard") {
                    length += 3;

                    if (stageNumber % gridChangeInterval == 0 && size < 10) {
                        increaseGridSize();
                        length = size + ((stageNumber / 3) | 0);
                    }
                }

            }

            // This function randomizes paths until one matched length desired
            function chosenPath() {
                while (pathArray.length != length) {
                    pathArray = [];
                    generatePath();
                }
            }

            // This function will generate the random path for each stage.
            // No validation of game status is needed because this function will
            // only be called when validation is done.
            function generatePath() {
                path_row = 0;
                path_col = 0;
                path_row = randomIntFromInterval(0, size - 1);
                pathArray.push(path_row + " " + path_col);
                while (path_col < size - 1) {
                    rowMovement = checkTwoSides();
                    if (rowMovement == 3) {
                        path_col++;
                    } else {
                        direction = randomIntFromInterval(0, 1);
                        if (direction == 0) {
                            path_col++;
                        } else {
                            changeRow();
                        }
                    }
                    pathArray.push(path_row + " " + path_col);
                }
                /* hard coded path.
                window.pathArray = ["3 0", "3 1", "2 1", "1 1", "1 2", "1 3", "2 3", "2 4"];
                x_coord = 2;
                y_coord = 5;
                pathArray.push(x_coord + " " + y_coord);
                while (pathArray.length <= size) {
                pathArray.push(path_row + " " + path_col);
                path_col++; 
                }*/
            }
            function changeRow() {
                upRow = path_row + 1;
                downRow = path_row - 1;
                if (path_row == 0) {
                    path_row++;
                } else if (path_row == size - 1) {
                    path_row--;
                } else if (checkTwoSides() == 0) {
                    path_row++;
                } else if (checkTwoSides() == 1) {
                    path_row--;
                } else {
                    dir = randomIntFromInterval(0, 1);
                    if (dir == 0) {
                        path_row++;
                    } else {
                        path_row--;
                    }
                }
            }
            function randomIntFromInterval(min, max) {
                return Math.floor(Math.random() * (max - min + 1) + min);
            }
            function checkTwoSides() {
                upRow = path_row + 1;
                downRow = path_row - 1;
                array = window.pathArray[window.pathArray.length - 1];
                if (window.pathArray.length > 1) {
                    array = window.pathArray[window.pathArray.length - 2];
                }
                row = parseInt(array.substring(0, 1));
                col = parseInt(array.substring(2, 3));
                if (col == path_col) {
                    if (((path_row == 0) && (row == path_row + 1)
                            || (path_row == size - 1) && (row == path_row - 1))
                            && (col == path_col)) {
                        return 3; // Represent no movement of row possible
                    } else
                        if (
                            path_row == row + 1
                        //$.inArray((upRow + ' ' + path_column), array )
                            ) {
                            return 0; // Represent upward direction
                        } else if (
                            path_row == row - 1
                        //$.inArray((downRow + ' ' + path_column), array )
                            ) {
                            return 1; // Represent downward direction
                        } else {
                            return 2; // Represent no used tile up or down
                        }
                }
            }
            // This function will use the random path generated in another function 
            // and show the path to the player by making the corresponding tiles
            // change color in order.
            // Time interval between the change of color of each tile depends on
            // the game mode and current stage number.
            function showPath() {
                var showPathSpeed;
                var timeToRememberGrid;
                if (mode == "easy") {
                    showPathSpeed = 200;
                    timeToRememberGrid = 800;
                } else if (mode == "normal") {
                    showPathSpeed = 160;
                    timeToRememberGrid = 700;
                } else if (mode == "hard") {
                    showPathSpeed = 120;
                    timeToRememberGrid = 600;
                }
                var i = 0;
                var interval = setInterval(function () {
                    array = window.pathArray[i];
                    row = parseInt(array.substring(0, 1));
                    col = parseInt(array.substring(2, 3));
                    var table = $("#grid")[0];
                    var cell = table.rows[row].cells[col];
                    $(cell).css('background', 'white');
                    $(cell).addClass('step' + i);
                    i++;
                    if (i == window.pathArray.length) {
                        clearInterval(interval);
                        setTimeout(resetGrid, timeToRememberGrid);
                        setTimeout(function () {
                            $(document).trigger("finishShowPath");
                        }, timeToRememberGrid);
                        timer.init();
                    }
                }, showPathSpeed);
                //alert (gameStatus + ' ' + gameClear);
            }
            //will reset the all the tiles inside the grid to original
            // state, which means original color. The recorded randomized path is not 
            // affected.
            function resetGrid() {
                var table = $("#grid")[0];
                for (row = 0; row < window.size; row++) {
                    for (col = 0; col < window.size; col++) {
                        var cell = table.rows[row].cells[col];
                        $(cell).css('background-image', 'none');
                        //$(cell).html($(cell).attr('class'));
                        $(cell).css('background-color', 'black');
                        $(cell).removeClass('clicked');
                    }
                    col = 0;
                }
            }
            function resetGridClass() {
                var table = $("#grid")[0];
                for (row = 0; row < window.size; row++) {
                    for (col = 0; col < window.size; col++) {
                        var cell = table.rows[row].cells[col];
                        $(cell).removeClass();
                        $(cell).addClass('panel clicked');
                    }
                    col = 0;
                }
            }

            function increaseGridSize() {
                var table = $("#grid")[0];
                for (row = 0; row < size; row++) {
                    var cell = table.rows[row];
                    $(cell).append('<td class="panel" onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false">' + '</td>');
                }

                $(table).append('<tr></tr>');
                for (i = 0; i <= size; i++) {
                    var row = table.rows[window.size];
                    $(row).append('<td class="panel" onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false">' + '</td>');
                }
                window.size++;
            }

            function Countdown() {
                //time declared
                this.baseTime = 10; // Base length of timer

                if (pauseOn == false) {
                    this.start_time = this.baseTime;
                } else {
                    this.start_time = currentTime;
                }
                this.target_id = "#timer";
                this.name = "timer";
            }
            // execute other functions
            Countdown.prototype.init = function () {
                this.reset();
                counter = setInterval(this.name + '.tick()', 1000);
            }
            // divide time declared into min, second and millisecond
            // storaged in an array whose values are able to be used 
            //individually
            Countdown.prototype.reset = function () {
                this.seconds = this.baseTime + (2 * ((stageNumber / gridChangeInterval) | 0));
                this.update_target();
            }
            // basic calculation decrementing time as countdown.
            Countdown.prototype.tick = function () {
                if (gameClear == true) {
                    clearInterval(counter);
                    gameClear = false;
                }
                if (this.seconds > 0) {
                    this.seconds = this.seconds - 1;
                }
                this.update_target();
            }
            //pause timer when user click the menu button
            Countdown.prototype.pauseTimer = function () {
                pauseOn = true;
                clearInterval(counter);
                if (this.seconds < 10) this.seconds = "0" + this.seconds;
                currentTime = this.seconds;
                $(game - menu).append("<div>Pause</div><br><div>currentTime</div>");
            }
            Countdown.prototype.restart = function () {
                counter = setInterval(this.name + ".tick()", 1000);
            }
            // update a new time from tick function every 10 millisecond
            Countdown.prototype.update_target = function () {
                seconds = this.seconds;
                if (seconds < 10) seconds = "0" + seconds;
                if (seconds == 0) {
                    $(this.target_id).html("Time Out!!");
                    gameClear = true;
                    if (gameClear == true) {
                        clearInterval(counter);
                        gameClear = false;
                    }
                    failPuzzle();
                } else {
                    $(this.target_id).html("Time: " + seconds);
                }
            }


            /** Posts data to achis.php when start button is clicked. For the achievements challange **/
            function startAchi(){
                $.post('Achievements/startAchi.php');
            }

            /** Posts data to achis.php when start button is clicked. For the achievements challange **/
            function stageAchi(){
                if(stageNumber == 10){
                    $.post('Achievements/stageAchi.php');
                }
                if(stageNumber == 25){
                    $.post('Achievements/stage25Achi.php');
                }
            }
        </script>
    </head>
    <body>
      <div data-role="page" id="pageone">
              
            <div data-role="header" id="headerForGamePage">               
                <h2 id="stageSign">Stage 1</h2>
                <h1 id="timer">Time: </h1>
                
                <a href="#game-menu" data-rel="popup" data-transition="slideup" class="ui-btn ui-corner-all ui-btn-inline" data-position-to="window" onclick="timer.pauseTimer()" >Menu</a>
                    <div data-role="popup" data-theme="b" class="ui-content ui-corner-all" data-dismissible="false" id="game-menu">
                        <a href="#in-game-instruction" data-rel="popup" data-transition="popup" class="ui-btn ui-corner-all" data-position-to="window">Instruction</a>
                        <a href="index.php" class="ui-btn ui-corner-all" onclick="removeGameSound()" data-ajax="false">Back to main menu</a>
                        <a href="#" data-rel="back" class="ui-btn ui-corner-all" data-transition="slidedown" onclick="timer.restart()">Return to game</a>
                    </div>
            </div>
            <div data-role="content" style="text-align: center;" id="game-container">
                <div id="game-screen">
                    <?php
                        $row = 1;
                        $col = 1;
                        echo '<table class="game-panel" id="grid">';
                        while ($row <= $startingGridNum) {
                            echo '<tr>';
                            while ($col <= $startingGridNum) {
                                echo '<td class="panel" onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false">' . '</td>';
                                $col++;
                            }
                            echo '</tr>';
                            $row++;
                            $col = 1;
                        }
                        echo '</table>';
                    ?>
                    <a href="#" id="actButton" class="ui-btn ui-corner-all ui-btn-inline" onclick="gameAct();startAchi();">Start</a>
                </div>
				<div id="puzzleover" data-role="popup" data-transition="pop" data-theme="b" data-overlay-theme="a" class="ui-content ui-corner-all" data-dismissible="false">
                    <h1>Puzzle Over!</h1>
                    <div class="placeForScore">
						<h3 class="gameOldTotalScore"></h3>
						<h3 class="gameTries"></h3>
					</div>
                    <a href="index.php" class="ui-btn ui-corner-all" data-ajax="false">Return to main menu</a>
                    <a data-rel="back" class="ui-btn ui-corner-all" onclick="resetGame()">Restart stage</a>
                </div>
				
                <div id="gameover" data-role="popup" data-transition="pop" data-theme="b" data-overlay-theme="a" class="ui-content ui-corner-all" data-dismissible="false">
                    <h1>Game Over!</h1>
                    <div class="placeForScore">
						<p class="gameOldTotalScore"></p>
					</div>
                     <form method="post" action="score.php">
                    <input type="hidden" id="hiddenScore" name="score" value="0" />
                    <button type="submit" class="ui-btn ui-corner-all ui-btn-inline" name="btn-score">Submit Score</button>
                    </form>
                    <a href="index.php" class="ui-btn ui-corner-all" data-ajax="false">Return to main menu</a>
                </div>

                <div id="clear" data-role="popup" data-transition="pop" data-theme="b" data-overlay-theme="a" class="ui-content ui-corner-all" data-dismissible="false">
                    <h1>Stage Cleared!</h1>
                    <div class="placeForScore">
						
							<h3 class="gameStageNumber">Stage 1</h3>
							<h3 class="gameNewTotalScore"><script>document.write(totalScore);</script></h3>
						
					</div>    
                    <a href="index.php" class="ui-btn ui-corner-all" data-ajax="false">Return to main menu</a>
                    <a data-rel="back" class="ui-btn ui-corner-all" onclick="nextGame();stageAchi();">Next stage</a>
                </div>
            </div>

            <div data-role="footer" id="footer">
                    <div id="life">Life: <script>document.write(life);</script></div>
                <div id="score">Stage Score: <script>document.write(stageScore);</script></div>
            </div>
            <div id="twinkling2"></div>
            
        </div>
    </body>
</html>