$(function(){
	yuppic_tema.init();

	$(".barra_menu_fix").removeClass('barra_menu_fix').addClass('barra_menu_fix4').css({
		backgroundColor: "#15181d"
	});
});

var yuppic_tema = (function($){
	var objr = {},
	colorpicker_fondo, color_fondo, tema_prev_yuppic,
	colorpicker_texto, color_texto,
	tema_prev_titulo, tema_prev_autor,
	tema_frm_imagen, progress_img_fondo,
	titulo_yuppic, autor_yuppic;

	function init(){
		pagination_themes();


		// Objetos y eventos para cambiar el color de fondo y texto del yuppic
		colorpicker_fondo = $('#colorpicker_fondo'); 
		color_fondo       = $("#color_fondo");
		tema_prev_yuppic  = $("#tema_prev_yuppic");
		color_fondo.minicolors();
		// colorpicker_fondo.farbtastic(function(color){
		// 	colorpicker_fondo.css("background-color", color);
		// 	color_fondo.val(color);
		// 	tema_prev_yuppic.css("background-color", color);
		// });
		
		colorpicker_texto = $('#colorpicker_texto');
		color_texto       = $("#color_texto");
		color_texto.minicolors();
		// colorpicker_texto.farbtastic(function(color){
		// 	colorpicker_texto.css("background-color", color);
		// 	color_texto.val(color);
		// 	tema_prev_yuppic.css("color", color);
		// });


		// Eventos para el titulo y autor del yuppic
		tema_prev_titulo = $("#tema_prev_titulo");
		tema_prev_autor  = $("#tema_prev_autor");
		titulo_yuppic    = $("#titulo_yuppic");
		autor_yuppic     = $("#autor_yuppic");
		// titulo_yuppic.on("keyup", function(evt){
		// 	tema_prev_titulo.val($(this).val());
		// });
		// autor_yuppic.on("keyup", function(evt){
		// 	tema_prev_autor.val($(this).val());
		// });
		// tema_prev_titulo.on("keyup", function(evt){
		// 	titulo_yuppic.val($(this).val());
		// });
		// tema_prev_autor.on("keyup", function(evt){
		// 	autor_yuppic.val($(this).val());
		// });


		// Eventos para subir la imagen del tema
		tema_frm_imagen    = $("#tema_frm_imagen");
		progress_img_fondo = $("#progress_img_fondo");
		tema_frm_imagen.ajaxForm({
			beforeSend: function() {
				tema_frm_imagen.hide(300);
				progress_img_fondo.show();
				$(".bar", progress_img_fondo).width('0%');
			},
			uploadProgress: function(event, position, total, percentComplete) {
				$(".bar", progress_img_fondo).width(percentComplete + '%');
			},
			complete: function(xhr){
				var data = $.parseJSON(xhr.responseText);
				tema_frm_imagen.show(70);
				progress_img_fondo.hide(70);

				if (data.status){
					preview_setImage(data.resp.path, data.resp.thumb);
				}
			}
		});
		// Evento para quitar la imagen seleccionada del yuppic
		$("#remove_imagesel").on("click", function(){
			preview_setImage("", "", true);
		});


		// Evento para seleccionar uno de los temas predefinidos
		$(document).on("click", ".use-theme", function(){
			var use_theme = $(".use-theme");
			var obj = $(this), img_quit = false,
			img = obj.attr("data-img"), imgthum = obj.attr("data-imgthum"), 
			colortexto = obj.attr("data-colortexto"), colorfondo = obj.attr("data-colorfondo");

			//si es un tema seleccionado le quita la seleccion
			if (obj.is(".btn-warning")) {
				colortexto = "#fff";
				colorfondo = "#CCC";
				img_quit = true;
				img = "";

				obj.removeClass("btn-warning").text("Usar tema");
			}else{
				use_theme.removeClass("btn-warning").text("Usar tema");
				obj.addClass("btn-warning").text("Quitar tema");
			}

			preview_setImage(img, imgthum, img_quit);

			color_texto.minicolors('value', colortexto);
			colorpicker_texto.css("background-color", colortexto);

			color_fondo.minicolors('value', colorfondo);
			colorpicker_fondo.css("background-color", colorfondo);
			tema_prev_yuppic.css({
				color: colortexto,
				backgroundColor: colorfondo
			});
			tema_prev_titulo.css("color", colortexto);
			tema_prev_autor.css("color", colortexto);
		});

		search_themes();

		$("#btn_select_theme, #btn_select_theme2").on("click", save_theme);
	}


	function preview_setImage(image, thume, remove){
		remove = (remove? remove: false);

		if (remove)
			tema_prev_yuppic.css("background-image", "none");
		else
			tema_prev_yuppic.css("background", "url("+base_url+thume+") no-repeat");
		$("#path_imagen_fondo").val(image);
	}

	/**
	 * Busca themes por ajax y muestra los resultados
	 * @return {[type]} [description]
	 */
	function search_themes(){
		
		$("#tema_frm_buscar").ajaxForm({
			beforeSend: function() {
				loader.create();
			},
			success:  function(data){
				$("#list_themes").html(data);
				pagination_themes();
			},
			dataType: 'html',
			complete: function(xhr){
				loader.close();
			}
		});
	}

	function pagination_themes(){
		$("div.pagination").jPages({
			containerID: "list_themes",
			perPage      : 1,
			startPage    : 1,
			startRange   : 1,
			midRange     : 5,
			endRange     : 1
		});
	}


	function save_theme(){
		var msg_modal = $("#messajes_alerts"),
		params = {
			title: $("#tema_prev_titulo").val(),
			author: $("#tema_prev_autor").val(),
			background_img: $("#path_imagen_fondo").val(),
			background_color: color_fondo.val(),
			text_color: color_texto.val()
		};

		$.post(base_url+"yuppics/theme_save", params, function(data){
			if (data.frm_errors.ico == 'success') {
				window.location = base_url+"yuppics/photos";
			}else{
				$(".modal-body", msg_modal).html(data.frm_errors.msg);
				msg_modal.modal('show');
			}
		}, "json");
	}



	function selThemesAutor(obj){
		$("#appendedInputButtons").val(obj);
		$("button.btn.submmit").click();

		if(obj == '')
			obj = 'Todos los temas';
		$("#autor_sel_tthem").text(obj);
	}


	objr.init = init;
	objr.selThemesAutor = selThemesAutor;

	return objr;
})(jQuery);