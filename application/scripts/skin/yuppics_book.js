$(function(){

	$('.scroll-pane').jScrollPane();

	// Evento para navegar en las paginas del book y guardarlas
	$("#next_page_save, #prev_page_save").on("click", function(){
		var vvthis = $(this), page_edited = $("#page_edited"),
		id_ypage = $("#id_ypage");

		if (page_edited.val() == "false" && id_ypage.val() != ""){ //no se modifica nada de la pagina
			load_page(vvthis.attr("data-page"));
		}else{
			if (id_ypage.val() == "") {
				vvthis.is("#next_page_save")? save_page_event("next"):
					(page_edited.val()=="false"? load_page(vvthis.attr("data-page")): save_page_event("prev"));
			}else{
				msb.confirm("Quieres guardar los cambios de la página?", "", this, function(obj){
					save_page_event( (vvthis.is("#prev_page_save")? "prev": "next") );
				}, function(obj){
					load_page(vvthis.attr("data-page"));
				});
			}

			$("#page_edited").val("false"); // se edito la pagina - reset
		}
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
				'	<span class="live_aviary"><i class="icon-picture"></i></span>'+
				'</div>';
		};
		pag_active.html(html);

		$("#page_edited").val("true"); // se edito la pagina
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
						redimImgFrame(this, img_sel);
					});

					$("#page_edited").val("true"); // se edito la pagina
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
			img_sel_height = parseInt(img_sel.css('height')),
			attr_img;

		if (img_sel.length > 0) {
			loader.create();

			attr_img = 'img_'+img_sel.attr("data-idimg")+img_sel.attr("data-idpagimg");
			$(".live_aviary", img_sel).attr("data-id", attr_img);

			// asigna la imagen y se redimenciona
			img_sel.attr("data-idphoto", info.id_photo);
			$(".photo", img_sel).html('<img id="'+attr_img+'" src="'+base_url+info.url_img+'">');
			$(".photo img", img_sel).load(function(){
				redimImgPhoto(this, img_sel_width, img_sel_height);
				loader.close();
			});

			$("#page_edited").val("true"); // se edito la pagina
		}
	});

	// Evento para eliminar una pagina del book
	$("#deletePage").on("click", function(){
		var params = {id_ypage: $("#id_ypage").val() };

		if (params.id_ypage != "") {
			msb.confirm("Estas seguro de eliminar la página?", "", this, function(obj){
				loader.create();
				$.post(base_url+"yuppics/delete_page", {id_ypage: $("#id_ypage").val() }, function(data){
					if (data.msg.ico == "success") {
						load_page(1);
					}
					noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
				}, "json").complete(function(){
					loader.close();
				});
			});
		}else
			noty({"text": "La página no se puede eliminar.", "layout":"topRight", "type": "error"});
	});

	// Evento para hacer el magic page
	$("#magicPage").on("click", function(){
		var vthis = $(this), img_sel = $(".img_in_page.active");

		if (img_sel.length > 0) {
			$(".img_in_page:not(.active)").each(function(){
				var vvthis = $(this);
				vvthis.attr("data-idframe", img_sel.attr("data-idframe"));
				$("#page_edited").val("true"); // se edito la pagina

				loader.create();
				$.getJSON(base_url+"yuppics/getFrame", { id_frame: vvthis.attr("data-idframe"), id_img: vvthis.attr("data-idimg") },
					function(data){
						if (data.msg.ico == "success") {
							$(".frame", vvthis).html('<img src="'+base_url+data.marco.url_frame+'">');
							$(".frame img", vvthis).load(function(){
								redimImgFrame(this, vvthis);
							});
						}else
							noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
				}).complete(function(){
					loader.close();
				});
			});
		}else
			noty({"text": "Activa una imagen para copiar su estilo.", "layout":"topRight", "type": "error"});
	});

	// Evento para hacer el magic book magic_book
	$("#magicBook").on("click", function(){
		msb.confirm("Seguro de aplicar el Magia Book? <br> las paginas previamente configuradas se perderan.", "", this, function(){
			var img_sel = $(".img_in_page.active"), params = {id_page: $("#idpage").val(), id_frame: []};

			if (params.id_page != "") {
				$(".img_in_page").each(function(){
					params.id_frame.push($(this).attr("data-idframe"));
				});
				loader.create();
				$.getJSON(base_url+"yuppics/magic_book", params,
					function(data){
						if (data.msg.ico == "success") {
							load_page(1);
						}
						noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
				}).complete(function(){
					loader.close();
				});
			}else
				noty({"text": "Configura la página para aplicar el magia book.", "layout":"topRight", "type": "error"});
		});
	});


	$("#finalizarCompra").on("click", function(){
		msb.confirm("Estas seguro de finalizar la compra?", "", this, function(){
			window.location = base_url+"buy?y="+$("#id_yuppic").val();
		});
	});

	// Evento para asignar el plug aviary
	launchEditor();

	redimPage();


	$(document).on('click', 'button#delete', function () {
    deleteClonePhoto(this);
    reinitializeScrollPane();
  });

 $('#removeall').on('click', function(){
    loader.create();
    $('button#delete').each(function(i, e){
      deleteClonePhoto(e);
    });
    reinitializeScrollPane();
    $('.jspPane').css('left', '0px')
    loader.close();
  });

});



function save_page_event(direction){
	var img_in_page = $(".img_in_page"), params = {};

	if (img_in_page.length > 0){
		params.status    = true;
		params.id_page   = $("#idpage").val();
		params.id_ypage  = $("#id_ypage").val();
		params.num_pag   = $("#num_pag").val();
		params.direction = direction;
		params.photos    = new Array();
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
				noty({"text": "Selecciona una foto y el marco para las imagenes de la página.", "layout":"topRight", "type": "error"});
				return false;
			}
		});

		if (params.status == true){ //guardamos la pagina
			loader.create();
			$.post(base_url+"yuppics/save_page", params, function(data){
				if(data.msg.ico = "success"){
					build_load_page(data);
				}
				noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
			}, "json").complete(function(){
				loader.close();
			});
		}
	}else
		noty({"text": "Selecciona la Acomodación de imágenes.", "layout":"topRight", "type": "error"});
}

function load_page(num_pag){
	loader.create();
	$.getJSON(base_url+"yuppics/load_page", {"num_pag": num_pag}, function(data){
		if(data.msg.ico = "success"){
			build_load_page(data);
		}
		// noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
	}, "json").complete(function(){
		loader.close();
	});
}

function build_load_page(data){
	if (data.page == false){ //no existe la sig pag, nueva pagina
		var num_pag = $("#num_pag");
		$("#pag_active").html("");
		$("#idpage").val("");
		$("#id_ypage").val("");
		num_pag.val(parseInt(num_pag.val())+1);
		$("#barratop_pagina").text(num_pag.val());
		$("#prev_page_save").attr("data-page", parseInt(num_pag.val())-1).hide(200);
		$("#next_page_save").attr("data-page", parseInt(num_pag.val())+1);
		if(parseInt(num_pag.val()) > 1)
			$("#prev_page_save").show(200);
	}else{
		var html = "";
		var num_pag = $("#num_pag");
		$("#idpage").val(data.page.id_page);
		$("#id_ypage").val(data.page.id_ypage);
		num_pag.val(data.page.num_pag);
		$("#barratop_pagina").text(num_pag.val());
		$("#prev_page_save").attr("data-page", parseInt(num_pag.val())-1).hide(200);
		$("#next_page_save").attr("data-page", parseInt(num_pag.val())+1);
		if(data.page.num_pag > 1)
			$("#prev_page_save").show(200);

		for (var i = 0; i < data.page.images.length; i++) {
			html += '<div class="img_in_page" style="top:'+data.page.images[i].coord_y+'%;left:'+data.page.images[i].coord_x+'%;width:'+data.page.images[i].width+'%;height:'+data.page.images[i].height+'%;" '+
				'data-idimg="'+data.page.images[i].id_img+'" data-idpagimg="'+data.page.images[i].id_page_img+'" data-width="'+data.page.images[i].width+'" data-height="'+data.page.images[i].height+'" '+
				'data-idframe="'+data.page.images[i].id_frame+'" data-idphoto="'+data.page.images[i].id_photo+'">'+
				'	<div class="photo">'+ (data.page.images[i].url_img? '<img id="img_'+data.page.images[i].id_img+data.page.images[i].id_page_img+'" src="'+base_url+data.page.images[i].url_img+'">': '') +'</div>'+
				'	<div class="frame">'+ (data.page.images[i].url_frame? '<img src="'+base_url+data.page.images[i].url_frame+'">': '')+'</div>'+
				'	<span class="live_aviary" data-id="img_'+data.page.images[i].id_img+data.page.images[i].id_page_img+'"><i class="icon-picture"></i></span>'+
				'</div>';
		};

		$("#pag_active").html(html);
		redimPage();
	}
}


function redimPage(){
	$(".img_in_page").each(function(){
		var img_sel = $(this);
		$(".photo img", img_sel).load(function(){
			redimImgPhoto(this, img_sel.width(), img_sel.height());
		});
		$(".frame img", img_sel).load(function(){
			redimImgFrame(this, img_sel);
		});
	});
}
function redimImgPhoto(vthis, img_sel_width, img_sel_height){
	var diff_pix; // vthis=img del load

	if (img_sel_width > img_sel_height) {
		diff_pix = img_sel_width / vthis.width;

		diff_pix_width = img_sel_width;
		diff_pix_height = parseInt( (diff_pix * vthis.height) );
	} else {
		diff_pix = img_sel_height / vthis.height;

		diff_pix_width = parseInt( (diff_pix * vthis.width) );
		diff_pix_height = img_sel_height;
	}
	vthis.width = diff_pix_width;
	vthis.height = diff_pix_height;
}
function redimImgFrame(vthis, img_sel){
	vthis.width = img_sel.width();
	vthis.height = img_sel.height();
}


var featherEditor;

function launchEditor() {
	featherEditor = new Aviary.Feather({
		apiKey: '2e63f9892',
		apiVersion: 2,
		tools: 'all',
		appendTo: '',
		onSave: function(imageID, newURL) {
			var img = $("#"+imageID);
			img.attr("src", img.attr("src"));
		},
		postUrl: base_url+'yuppics/save_aviary/'
	});

	$(document).on("click", ".live_aviary", function(){
		var vthis = $(this), imgsel;

		if (vthis.attr('data-id') != '') {
			imgsel = $("#"+vthis.attr('data-id'));

			featherEditor.launch({
				image: imgsel.attr("id"),
				url: imgsel.attr("src"),
				postData: imgsel.attr("src").replace(base_url, "")
			});
		}
	
	});
	return false;
}



function reinitializeScrollPane() {
  var pane = $('.horizontal-only'),
    api = pane.data('jsp');
    api.reinitialise();
}

function deleteClonePhoto(obj) {
  var obj = $(obj),
    objtotalch = $('#total-choose');

      if (obj.attr('data-exist') == 'false') {
        obj.parent().remove();
        $('input.src-'+obj.attr('data-id')).remove();
        $('li#'+obj.attr('data-id')).removeClass('choose-photo').find('.choosed').remove();
      }
      else {
        loader.create()
        obj.parent().remove();
        $('input.src-'+obj.attr('data-id')).remove();
        $.post(base_url + 'yuppics/photo_delete', {'idp': $(obj).attr('data-id')} , function(data){
          loader.close()
        });
      }

      var  obj_content_selected_photos = $('#content-selected-photos'),
       px = parseInt(obj_content_selected_photos.css('width')) - 165;

    obj_content_selected_photos.css('width', px);
    var totalch = parseInt(objtotalch.html()) - 1;
    objtotalch.html(totalch);

    if (totalch < 7)
      $('.jspPane').css('left', '0px');
}





var yuppic_book = (function($){
	var objr = {};

	function init(){

	}


})(jQuery);

