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

		$("#fb_comparte_link").on("click", function(){
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


		$("#fb_invita_link").on("click", function(){
			FB.ui({method: 'apprequests',
			  message: 'Prueba dialog request'
			}, requestInviteLink);
		});

	}

	function requestInviteLink(response) {
	  console.log(response);
	}

	function requestShareLink(response) {
		if (response && response.post_id) {
		  alert('Post was published.');
		} else {
		  alert('Post was not published.');
		}
	}

	objr.init = init;
	return objr;
})(jQuery);