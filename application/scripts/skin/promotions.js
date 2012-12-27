$(function(){
	FB.init({
  	appId  : fb_app_id,
	  frictionlessRequests: true
	});

	get_yuppic.init();
});


var get_yuppic = (function($){
	var objr = {};

	function init(){
		var fb_comparte_link = $("#fb_comparte_link"),
		fb_invita_link = $("#fb_invita_link"),
		prom_feedback = $("#prom_feedback"),
		prom_tweetea = $("#prom_tweetea");

		$('#promo_alert .close').on('click', function(){
			$(this).parent("#promo_alert").hide(500);
		});

		if (!fb_comparte_link.is(".disable")){
			// Paso 1, asigna eventos para compartir en facebook
			fb_comparte_link.on("click", function(){
				FB.ui({
				   method: 'feed',
				   name: 'Crea tus photobooks en linea nunca había sido tan divertido!',
				   caption: 'Yuppics es tu opción para crear tus photobooks de una manera sencilla y divertida...',
				   description: (
				      'http://www.yuppics.com/'
				   ),
				   link: 'http://www.yuppics.com/',
				   picture: 'http://a0.twimg.com/profile_images/1245623307/Logo_oficial_Yuppics.gif'
				  }, 
				  requestShareLink
				);
			});
		}

		// Paso 2, asigna eventos para invitar amigos
		if (!fb_invita_link.is(".disable")){
			fb_invita_link.on("click", function(){
				FB.ui({method: 'apprequests',
				  message: 'Crea photobooks facil y rapido con yuppics'
				}, requestInviteLink);
			});
		}

		// Paso 3, redirigue al apartado para publicar en twitter
		if (!prom_tweetea.is(".disable")){
			prom_tweetea.on("click", function(){
				window.location = base_url+"promotions/twitter";
			});
		}

		// Paso 4, asigna eventos para el feedback
		if (!prom_feedback.is(".disable")){
			prom_feedback.on("click", function(){
				$("#modal_feedback").modal("show");
				$("#btn-feedback").on("click", function(){
					var txt = $("#feedback_text"), data = "field=feedback&value=1&text="+txt.val();
					if ($.trim(txt.val()) != ""){
						updateStatusPromo(data);
					}else{
						myalert("Escribe la informacion en el campo.", "error", "send_feedback_alert");
					}
				});
			});
		}

	}

	function requestInviteLink(response) {
		if (response){
			if(response.to.length >= 2){
				var data = "field=invit_facebook&value=1";
				updateStatusPromo(data);
			}else{
				myalert("Tienes que invitar a mas de 20 amigos, intentalo de nuevo.", "error");
			}
		}else{
			myalert("No se pudo enviar la invitacion en facebook, intenta de nuevo porfavor.", "error");
		}
	}

	function requestShareLink(response) {
		if (response && response.post_id) {

			var data = "field=link_facebook&value=1";
			updateStatusPromo(data);

		} else {
			myalert("No se pudo hacer la publicacion en facebook, intenta de nuevo porfavor.", "error");
		}
	}


	function updateStatusPromo(data){
		console.log(data);
		$.post(base_url+"promotions/update_state", data, requestUpdateStatus, "json");
	}

	function requestUpdateStatus(data){
		myalert(data.frm_errors.msg, data.frm_errors.ico);

		if (data.frm_errors.ico == "success"){
			// Paso 1, publicacion en facebook
			if(data.status1.link_facebook == "1"){
				var obj = $("#fb_comparte_link");
				obj.addClass("disable").off('click');
				$("input[type=checkbox]", obj).attr("checked", true);
			}else
				$("#fb_comparte_link").removeClass("disable");

			// Paso 2, invitar amigos en facebook
			if(data.status1.invit_facebook == "1"){
				var obj = $("#fb_invita_link");
				obj.addClass("disable").off('click');
				$("input[type=checkbox]", obj).attr("checked", true);
			}else
				$("#fb_invita_link").removeClass("disable");

			// Paso 4, enviar feedback
			if(data.status1.feedback == "1"){
				var obj = $("#prom_feedback");
				obj.addClass("disable").off('click');
				$("input[type=checkbox]", obj).attr("checked", true);
				$("#modal_feedback").modal("hide");
			}else
				$("#prom_feedback").removeClass("disable");

			// anima la barra de progreso
			$(".progress .bar").animate({
				width: data.status1.progress+"%"
			}, 500);

			// si se completaron todos los pasos redirecciona para generar le cupon
			if (data.status1.progress == 100) {
				setTimeout(function(){
					window.location = base_url+"promotions";
				}, 1400);
			}
		}
	}

	function myalert(msg, ico, id){
		var valert = $("#"+(id? id: "promo_alert") );
		
		valert.removeClass("alert-success alert-info alert-error alert-block");
		valert.addClass("alert-"+ico).show(300);
		$("span", valert).html(msg);
		setTimeout(function(){
			valert.hide(500);
		}, 5000);
	}

	objr.init = init;
	return objr;
})(jQuery);