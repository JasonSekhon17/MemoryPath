	var looper1,looper2,looper3,looper4;
	var degrees1=0,degrees2 = 0,degrees3=0,degrees4=0;
	function rotateAnimation1(el,speed){
		var elem = document.getElementById(el);

		if(navigator.userAgent.match("Chrome")){
			elem.style.WebkitTransform = "rotate("+degrees1+"deg)";
		} else if(navigator.userAgent.match("Firefox")){
			elem.style.MozTransform = "rotate("+degrees1+"deg)";
		} else if(navigator.userAgent.match("MSIE")){
			elem.style.msTransform = "rotate("+degrees1+"deg)";
		} else if(navigator.userAgent.match("Opera")){
			elem.style.OTransform = "rotate("+degrees1+"deg)";
		} else if(navigator.userAgent.match("Safari")) {
			elem.style.WebkitTransform = "rotate("+degrees1+"deg)";
		}else {
			elem.style.transform = "rotate("+degrees1+"deg)";
		}
		looper1 = setTimeout('rotateAnimation1(\''+el+'\','+speed+')',speed);
		degrees1++;
		if(degrees1 > 359){
			degrees1 = 1;
		}
		
	}
	function rotateAnimation2(el,speed){
		var elem = document.getElementById(el);

		if(navigator.userAgent.match("Chrome")){
			elem.style.WebkitTransform = "rotate("+degrees2+"deg)";
		} else if(navigator.userAgent.match("Firefox")){
			elem.style.MozTransform = "rotate("+degrees2+"deg)";
		} else if(navigator.userAgent.match("MSIE")){
			elem.style.msTransform = "rotate("+degrees2+"deg)";
		} else if(navigator.userAgent.match("Opera")){
			elem.style.OTransform = "rotate("+degrees2+"deg)";
		} else if(navigator.userAgent.match("Safari")) {
			elem.style.WebkitTransform = "rotate("+degrees2+"deg)";
		}else {
			elem.style.transform = "rotate("+degrees2+"deg)";
		}
		looper2 = setTimeout('rotateAnimation1(\''+el+'\','+speed+')',speed);
		degrees2++;
		if(degrees2 > 359){
			degrees2 = 1;
		}
		
	}
	function rotateAnimation3(el,speed){
		var elem = document.getElementById(el);

		if(navigator.userAgent.match("Chrome")){
			elem.style.WebkitTransform = "rotate("+degrees3+"deg)";
		} else if(navigator.userAgent.match("Firefox")){
			elem.style.MozTransform = "rotate("+degrees3+"deg)";
		} else if(navigator.userAgent.match("MSIE")){
			elem.style.msTransform = "rotate("+degrees3+"deg)";
		} else if(navigator.userAgent.match("Opera")){
			elem.style.OTransform = "rotate("+degrees3+"deg)";
		} else if(navigator.userAgent.match("Safari")) {
			elem.style.WebkitTransform = "rotate("+degrees3+"deg)";
		}else {
			elem.style.transform = "rotate("+degrees3+"deg)";
		}
		looper3 = setTimeout('rotateAnimation1(\''+el+'\','+speed+')',speed);
		degrees3++;
		if(degrees3 > 359){
			degrees3 = 1;
		}
		
	}
	function rotateAnimation4(el,speed){
		var elem = document.getElementById(el);

		if(navigator.userAgent.match("Chrome")){
			elem.style.WebkitTransform = "rotate("+degrees4+"deg)";
		} else if(navigator.userAgent.match("Firefox")){
			elem.style.MozTransform = "rotate("+degrees4+"deg)";
		} else if(navigator.userAgent.match("MSIE")){
			elem.style.msTransform = "rotate("+degrees4+"deg)";
		} else if(navigator.userAgent.match("Opera")){
			elem.style.OTransform = "rotate("+degrees4+"deg)";
		} else if(navigator.userAgent.match("Safari")) {
			elem.style.WebkitTransform = "rotate("+degrees4+"deg)";
		}else {
			elem.style.transform = "rotate("+degrees4+"deg)";
		}
		looper4 = setTimeout('rotateAnimation1(\''+el+'\','+speed+')',speed);
		degrees4++;
		if(degrees4 > 359){
			degrees4 = 1;
		}
		
	}
	function gameoverBlackhole(){
		$("#grid").empty();
		$("#grid").css('overflow','hidden');
		$("#grid").css('opacity','0.5');
		$("#grid").css('background-color','black');
		$("#grid").append($('<div id="giantHole"></div>'));
		var giantHole = $('#giantHole');
		$(giantHole).css({
			'position': 'relative',
			'width': '100%',
			'height': '100%',
			'background-image': 'url(vortex.jpeg)',
			'background-size': 'cover',
			'background-repeat': 'no-repeat',
			'left': '50%',
			'margin-left': '-50%',
			'z-index': '99'
		});
		rotateAnimation2('giantHole', 5);
	}
	var row, cell;
	function resetTable(){
		$("#grid").empty();
		var table = $("#grid")[0];
        for (i = 0; i < size; i++) {
            row = $('<tr></tr>');
            for (var j = 0; j < size; j++) {
	            cell = $('<td class="panel" onmousedown="event.preventDefault ? event.preventDefault() : event.returnValue = false">' + '</td>');
	            
				$(row).append(cell);
			}
			$(table).append(row);
		}
		$('.panel').css('background-color','#262829');
		$("#grid").css('opacity','1');
		$("#grid").css('background-color','#0070c0');

		
	}


















	