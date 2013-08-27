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
	tema_frm_imagen, progress_img_fondo, tema_frm_franja, 
	titulo_yuppic, autor_yuppic,
	minibox_color_fondo, minibox_color_texto,
	color_franja, minibox_color_franja;

	function init(){
		pagination_themes();

		//size el fondeo madera
		$(".row-fluid.contenido_crea_yupp").css("min-height", $(document).height()-185);

		//si la imagen existe se asigna el evento para mover
		$(".img_move_preview").draggable({
			revert: false,
			scroll: false
		});

		//evento al cambiar el check de pattern en la imagen del preview
		$("#pattern_imagen_fondo").on("change", function(){
			var vthis = $(this), img = $(".img_move_preview"), img_str="";
			// if(vthis.is(":checked")){
				img_str = img.attr("src").replace(base_url, "");
				preview_setImage(img_str.replace("_thumb", ""), img_str);
			// }else{

			// }
		});
		$(".img_move_preview").load(function(){
			redimImgPreview(this, $("#tema_prev_yuppic"));
		});


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


		// Objetos y eventos para cambiar el color de fondo y texto del yuppic
		colorpicker_fondo   = $('#colorpicker_fondo'); 
		color_fondo         = $("#color_fondo");
		tema_prev_yuppic    = $("#tema_prev_yuppic");
		minibox_color_fondo = $("#minibox_color_fondo");
		color_fondo.ColorPicker({  			//colorpicker del color de fondo
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
				tema_prev_yuppic.css("background-color", "#"+hex);
				minibox_color_fondo.css("background-color", "#"+hex);
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			},
			onChange: function (hsb, hex, rgb) {
				tema_prev_yuppic.css("background-color", "#"+hex);
				minibox_color_fondo.css("background-color", "#"+hex);
				color_fondo.val(hex);
			}
		}).on('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
			tema_prev_yuppic.css("background-color", "#"+this.value);
			minibox_color_fondo.css("background-color", "#"+this.value);
		});
		minibox_color_fondo.on('click', function(){
			color_fondo.click();
		});
		// color_fondo.minicolors();
		
		colorpicker_texto   = $('#colorpicker_texto');
		color_texto         = $("#color_texto");
		minibox_color_texto = $("#minibox_color_texto");
		color_texto.ColorPicker({			   //colorpicker del color de texto
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
				tema_prev_yuppic.css("color", "#"+hex);
				tema_prev_titulo.css("color", "#"+hex);
				tema_prev_autor.css("color", "#"+hex);
				minibox_color_texto.css("background-color", "#"+hex);
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			},
			onChange: function (hsb, hex, rgb) {
				tema_prev_yuppic.css("color", "#"+hex);
				tema_prev_titulo.css("color", "#"+hex);
				tema_prev_autor.css("color", "#"+hex);
				minibox_color_texto.css("background-color", "#"+hex);
				color_texto.val(hex);
			}
		}).on('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
			tema_prev_yuppic.css("color", "#"+this.value);
			tema_prev_titulo.css("color", "#"+this.value);
			tema_prev_autor.css("color", "#"+this.value);
			minibox_color_texto.css("background-color", "#"+this.value);
		});
		minibox_color_texto.on('click', function(){
			color_texto.click();
		});
		// color_texto.minicolors();

		// Objetos y eventos para cambiar el color de la franja
		color_franja            = $("#color_franja"); //picker
		background_franja_color = $("#background_franja_color");
		minibox_color_franja    = $("#minibox_color_franja");
		bgtitulo                = $(".bgtitulo");
		color_franja.ColorPicker({  			//colorpicker del color de fondo
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
				bgtitulo.css("background-color", "#"+hex);
				minibox_color_franja.css("background-color", "#"+hex);
				background_franja_color.val("#"+hex);
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			},
			onChange: function (hsb, hex, rgb) {
				bgtitulo.css("background-color", "#"+hex);
				minibox_color_franja.css("background-color", "#"+hex);
				color_franja.val(hex);
				background_franja_color.val("#"+hex);
			}
		}).on('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
			bgtitulo.css("background-color", "#"+this.value);
			minibox_color_franja.css("background-color", "#"+this.value);
			background_franja_color.val("#"+this.value);
		});
		minibox_color_franja.on('click', function(){
			color_franja.click();
		});


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

		// Eventos para subir la imagen de la franja
		tema_frm_franja    = $("#tema_frm_franja");
		tema_frm_franja.ajaxForm({
			beforeSend: function() {
				tema_frm_franja.hide(300);
				progress_img_fondo.show();
				$(".bar", progress_img_fondo).width('0%');
			},
			uploadProgress: function(event, position, total, percentComplete) {
				$(".bar", progress_img_fondo).width(percentComplete + '%');
			},
			complete: function(xhr){
				var data = $.parseJSON(xhr.responseText);
				tema_frm_franja.show(70);
				progress_img_fondo.hide(70);

				if (data.status){
					$(".bgtitulo").css("background", "url('"+data.resp.path+"')");
					$("#background_franja").val(data.resp.path);
				}
			}
		});
		// Evento para quitar la imagen seleccionada del yuppic
		$("#remove_image_franja").on("click", function(){
			$(".bgtitulo").css("background", "none");
			$("#background_franja").val('');
		});


		// Evento para seleccionar uno de los temas predefinidos
		$(document).on("click", ".use-theme", function(){
			var use_theme = $(".use-theme");
			var obj = $(this), img_quit = false,
			img = obj.attr("data-img"), imgthum = obj.attr("data-imgthum"), 
			colortexto = obj.attr("data-colortexto"), 
			colorfondo = obj.attr("data-colorfondo"),
			font_cover                 = obj.attr("data-fontcover"),
			background_franja          = obj.attr("data-backgroundfranja"),
			background_franja_color    = obj.attr("data-backgroundfranjacolor"),
			background_franja_position = obj.attr("data-backgroundfranjaposition");
			var bgtitulo = $(".bgtitulo");

			//si es un tema seleccionado le quita la seleccion
			if (obj.is(".btn-warning")) {
				colortexto = "#fff";
				colorfondo = "#CCC";
				img_quit = true;
				img = "";
				bgtitulo.css("background", "none");

				obj.removeClass("btn-warning").text("Usar tema");
			}else{
				use_theme.removeClass("btn-warning").text("Usar tema");
				obj.addClass("btn-warning").text("Quitar tema");
			}

			preview_setImage(img, imgthum, img_quit);

			// color_texto.minicolors('value', colortexto);
			// colorpicker_texto.css("background-color", colortexto);
			color_texto.val(colortexto.replace("#", ""));
			tema_prev_yuppic.css("color", colortexto);
			tema_prev_titulo.css("color", colortexto);
			tema_prev_autor.css("color", colortexto);
			minibox_color_texto.css("background-color", colortexto);


			// color_fondo.minicolors('value', colorfondo);
			// colorpicker_fondo.css("background-color", colorfondo);
			color_fondo.val(colorfondo.replace("#", ""));
			tema_prev_yuppic.css("background-color", colorfondo);
			minibox_color_fondo.css("background-color", colorfondo);

			tema_prev_yuppic.css({
				color: colortexto,
				backgroundColor: colorfondo
			});
			tema_prev_titulo.css("color", colortexto);
			tema_prev_autor.css("color", colortexto);

			// CONFIGURA LA FRANJA Y LA FUENTE
			switch(background_franja_position){
				case 't': bgtitulo.css('top', '0px'); break;
				case 'b': bgtitulo.css('bottom', '0px'); break;
				default: bgtitulo.css('top', ((tema_prev_yuppic.height()-bgtitulo.height())/2) )+"px";
			}

			font_cover = (font_cover!=''? "'"+font_cover+"'": 'Arial, Helvetica, sans-serif')
			bgtitulo.css("font-family", font_cover);
			bgtitulo.css("background", "none");
			if(background_franja != ''){
				bgtitulo.css("background", "url('"+background_franja+"')");
			}else{
				bgtitulo.css("background-color", ""+background_franja_color+"");
			}
			$("#font_cover").val(font_cover);
			$("#background_franja_position").val(background_franja_position);
			$("#background_franja").val(background_franja);
			$("#background_franja_color").val(background_franja_color);

		});

		//evento para cambiar la posicion de la franja
		$("#posicion_franja").on("change", function(){
			// CONFIGURA LA FRANJA Y LA FUENTE
			var poss = $(this).val(), 
			bgtitulo = $(".bgtitulo");
			bgtitulo.css('top', 'inherit').css('bottom', 'inherit');
			switch(poss){
				case 't': bgtitulo.css('top', '0px'); break;
				case 'b': bgtitulo.css('bottom', '0px'); break;
				default: bgtitulo.css('top', ((tema_prev_yuppic.height()-bgtitulo.height())/2) )+"px";
			}
			$("#background_franja_position").val(poss);
		});

		search_themes();

		$("#btn_select_theme, #btn_select_theme2").on("click", save_theme);
	}


	function preview_setImage(image, thume, remove){
		remove = (remove? remove: false);
		var pattern_img = $("#pattern_imagen_fondo");

		if (remove){
			tema_prev_yuppic.css("background-image", "none");
			$("#tema_prev_yuppic .img_move_preview").hide();
		}else{
			if(pattern_img.is(":checked")){ //si esta activado el pattern
				tema_prev_yuppic.css("background-image", "url("+base_url+image+")")
												.css("background-repeat", "repeat");
				$("#tema_prev_yuppic .img_move_preview").hide();
			}else{
				tema_prev_yuppic.css("background-image", "none");
				$("#tema_prev_yuppic .img_move_preview").attr("src", base_url+thume).show();
			}
		}
		$("#path_imagen_fondo").val(image);
	}

	function redimImgPreview(img, content){
		var diff_pix; // img=img del load

		diff_pix = content.height() / img.height;

		diff_pix_width = parseInt( (diff_pix * img.width) );
		diff_pix_height = content.height();

		img.width = diff_pix_width;
		img.height = diff_pix_height;
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
			title: tema_prev_titulo.val(),
			author: tema_prev_autor.val(),
			background_img: $("#path_imagen_fondo").val(),
			background_color: "#"+color_fondo.val().replace("#", ""),
			text_color: "#"+color_texto.val().replace("#", ""),
			bg_pattern: ($("#pattern_imagen_fondo").is(":checked")? 'si': 'no'),

			font_cover: $("#font_cover").val(),
			background_franja: $("#background_franja").val(),
			background_franja_color: $("#background_franja_color").val(),
			background_franja_position: $("#background_franja_position").val(),
		}, img_prev = $(".img_move_preview");

		if(params.bg_pattern == 'no'){ //si pattern esta desactivado se guardan las coordenadas
			var tema_prev_yuppic = $("#tema_prev_yuppic");

			params.bg_img_x = trunc2Dec( (parseInt(img_prev.css("left")) * 100 / (tema_prev_yuppic.width()) ));
			params.bg_img_y = trunc2Dec( (parseInt(img_prev.css("top")) * 100 / (tema_prev_yuppic.height()) ));
		}

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

function trunc2Dec(num) {
	return Math.floor(num * 100) / 100;
}