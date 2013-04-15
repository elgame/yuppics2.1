$(function(){
	faqssqs.init();
});

var faqssqs = (function($){
	var objr = {};

	function init(){
		$('.accord_barr_rig').on('shown', showFaqssqs);
		$('.accord_barr_rig').on('hide', hideFaqssqs);
	}

	function showFaqssqs(){
		var obj = $(".accordion-body.in.collapse", this).parents(".accordion-group");

		if (obj.attr('data-type') == 'inverse') {
			$(".accordion-body", obj).addClass("actifaqs");
		}else{
			obj = $(".accordion-heading .accordion-toggle", obj);
			obj.addClass("actifaqs");
			$("i", obj).addClass("actifaqs");
		}
	}
	function hideFaqssqs(){
		var obj = $(".accordion-body.in.collapse", this).parents(".accordion-group");

		if (obj.attr('data-type') == 'inverse') {
			$(".accordion-body", obj).removeClass("actifaqs");
		}else{
			obj = $(".accordion-heading .accordion-toggle", obj);
			obj.removeClass("actifaqs");
			$("i", obj).removeClass("actifaqs");
		}
	}

	objr.init = init;
	return objr;
})(jQuery);


function newsletter_success(){
	setTimeout(function(){
		window.location.href = window.location.href;
	}, 1000);
}