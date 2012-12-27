/**
 * Obj para crear un loader cuando se use Ajax
 */
var loader = {
	create: function(wrapper){
		var css = 'style="position: absolute;left:40%; top:0px;background-color:#F9EDBE;padding:5px 10px; z-index:600;"', 
		html = '<div id="ajax-loader" {css}> <img src="'+base_url+'application/images/bootstrap/ajax-loaders/ajax-loader-9.gif" width="24" height="24"> Cargando...</div>';
		
		if (wrapper){
			$(wrapper).append(html.replace("{css}", ""));
		}else{
			$("body").append(html.replace("{css}", css));
		}
	},
	close: function(){
		$("#ajax-loader").remove();
	}
};