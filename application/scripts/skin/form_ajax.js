
/**
 * Inicializa el login por ajax
 */
var form_ajax = (function($){
	var objr = {};
	function initialize(){
		$("form[data-sendajax=true]").each(function(){
			var target = $(this);
			target.ajaxForm({ 
				success:  function(data){
					success(data, target)
				},
				dataType: 'json'        // 'xml', 'script', or 'json' (expected server response type)
			});

			$('.close', target).on('click', function(){
				$("#"+target.attr("data-alert")).hide(500);
			});
		});
	}
	function success(data, target){
		var valert = $("#"+target.attr("data-alert"));
		
		valert.removeClass("alert-success alert-info alert-error alert-block");
		valert.addClass("alert-"+data.frm_errors.ico).show(300);
		$("span", valert).html(data.frm_errors.msg);

		if (target.attr('data-reset') == 'true' && data.frm_errors.ico == 'success')
		{
			// valert.hide(500);
			target.clearForm();
		}

		if (target.attr('data-callback') != '' && data.frm_errors.ico == 'success'){
			eval(target.attr('data-callback')+"();");
		}
	}
	objr.init = initialize;
	return objr;
})(jQuery);