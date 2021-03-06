<?php
/**
session_start();
include_once 'Login/dbconnect.php';

if(!isset($_SESSION['user']))
{
 header("Location: Login/login.php");
}
$res=mysql_query("SELECT * FROM users WHERE user_id=".$_SESSION['user']);
$userRow=mysql_fetch_array($res);
**/
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
        <title>Memory Path</title>
        <link href="style.css" rel="stylesheet" type="text/css"/>
        <link href="resonsiveslides.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <script src="responsiveslides.js"></script>
        <script src="slideshow_control.js"></script>        
        <script src="sound.js" type="text/javascript"></script>
        <script>
            var triggerLeft = 0;
            var triggerUp = 0;
            var triggerRight = 0;
            var triggerDown = 0;

            $(document).ready(function () {
                animateDog();
            });

            function showDog() {
                if (window.triggerLeft == 1 && window.triggerUp && window.triggerRight == 1 && window.triggerDown == 1) {
                    $(".spaceDog").css("visibility", "visible");
                    $.post('Achievements/easterAchi.php');
                    removeMainSound();
                    easterEggSound();
                }
            }

            function changePosition() {
                var h = $(window).height() - 250;
                var w = $(window).width() - 150;

                var newh = Math.floor(Math.random() * h);
                var neww = Math.floor(Math.random() * w);

                return [newh, neww];
            }

            function animateDog() {
                var newCord = changePosition();
                var oldCord = $('.spaceDog').offset();
                var speed = calcSpeed([oldCord.top, oldCord.left], newCord);

                $(".spaceDog").animate({ top: newCord[0], left: newCord[1] }, speed, function () {
                    animateDog();
                });
            }

            function calcSpeed(prev, next) {
                var x = Math.abs(prev[1] - next[1]);
                var y = Math.abs(prev[0] - next[0]);

                var greatest = x > y ? x : y;

                var speedModifier = 0.1;

                var speed = Math.ceil(greatest / speedModifier);

                return speed;
            }

            $(document).keydown(function (e) {

                switch (e.which) {
                    case 37: // left
                        window.triggerLeft = 1;
                        window.showDog();
                        break;

                    case 38: // up
                        window.triggerUp = 1;
                        window.showDog();
                        break;

                    case 39: // right
                        window.triggerRight = 1;
                        window.showDog();
                        break;

                    case 40: // down
                        window.triggerDown = 1;
                        window.showDog();
                        break;

                    default: return; // exit this handler for other keys
                }
                e.preventDefault(); // prevent the default action (scroll / move caret)
            });

            function openOption() {
                $("#instructions").popup('close');
                window.setTimeout(function () {
                    $("#option").popup('open');
                }, 100);
            }

            function openMenu() {
                $("#option").popup('close');
                window.setTimeout(function () {
                    $("#instructions").popup('open');
                }, 100);
            }

            $(document).on("change", "#globalBGM", function () {
                if ($("#globalBGM option:selected").text() == "Off") {
                    removeMainSound();
                    document.getElementById('gameMusic').value = "Off";
                } else {
                    mainSound();
                    document.getElementById('gameMusic').value = "On";
                }
            });

            $(document).on("change", "#soundEffect", function () {
                if ($("#soundEffect option:selected").text() == "Off") {
                    document.getElementById('gameSoundEffect').value = "Off";
                } else {
                    document.getElementById('gameSoundEffect').value = "On";
                }
            });

        </script>

    </head>
    <body>
        <div data-role="page" id="menupage">
            <div id="menuContainer">
            <div data-role="header" id="header">
                <h1>Memory Path</h1>
                <a href="Login/logout.php?logout" class="ui-btn ui-corner-all ui-btn-inline" data-position-to="window"><?php echo $userRow['username'];?> : Sign Out</a>
            </div>
            <div data-role="content" id="content">
                <div id="logoDiv">
                    <img src="logo2.png" alt="logo" id="logo">
                </div>
                <a href="#gamemode" class="ui-btn ui-corner-all ui-btn-inline" data-rel="popup" data-transition="pop" data-position-to="window" id="menu-button">Play</a>
                    <div id="gamemode" data-role="popup" class="ui-corner-all ui-content" data-theme="b" data-dismissible="false" data-overlay-theme="a">
                        <form method="post" action="game.php" data-ajax="false">
                            <fieldset data-role="controlgroup">
                                <legend>Please choose your game mode</legend>
                                <input type="radio" name="game-mode" id="game-mode-1" value="easy" checked="checked">
                                <label for="game-mode-1">Easy mode</label>
                                <input type="radio" name="game-mode" id="game-mode-2" value="normal">
                                <label for="game-mode-2">Normal mode</label>
                                <input type="radio" name="game-mode" id="game-mode-3" value="hard">
                                <label for="game-mode-3">Hard mode</label>
                                <input type="hidden" name="gameMusic" id="gameMusic" value="On">
                                <input type="hidden" name="soundEffect" id="gameSoundEffect" value="On">
                            </fieldset>
                            <a href="#" data-rel="back" class="ui-btn ui-corner-all" id="menu-button">Return</a>
                            <input type="submit" name="submit" value="Start game">
                        </form>
                    </div>
                <a href="#highscore" data-rel="popup" data-transition="slideup" data-position-to="window" class="ui-btn ui-corner-all ui-btn-inline" id="menu-button">High Scores</a>
                    <div id="highscore" data-role="popup" class="ui-content ui-btn-left" data-theme="b" data-dismissible="false" data-overlay-theme="a">
                        <div id="popup-title">
                            <h1>High Score</h1>
                            <div id="score-rank">
                                <ol data-role="listview">
                                    <?php
                                        $result = mysql_query("SELECT * FROM scores ORDER BY score DESC LIMIT 5");

                                        while($row = mysql_fetch_array($result))
                                        {
                                        echo "<li>";
                                        echo "<h1>" . $row['name'] . "</h1>";
                                        echo "<h1 id='high-score'>" . $row['score'] . "</h1>";
                                        echo "</li>";
                                        }
                                    ?>
                                </ol>
                            </div>
                            <br><br>
                            <a href="#" data-rel="back" data-transition="slidedown" class="ui-btn ui-corner-all" id="menu-button">Return</a>
                        </div>
                    </div>
				<a href="#option" class="ui-btn ui-corner-all ui-btn-inline" data-rel="popup" id="menu-button" data-transition="pop" data-position-to="window">Options</a>
                    <div id="option" data-role="popup" data-dismissible="false" class="ui-content ui-corner-all" data-overlay-theme="a" data-theme="b">
                        <h1>Options</h1>
						<fieldset>
						  <div data-role="fieldcontain">
							<label for="globalBGM">BGM</label>
							<select id="globalBGM" name="globalBGM" data-role="slider">
								<option>Off</option>
								<option selected = "">On</option>
							</select>
						  </div>
                          <div data-role="fieldcontain">
							<label for="soundEffect">Sound Effect</label>
							<select id="soundEffect" name="soundEffect" data-role="slider">
								<option>Off</option>
								<option selected = "">On</option>
							</select>
						  </div>
						</fieldset>
						
                        <a href="#instructions" id="instructionsLink" class="ui-btn ui-corner-all" onclick="openMenu()">Instructions</a>
						<a href="#" data-rel="back" class="ui-btn ui-corner-all" data-transition="fade">Return</a>
						
											
					</div>
                    <div data-role="popup" id="instructions" data-dismissible="false" class="ui-content ui-corner-all" data-overlay-theme="a" data-theme="b">
							<ul class="rslides">
                                <li><img src="instruction1.png" alt="first instruction slide" width="260" height="366"></li>
                                <li><img src="instruction2.png" alt="second instruction slide" width="260" height="366"></li>
                                <li><img src="instruction3.png" alt="second instruction slide" width="260" height="366"></li>
                            </ul>
							<a href="#" onclick="openOption()" class="ui-btn ui-corner-all">Close</a>
						
			        </div>
                    <a href="#achievements" class="ui-btn ui-corner-all ui-btn-inline" data-rel="popup" id="menu-button" data-transition="pop" data-position-to="window">Achievements</a>
                    <div id="achievements" data-role="popup" data-dismissible="false" class="ui-content ui-corner-all" data-overlay-theme="a" data-theme="b">
                        <div class="achievement-gallery">
                            <center>
                            <?php

                            if($userRow['start'] == '0' || $userRow['easter'] == '0' || $userRow['stage10'] == '0' || $userRow['stage25'] == '0'){
                                echo "<h2>Locked Achievements</h2>";
                            }
                                if ($userRow['start'] == '0'){
                                    echo "<img src='Achievement-images/startFade.jpg' class='faded-achievement'><br>";
                                }
                                if ($userRow['easter'] == '0'){
                                    echo "<img src='Achievement-images/easterFade.jpg' class='faded-achievement'><br>";
                                }
                                if ($userRow['stage10'] == '0'){
                                    echo "<img src='Achievement-images/stageFade.jpg' class='faded-achievement'><br>";
                                }
                                if ($userRow['stage25'] == '0'){
                                    echo "<img src='Achievement-images/stage25Fade.jpg' class='faded-achievement'><br>";
                                }

                            if($userRow['start'] == '1' || $userRow['easter'] || '1' && $userRow['stage10'] || '1' || $userRow['stage25'] == '1'){
                                echo "<h2>Unlocked Achievements</h2>";
                            }
                                if ($userRow['start'] == '1'){
                                    echo "<img src='Achievement-images/startClear.jpg' class='clear-achievement'><br>";
                                }
                                if ($userRow['easter'] == '1'){
                                    echo "<img src='Achievement-images/easterClear.jpg' class='clear-achievement'><br>";
                                }
                                if ($userRow['stage10'] == '1'){
                                    echo "<img src='Achievement-images/stageClear.jpg' class='clear-achievement'><br>";
                                }
                                if ($userRow['stage25'] == '1'){
                                    echo "<img src='Achievement-images/stage25Clear.jpg' class='clear-achievement'><br>";
                                }
                            ?>
                        </center>
                        </div>
                    <a href="#" data-rel="back" class="ui-btn ui-corner-all" data-transition="fade">Return</a>
                    </div>
            </div>
            </div>
            <div class="spaceDog">
                </div>
            <div id="twinkling"></div>
            <audio autoplay="autoplay" loop id="mainSound">
                <source src="mainsound.mp3">
            </audio>
        </div>
    </body>
</html>
