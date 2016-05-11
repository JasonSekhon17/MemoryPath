<?php
    $startingGridNum;
    
    if ($_POST['game-mode'] == 'easy') {
        $startingGridNum = 4;
    } else if ($_POST['game-mode'] == 'normal') {
        $startingGridNum = 5;
    } else if ($_POST['game-mode'] == 'hard') {
        $startingGridNum = 5;
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

        
        <script>
            var gameStatus = false;         // False if game is paused/stopped, true otherwise.
            var gameClear = false;          // Represents the clear status of a stage. Becomes true when player reaches the last column.
            var stageNumber;                // Represents the current stage number.
            var totalScore;                 // Total score of player for this game.
            var stageScore;                 // Score for current stage.
            var life = 3;                   // Player's life for current stage.
            var timer = 60;                 // Placeholder variable to represent timer, acts as an integer
            var pathArray;                  // Records the correct path's coordinate.
            var playerArray;

            // If game has been started, the cells of the table will
            // respond to clicks.
            $(document).on("pagecreate", "#pageone", function () {
                $("td.panel").click(function () {
                    if (gameStatus) {
                        $(this).css("background-color", "green");
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

            // This function checks if the life of player has dropped to zero.
            // True if life is zero, false otherwise.
            function lifeZero() {
                // Implementation needed.
            }

            // This function will take away one life from the player.
            function lifeMinusOne() {
                // Implementation here.
            }

            // This function contains the entire gameover process.
            // Includes showing gameover screen, showing score achieved, entering name
            // for ranking, and anything else that needs to be done.
            function gameOver() {
                // Implementation here.
            }

            // Shows a stage clear screen.
            // May be a popup, or just some texts.
            function stageClearScreen() {
                // Implementation here.
            }

            // Calculate the score achieved by the player for the current stage.
            // Calculation involves the time and life remaining.
            function calculateScore() {
                // Implementation here.
            }

            // Shows the score achieved by the player for the current stage.
            // Can be done in any method desired.
            function showScoreAchieved() {
                // Implementation here.
            }

            // Add the current score to total score and reset the current score.
            function addToTotalScore() {
                // Implementation here.
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
                // hard coded path.
                window.pathArray = ["3 0", "3 1", "2 1", "1 1", "1 2", "1 3", "2 3", "2 4"];
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
                    $(cell).css('background-color', 'white');
                    $(cell).className += ' step' + i;
                    //y.rows[row].cells[col].className += ' step' + i;
                    i++;
                    if (i == window.pathArray.length) {
                        clearInterval(interval);
                        setTimeout(resetGrid, 1000);
                    }
                }, 500);
            }

            // This function will reset the all the tiles inside the grid to original
            // state, which means original color. The recorded randomized path is not 
            // affected.
            function resetGrid() {
                for (i = 0; i < window.pathArray.length; i++) {
                    array = window.pathArray[i];
                    row = parseInt(array.substring(0, 1));
                    col = parseInt(array.substring(2, 3));
                    var table = $("#grid")[0];
                    var cell = table.rows[row].cells[col];
                    $(cell).css('background-color', '#808080');
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


        </script>
    </head>
    <body>
        <div data-role="page" id="pageone">
            <div data-role="header">
                <h1>Time: <script>document.write(timer);</script></h1>
                <a href="#game-menu" data-rel="popup" data-transition="slideup" class="ui-btn ui-corner-all ui-btn-inline" data-position-to="window">Menu</a>
                    <div data-role="popup" data-theme="b" class="ui-content ui-corner-all" data-dismissible="false" id="game-menu">
                        <a href="#in-game-instruction" data-rel="popup" data-transition="popup" class="ui-btn ui-corner-all" data-position-to="window">Instruction</a>
                        <a href="#" data-rel="back" class="ui-btn ui-corner-all" data-transition="slidedown">Return to game</a>
                    </div>
            </div>
            <div data-role="content" style="text-align: center;">
                <div id="game-screen">
                    <?php
                        $row = 1;
                        $col = 1;

                        echo '<table class="game-panel" id="grid">';
                        while ($row <= $startingGridNum) {
                            echo '<tr>';
                            while ($col <= $startingGridNum) {
                                echo '<td class="panel">' . '</td>';
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
            </div>
            <div data-role="footer">
                <h3>Life: <script>document.write(life);</script></h3>
            </div>
        </div>
    </body>
</html>
