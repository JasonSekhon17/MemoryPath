
var pointer;
function correctTileSound(cell){
	pointer = $(cell);
	$(pointer).append($('<audio autoplay="autoplay" id="correctingSound"><source src="correcting.m4a"></audio>'));
}
function incorrectTileSound(cell){
	pointer = $(cell);
	$(pointer).append($('<audio autoplay="autoplay" id="incorrectingSound"><source src="incorrecting.m4a"></audio>'));
}
var page;
function gameSound(){
	page = $("#pageone");
	$(page).append($('<audio autoplay="autoplay" loop id="gameSound"><source src="gameSound.mp3"></audio>'));
}
function removeGameSound(){
	var gamesound = $("#gameSound");
	$(gamesound).remove();
}

function gameoverSound(){
	page = $("#pageone");
	$(page).append($('<audio autoplay="autoplay" id="gameoverSound"><source src="gameover2.mp3"></audio>'));
}
function removeGameoverSound(){
	var gamesound = $("#gameoverSound")
	$(gamesound).remove();
}
var mainP;
function mainSound(){
	mainP = $("#menupage");
	$(mainP).append($('<audio autoplay="autoplay" loop id="mainSound"><source src="mainsound.mp3"></audio>'));
}
function removeMainSound(){
	$("#mainSound").remove();
}

