$(function(){

	$('.scroll-pane').jScrollPane();

	$("#nav_page_save").on("click", function(){
		var img_in_page = $(".img_in_page"), params = {};

		if (img_in_page.length > 0){
			params.status   = true;
			params.id_page  = $("#idpage").val();
			params.id_ypage = $("#id_ypage").val();
			params.photos   = new Array();
			img_in_page.each(function(){
				var vthis = $(this);
				if(vthis.attr("data-idframe") && vthis.attr("data-idphoto")){
					params.photos.push({
						id_photo: vthis.attr("data-idphoto"),
						id_page_img: vthis.attr("data-idpagimg"),
						id_frame: vthis.attr("data-idframe")
					});
				}else{
					params.status = false;
					noty({"text": "Selecciona una foto y el marco para las imagenes de la pagina.", "layout":"topRight", "type": "error"});
					return false;
				}
			});

			if (params.status){ //guardamos la pagina
				loader.create();
				$.post(base_url+"yuppics/save_page", params, function(data){
					if(data.msg.ico = "success"){
					}
					noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
				}, "json").complete(function(){
					loader.close();
				});
			}
		}else
			noty({"text": "Selecciona la Acomodación de imágenes.", "layout":"topRight", "type": "error"});
	});

	// evento para construir la pagina del book
	var pag_active = $("#pag_active");
	$(".prev_pag").on("click", function(){
		var vthis = $(this), info = $.parseJSON(vthis.attr("data-info")),
		html="";

		$("#idpage").val(vthis.attr("data-id"));
		for (var i = 0; i < info.length; i++) {
			html += '<div class="img_in_page" style="top:'+info[i].coord_y+'%;left:'+info[i].coord_x+'%;width:'+info[i].width+'%;height:'+info[i].height+'%;" '+
				'data-idimg="'+info[i].id_img+'" data-idpagimg="'+info[i].id_page_img+'" data-width="'+info[i].width+'" data-height="'+info[i].height+'">'+
				'	<div class="photo"></div>'+
				'	<div class="frame"></div>'+
				'	<span class="aviary"><i class="icon-picture"></i></span>'+
				'</div>';
		};
		pag_active.html(html);
	});

	// evento para cargar el borde de una imagen
	$(".frame_photo").on("click", function(){
		var vthis = $(this), img_sel = $(".img_in_page.active"),
		params = {
			id_frame: vthis.attr("data-id"),
			id_img: img_sel.attr("data-idimg")
		};

		if (params.id_frame && params.id_img){
			loader.create();
			$.getJSON(base_url+"yuppics/getFrame", params, function(data){
				if (data.msg.ico == "success") {
					img_sel.attr("data-idframe", data.marco.id_frame);
					$(".frame", img_sel).html('<img src="'+base_url+data.marco.url_frame+'">');
					$(".frame img", img_sel).load(function(){
						this.width = img_sel.width();
						this.height = img_sel.height();
					});
				}else
					noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
			}).complete(function(){
				loader.close();
			});
		}else{
			noty({"text": "Activa una imagen para asignarle el borde.", "layout":"topRight", "type": "error"});
		}
	});

	// Evento para marcar y desmarcar la imgen activa del book
	$(document).on("click", ".img_in_page", function(){
		var vthis = $(this), band = vthis.is(".active");
		$(".img_in_page").removeClass("active");
		if(!band)
			vthis.addClass("active");
	});


	// Evento asigna la foto al book, con la imagen activa
	$(document).on("click", ".setphoto", function(){
		var vthis = $(this), info = $.parseJSON(vthis.attr("data-info")),
			img_sel = $(".img_in_page.active"),
			img_sel_width = parseInt(img_sel.css('width')),
			img_sel_height = parseInt(img_sel.css('height'));

		if (img_sel.length > 0) {
			// asigna la imagen y se redimenciona
			img_sel.attr("data-idphoto", info.id_photo);
			$(".photo", img_sel).html('<img src="'+base_url+info.url_img+'">');
			$(".photo img", img_sel).load(function(){
				var diff_pix; // this=img del load

				if (img_sel_width > img_sel_height) {
					diff_pix = img_sel_width / this.width;

					diff_pix_width = img_sel_width;
					diff_pix_height = parseInt( (diff_pix * this.height) );
				} else {
					diff_pix = img_sel_height / this.height;

					diff_pix_width = parseInt( (diff_pix * this.width) );
					diff_pix_height = img_sel_height;
				}
				this.width = diff_pix_width;
				this.height = diff_pix_height;

				loader.close();
			});
			loader.create();
		}
	})

});



var yuppic_book = (function($){
	var objr = {};

	function init(){

	}

})(jQuery);