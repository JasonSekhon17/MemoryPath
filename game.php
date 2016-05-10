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
            
            if (timeOver()) {               // If timer goes down to zero, check if life is zero.
                if (lifeZero()) {           // If life is zero, continue onto game over screen.
                    gameOver();             // If life isn't zero, call function to take away one life.
                } else {
                    lifeMinusOne();
                }
            }

            if (stageClear()) {
                stageClearScreen();
                calculateScore();
                showScoreAchieved();

            }

            // This function starts the game.
            function gameAct() {
                if (!gameStatus) {
                    startGame();
                    gameStatus = true;
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
                // Implementation needed.
            }

            // This function will use the random path generated in another function 
            // and show the path to the player by making the corresponding tiles
            // change color in order.
            // Time interval between the change of color of each tile depends on
            // the game mode and current stage number.
            function showPath() {
                // Implementation needed.
                // Use php inside javascript to find out what the game mode chosen is.
            }

            // This function will reset the all the tiles inside the grid to original
            // state, which means original color. The recorded randomized path is not 
            // affected.
            function resetGrid() {
                // Implementation needed.
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
        <div data-role="page">
            <div data-role="header">
                <h1>Time</h1>
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

                        echo '<table class="game-panel">';
                        while ($row <= $startingGridNum) {
                            echo '<tr>';
                            while ($col <= $startingGridNum) {
                                echo '<td class="panel">' . $row . ',' . $col . '</td>';
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
        </div>
    </body>
</html>
