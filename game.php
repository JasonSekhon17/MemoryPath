<?php
    /** connects to the 000webhost database. Will only work here: http://jasontestsite.net63.net/MemoryPath/
    session_start();
    include_once 'Login/dbconnect.php';
    **/
    $startingGridNum;
    
    if ($_POST['game-mode'] == 'easy') {
        $startingGridNum = 4;
    } else if ($_POST['game-mode'] == 'normal') {
        $startingGridNum = 5;
    } else if ($_POST['game-mode'] == 'hard') {
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


        <script>var size = <?php echo json_encode($startingGridNum) ?>; </script>
        
        <script>
            var gameStatus = false;         // False if game is paused/stopped, true otherwise.
            var gameClear = false;          // Represents the clear status of a stage. Becomes true when player reaches the last column.
            var stageNumber = 1;            // Represents the current stage number.
            var totalScore = 0;                 // Total score of player for this game.
            var stageScore = 0;                 // Score for current stage.
            var life = 3;                   // Player's life for current stage.
            var timer = 60;               // Placeholder variable to represent timer, acts as an integer
            var pathArray = [];                  // Records the correct path's coordinate.
            var stepOrder = 0;
            var isDown = false;
            var path_col;
            var path_row;
            //timer object
            var counter;
            //the place where current time is storaged when you pause
            var currentTime;
            //execute pause function
            var pauseOn = false;
            timer = new Countdown();
            //  Changes grid to a square
            //  If grid width is greater than 800, it is changed to 50%
            $(document).ready(function () {
                var cw = $('.game-panel').width();
                if (cw > 800) {
                    $('.game-panel').css({ 'width': 35 + '%' });
                    cw = $('.game-panel').width();
                }
                $('.game-panel').css({ 'height': cw + 'px' });
            });
            $(document).mousedown(function () {
                isDown = true;      // When mouse goes down, set isDown to true
            })
            $(document).mouseup(function () {
                isDown = false;    // When mouse goes up, set isDown to false
            });
            // If game has been started, the cells of the table will
            // respond to clicks.
            $(document).on("pagecreate", "#pageone", function () {
                $("td.panel").mousedown(function () {
                    if (gameStatus) {
                        if ($(this).hasClass("step" + stepOrder)) {
                            $(this).css("background-color", "green");
                            stepOrder++;
                            if (stepOrder == pathArray.length) {
                                stageClearScreen();
                                gameClear = true;
                            }
                        } else {
                            if (!$(this).hasClass("clicked")) {
                                $(this).css("background-color", "red");
                                life--;
                                updateLifeMessage();
                                if (life == 0) {
                                    gameOver();
                                }
                            }
                        }
                        $(this).addClass("clicked");
                    }
                });
                $("td.panel").mouseover(function () {
                    if (gameStatus) {
                        if (isDown) {
                            if ($(this).hasClass("step" + stepOrder)) {
                                $(this).css("background-color", "green");
                                stepOrder++;
                                if (stepOrder == pathArray.length) {
                                    stageClearScreen();
                                    gameClear = true;
                                }
                            } else {
                                if (!$(this).hasClass("clicked")) {
                                    $(this).css("background-color", "red");
                                    life--;
                                    updateLifeMessage();
                                    if (life == 0) {
                                        gameOver();
                                    }
                                }
                            }
                            $(this).addClass("clicked");
                        }
                    }
                });
            });
            // If timer counts down to zero, check if player's life is zero.
            // If life is zero, continue onto gameover process.
            // If life is not zero, take away one life and restart stage or reset stage.
            if (timer == 0) {
                if (lifeZero()) {
                    gameOver();
                } else {
                    lifeMinusOne();
                    // restart or reset stage.
                }
            }
            // If the stage is cleared, a list of functions will be called.
            // It will end with starting a new stage.
            // **This code snippet needs to be constantly checked, possibly need to do something more.**
            if (gameClear) {
                stageClearScreen();
                calculateScore();
                showScoreAchieved();
                addToTotalScore();
                startNewStage();
            }
            // Function to reset all game status.
            // Used for testing stage
            function resetGame() {
                gameStatus = false;
                life = 3;
                updateLifeMessage();
                resetGrid();
                stepOrder = 0;
                pathArray = [];
            }
            // Function to continue onto next stage
            function nextGame() {
                gameStatus = false;
                life = 3;
                updateLifeMessage();
                resetGrid();
                stepOrder = 0;
                stageNumber++;
                updateStageNumber();
                pathArray = [];
                updateScoreMessage();
                updateGridSize();
            }

            function updateGridSize() {
                $("#game-screen").reload();
            }
            function updateLifeMessage() {
                $("#footer #life").html('Life: ' + life);
            }
            function updateScoreMessage() {
                $("#footer #score").html('Score: ' + totalScore);
            }
            function updateStageNumber() {
                $("#headerForGamePage h2").html('Stage ' + stageNumber);
            }
            // This function contains the entire gameover process.
            // Includes showing gameover screen, showing score achieved, entering name
            // for ranking, and anything else that needs to be done.
            function gameOver() {
                calculateScore();
                addToTotalScore();
                clearInterval(counter);
                $("#timer").html("Time: ");
                $("#gameover").popup("open");
            }
            // Shows a stage clear screen.
            // May be a popup, or just some texts.
            function stageClearScreen() {
                calculateScore();
                addToTotalScore();
                clearInterval(counter);
                $("#timer").html("Time: ");
                $("#clear").popup("open");
                $("#placeForScore").html("<h3>Score: " + totalScore + "</h3>");
                stageScore = 0;
            }
            // Calculate the score achieved by the player for the current stage.
            // Calculation involves the time and life remaining.
            function calculateScore() {
                stageScore = 1000 * (timer.seconds);
            }
            // Shows the score achieved by the player for the current stage.
            // Can be done in any method desired.
            function showScoreAchieved() {
                // Implementation here.
            }
            // Add the current score to total score and reset the current score.
            function addToTotalScore() {
                totalScore += stageScore;
            }
            // Reset timer, grid/table, life remaining, start button, and update total score.
            // Grid/table size may be changed depending on stage number.
            function startNewStage() {
                // Implementation here.
            }
            // This function starts the game.
            function gameAct() {
                if (!gameStatus) {
                    gameStatus = true;
                    startGame();
                    // Try to make the button disappear. (Not done)
                }
            }
            // This function will start the game. Timer will start to count down.
            // Only called by the start button.
            function startGame() {
                generatePath();         // Generate random path. (Not done)
                showPath();             // Show the user the path by making the corresponding grid/cell change color. (Not done)
                resetGrid();            // Make all grid/cell go back to original color. (Not done)
                gameStart();            // Implementation of resuming the timer. (Not done)
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
                var i = 0;
                var interval = setInterval(function () {
                    array = window.pathArray[i];
                    row = parseInt(array.substring(0, 1));
                    col = parseInt(array.substring(2, 3));
                    var table = $("#grid")[0];
                    var cell = table.rows[row].cells[col];
                    $(cell).css('background', 'white');
                    $(cell).addClass('step' + i);
                    $(cell).css("animation", "walk-east 0.2s steps(4) infinite");
                    //y.rows[row].cells[col].className += ' step' + i;
                    i++;
                    if (i == window.pathArray.length) {
                        clearInterval(interval);
                        setTimeout(resetGrid, 800);
                        timer.init();
                    }
                }, 200);
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
                        $(cell).css('background-color', '#808080');
                    }
                    col = 0;
                }
            }
            function resetGridClass() {
                var table = $("#grid")[0];
                for (row = 0; row < window.size; row++) {
                    for (col = 0; col < window.size; col++) {
                        var cell = table.rows[row].cells[col];
                        $(cell).removeClass("clicked stepOrder");
                    }
                    col = 0;
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
            function Countdown() {
                //time declared
                if (pauseOn == false) {
                    this.start_time = 20 + (5 * stageNumber);
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
                this.seconds = this.start_time;
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
                if (seconds < 10) this.seconds = "0" + this.seconds;
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
                    gameOver();
                } else {
                    $(this.target_id).html("Time: " + seconds);
                }
            }
        </script>
    </head>
    <body>
      <div data-role="page" id="pageone">
                <video autoplay loop id="space">
                    <source src="space1.mp4">
                </video>
            <div data-role="header" id="headerForGamePage">               
                <h1 id="timer">Time: </h1>
                <h2>Stage 1</h2>
                <a href="#game-menu" data-rel="popup" data-transition="slideup" class="ui-btn ui-corner-all ui-btn-inline" data-position-to="window" onclick="timer.pauseTimer()" >Menu</a>
                    <div data-role="popup" data-theme="b" class="ui-content ui-corner-all" data-dismissible="false" id="game-menu">
                        <a href="#in-game-instruction" data-rel="popup" data-transition="popup" class="ui-btn ui-corner-all" data-position-to="window">Instruction</a>
                        <a href="index.php" class="ui-btn ui-corner-all">Back to main menu</a>
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
                    <a href="#" id="actButton" class="ui-btn ui-corner-all ui-btn-inline" onclick="gameAct()">Start</a>
                </div>
                <div id="gameover" data-role="popup" data-transition="pop" data-theme="b" data-overlay-theme="a" class="ui-content ui-corner-all" data-dismissible="false">
                    <h1>Game Over!</h1>     
                    <a href="index.php" class="ui-btn ui-corner-all">Return to main menu</a>
                    <a data-rel="back" class="ui-btn ui-corner-all" onclick="resetGame()">Restart stage</a>
                </div>

                <div id="clear" data-role="popup" data-transition="pop" data-theme="b" data-overlay-theme="a" class="ui-content ui-corner-all" data-dismissible="false">
                    <h1>Stage Cleared!</h1> 
                    <div id="placeForScore"></div>    
                    <a href="index.php" class="ui-btn ui-corner-all">Return to main menu</a>
                    <a data-rel="back" class="ui-btn ui-corner-all" onclick="nextGame()">Next stage</a>
                </div>
            </div>

            <div data-role="footer" id="footer">
                <img class="footer-image" src="life.jpg" alt="life points">
                    <h3 id="life">Life: <script>document.write(life);</script></h3>
                
                <img class="footer-image" src="star.jpeg" alt="score">
                <h3 id="score">Score: <script>document.write(totalScore);</script></h3>
            </div>
        </div>
    </body>
</html>