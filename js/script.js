/**
 *
 * Crop Image While Uploading With jQuery
 * 
 * Copyright 2013, Resalat Haque
 * http://www.w3bees.com/
 *
 */

// set info for cropping image using hidden fields
function setInfo(i, e) {
	$('#x').val(e.x1);
	$('#y').val(e.y1);
	$('#w').val(e.width);
	$('#h').val(e.height);
}

$(document).ready(function() {
	var p = $("#uploadPreview");

	// prepare instant preview
	$("#avata").change(function(){
		// fadeOut or hide preview
		p.closest("div").fadeOut();

		// prepare HTML5 FileReader
		var oFReader = new FileReader();
		oFReader.readAsDataURL(document.getElementById("avata").files[0]);

		oFReader.onload = function (oFREvent) {
	   		p.attr('src', oFREvent.target.result);
			p.closest("div").fadeIn();
			if(p.width()<=1000 && p.height()<=1000) {
				p.closest("div").css({"width":p.width()+"px","height":p.height()+"px","left":($("body").width()/2 - p.width()/2 - 25),"top":"15%"});
				$("#submit-up").fadeIn();
			} else {
				$("#submit-up").fadeOut();
				p.closest("div").hide();
				alert("Kích cỡ ảnh phải bé hơn 1000px");
				location.reload();
			}
		};
		
		//center2(p.closest("div"),"body");
		
	});

	// implement imgAreaSelect plug in (http://odyniec.net/projects/imgareaselect/)
	$('img#uploadPreview').imgAreaSelect({
		// set crop ratio (optional)
		aspectRatio: '1:1',
		onSelectEnd: setInfo
	});
});