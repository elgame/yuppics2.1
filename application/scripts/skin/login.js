$(function(){
	popupFb.init();

	eventsModales();

	form_ajax.init();

	nav_parallax.init();
});


function login_success(){
	window.location = base_url;
}

function eventsModales(){
	$("#modal_registro").on("show", function(){
		$("#modal_ingreso").modal("hide");

		var target = $("form", this)
		target.clearForm();
		$("#"+target.attr("data-alert")).hide(500);
	});
	$("#modal_ingreso").on("show", function(){
		$("#modal_registro").modal("hide");
		$("#modal_politicas_prv").modal("hide");
		$("#modal_terminos_uso").modal("hide");

		var target = $("form", this)
		target.clearForm();
		$("#"+target.attr("data-alert")).hide(500);
	});
	$("#modal_politicas_prv").on("show", function(){
		$("#modal_ingreso").modal("hide");
		$("#modal_registro").modal("hide");
		$("#modal_terminos_uso").modal("hide");
	});
	$("#modal_terminos_uso").on("show", function(){
		$("#modal_ingreso").modal("hide");
		$("#modal_registro").modal("hide");
		$("#modal_politicas_prv").modal("hide");
	});
}

var nav_parallax = (function($){
	var objr = {}, intro;

	function init(){
		//menu de navegacion
		$('#menunavbartop a').on("click", function(){
			var vthis = $(this), targetOffset = $(vthis.attr("href")).offset().top; 
			$('html,body').animate({scrollTop: targetOffset}, 800);
			$("li", vthis.parents('#menunavbartop')).removeClass("active");
			vthis.parent('li').addClass("active");

			if(vthis.attr("data-toggle") == "modal")
				return true;
			return false;
		}); 
		
		intro = $("#intro");
		//.parallax(xPosition, speedFactor, outerHeight) options:
		//xPosition - Horizontal position of the element
		//inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling
		//outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport
		intro.parallax("50%", 0.1);
		$('#second').parallax("50%", 0.1);
		$('.bg').parallax("96%", 0.4);
		$('#four').parallax("50%", 0.3);
		chageBGIntro(true);

		//evento de archivo
		attachFile();

		// attachScrollPane();
	}

	function chageBGIntro(first){
		var clas = intro.attr("class").split(" "), 
		next = parseInt(clas[2].replace("intro", ""));
		next += 1;
		if(next > 4)
			next = 1;

		if(first == true)
			intro.removeClass(clas[2]).addClass("intro"+next);
		else{
			intro.fadeTo('slow', 0.2, function(){
				intro.removeClass(clas[2]).addClass("intro"+next).fadeTo('slow', 1);
			}); 
		}
		setTimeout(chageBGIntro, 7000);
	}

	function attachFile(){
		var avatar = $("#avatar");
		$("#file_avatar").on("click", function(){
			avatar.click();
		});
	}

	function attachScrollPane(){
		$('.scroll-pane').jScrollPane({
			showArrows: false,
			autoReinitialise: true
		});
	}

	objr.init = init;

	return objr;
})(jQuery);



/**
 * Popup para el login con facebook
 */
var popupFb = (function($){
	var objr = {};
	function initialize(){
    $('body').find('a.loginFb').on('click', function(){
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