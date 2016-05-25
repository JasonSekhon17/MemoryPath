var count=1;
function vortexGenerate(cell){
	var tile  =cell;
    $(tile).append($('<div id="vortex'+count+'"></div>'));
    $(tile).css('overflow','hidden');
    $(tile).addClass('vortex');
    vortex = $('#vortex'+count+'');
    $(vortex).css({
        'background-image': 'url(vortex.jpeg)',
        'background-size': 'cover',
        'background-repeat': 'no-repeat',
        'position': 'relative',
        'left': '50%',
        'margin-left': '-50%',
        'width': '100%',
        'height': '100%',
    });
   	rotateAnimation1("vortex"+count,1);
    count++;
}