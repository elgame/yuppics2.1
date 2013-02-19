$(function(){
	popupFb.init();

	$("#modal_registro").on("show", function(){
		$("#modal_ingreso").modal("hide");

		var target = $("form", this)
		target.clearForm();
		$("#"+target.attr("data-alert")).hide(500);
	});
	$("#modal_ingreso").on("show", function(){
		$("#modal_registro").modal("hide");

		var target = $("form", this)
		target.clearForm();
		$("#"+target.attr("data-alert")).hide(500);
	});

	form_ajax.init();

});


function login_success(){
	window.location = base_url;
}


/**
 * Popup para el login con facebook
 */
var popupFb = (function($){
	var objr = {};
	function initialize(){
    $('body').find('input[type="button"]#loginFb').on('click', function(){
      popup();
    });
		// $('input[type="button"]#loginFb').on('click', function(){
		// 	popup();
		// });
	}
	function popup(){
		var width = 300,
			height = 300,
			xPosition = ($(window).width()-width) / 2,
			yPosition = ($(window).height()-height) / 2,
			url = base_url + 'customer/login_facebook';

		  var win = window.open(url, '', 'width=' + width + ', height=' + height + ', top=' + yPosition + ', left=' + xPosition);
	}
	objr.init = initialize;
	return objr;
})(jQuery);

!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");