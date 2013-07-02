$(function(){
	$(".barra_menu_fix").removeClass('barra_menu_fix').addClass('barra_menu_fix4').css({
		backgroundColor: "#15181d"
	});

	// Eventos click para el control del scroll dela fotos seleccionadas
  // Este es el click del boton derecho
  var contPagScroll = 0;
  $('#btn-next-scroll').on('click', function(event) {
      var obj = $('#content-selected-photos'),
          width_content = parseInt(obj.css('width'), 10);
          leftPos = parseInt(obj.scrollLeft(), 10),
          pix_recorrer = 0;

      if (width_content > (195 * 4)) {
        pix_recorrer = 195 * 4;
      } else if ((width_content < (195 * 4)) && width_content > (195 * 3)) {
        pix_recorrer = 195 * 3;
      } else if (width_content < (195 * 3) && width_content > (195 * 2)) {
        pix_recorrer = 195 * 2;
      } else {
        pix_recorrer = 195;
      }
      obj.animate({
          'scrollLeft': leftPos + pix_recorrer
      }, 300);

  });
  // Este es el click del boton izquierdo
  $('#btn-prev-scroll').on('click', function(event) {
      var obj = $('#content-selected-photos'),
          width_content = parseInt(obj.css('width'), 10);
          leftPos = parseInt(obj.scrollLeft(), 10),
          pix_recorrer = 0;


      if (width_content > (195 * 4)) {
        pix_recorrer = 195 * 4;
      } else if ((width_content < (195 * 4)) && width_content > (195 * 3)) {
        pix_recorrer = 195 * 3;
      } else if (width_content < (195 * 3) && width_content > (195 * 2)) {
        pix_recorrer = 195 * 2;
      } else {
        pix_recorrer = 195;
      }
      obj.animate({
          'scrollLeft': leftPos - pix_recorrer
      }, 300);
  });

	$('.scroll-pane').jScrollPane({
		autoReinitialise: true
	});

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
	// Evento para guardar la pagina activa
	$("#save_page_active").on("click", function(){
		var vvthis = $(this), page_edited = $("#page_edited"),
		id_ypage = $("#id_ypage");

		if (page_edited.val() == "true"){
			msb.confirm("Quieres guardar los cambios de la página?", "", this, function(obj){
				save_page_event("next");
			});

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

		images_drag_drop.setDropEvent();
		images_drag_drop.setDragEvent();
		$("#page_edited").val("true"); // se edito la pagina
	});

	// evento para cargar el borde de una imagen
	$(".frame_photo").on("click", function(){
		images_drag_drop.setFrameToPhoto($(this), $(".img_in_page.active"));
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
		var vthis = $(this), img_sel = $(".img_in_page.active");
		images_drag_drop.setPhotoToBook(vthis, img_sel);
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
		var items_pages = $("#list_acomodacion_pages .prevv a.prev_pag"), 
		items_frames = $("#list_estilos_marcos .prevv a.frame_photo"),
		items_fotos = $("#lista_fotos_usuario li .setphoto"),
		num = Math.floor((Math.random()*items_pages.length)),
		rand_frames, rand_fotos;
		$(items_pages[num]).click(); //seleccionamos un tipo de pagina

		$("#pag_active .img_in_page").each(function(){
			$(this).click();
			rand_frames = Math.floor((Math.random()*items_frames.length));
			rand_fotos = Math.floor((Math.random()*items_fotos.length));
			$(items_fotos[rand_fotos]).click(); //seleccionamos una foto
			$(items_frames[rand_frames]).click(); //seleccionamos un estilo de marco
		});


		// var vthis = $(this), img_sel = $(".img_in_page.active");

		// if (img_sel.length > 0) {
		// 	$(".img_in_page:not(.active)").each(function(){
		// 		var vvthis = $(this);
		// 		vvthis.attr("data-idframe", img_sel.attr("data-idframe"));
		// 		$("#page_edited").val("true"); // se edito la pagina

		// 		loader.create();
		// 		$.getJSON(base_url+"yuppics/getFrame", { id_frame: vvthis.attr("data-idframe"), id_img: vvthis.attr("data-idimg") },
		// 			function(data){
		// 				if (data.msg.ico == "success") {
		// 					$(".frame", vvthis).html('<img src="'+base_url+data.marco.url_frame+'">');
		// 					$(".frame img", vvthis).load(function(){
		// 						redimImgFrame(this, vvthis);
		// 					});
		// 				}else
		// 					noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
		// 		}).complete(function(){
		// 			loader.close();
		// 		});
		// 	});
		// }else
		// 	noty({"text": "Activa una imagen para copiar su estilo.", "layout":"topRight", "type": "error"});
	});

	// Evento para hacer el magic book magic_book
	$("#magicBook").on("click", function(){
		msb.confirm("Seguro de aplicar el Magia Book? <br> las paginas previamente configuradas se perderan.", "", this, function(){
			var img_sel = $(".img_in_page.active"), params = {id_page: $("#idpage").val(), id_frame: []};

			if (params.id_page != "") {
				$(".img_in_page").each(function(){
					params.id_frame.push(($(this).attr("data-idframe")? $(this).attr("data-idframe").replace("null", ""): ''));
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

	// Boton de finalizar compra
	$("#finalizarCompra").on("click", function(){
		msb.confirm("Estas seguro de finalizar la compra?", "", this, function(){
			window.location = base_url+"buy/order?y="+$("#id_yuppic").val();
		});
	});

	// Evento para asignar el plug aviary
	launchEditor();

	redimPage();

	// animacion del progress bar
	var progressbar_yuppic = $("#progressbar_yuppic .bar")
	progressbar_yuppic.animate({
		width: progressbar_yuppic.attr('data-progress')+"%"
	}, 400);

	// Eventos para eliminar imagenes de la lista
	$(document).on('click', 'button#delete', function () {
    deleteClonePhoto(this);
    reinitializeScrollPane();
  });
 $('#removeall').on('click', function(){
    loader.create();
    $('button#delete').each(function(i, e){
      deleteClonePhoto(e);
    });
    // reinitializeScrollPane();
    $('.jspPane').css('left', '0px')
    loader.close();
    window.location = base_url+"yuppics/photos";
  });


 	// Drag and drop
 	images_drag_drop.init();
});



// Guarda la configuracion de la pagina
function save_page_event(direction){
	var pag_active, img_in_page = $(".img_in_page"), params = {};

	if (img_in_page.length > 0){
		pag_active = $("#pag_active");

		params.status    = true;
		params.id_page   = $("#idpage").val();
		params.id_ypage  = $("#id_ypage").val();
		params.num_pag   = $("#num_pag").val();
		params.direction = direction;
		params.photos    = new Array();
		img_in_page.each(function(){
			var vthis = $(this), photo_pos = $(".photo", vthis);
			if(vthis.attr("data-idphoto")){
				params.photos.push({
					id_photo: vthis.attr("data-idphoto"),
					id_page_img: vthis.attr("data-idpagimg"),
					id_frame: (vthis.attr("data-idframe")? vthis.attr("data-idframe").replace("null", ""): ''),
					coord_x: trunc2Dec( (parseInt(photo_pos.css("left")) * 100 / (vthis.width()) )),
					coord_y: trunc2Dec( (parseInt(photo_pos.css("top")) * 100 / (vthis.height()) )),
					width: 0,
					height: 0
				});
			}else{
				params.status = false;
				noty({"text": "Selecciona una foto para las imagenes de la página.", "layout":"topRight", "type": "error"});
				return false;
			}
		});

		if (params.status == true){ //guardamos la pagina
			loader.create();
			$.post(base_url+"yuppics/save_page", params, function(data){
				if(data.msg.ico = "success"){
					if( validaNumPages(parseFloat(params.num_pag)+1, false) || params.direction == "prev")
						build_load_page(data);
					else
						load_page(parseFloat(params.num_pag));
				}
				noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
			}, "json").complete(function(){
				loader.close();
			});
		}
	}else
		noty({"text": "Selecciona la Acomodación de imágenes.", "layout":"topRight", "type": "error"});
}

// Carga una pagina del book
function load_page(num_pag){
	if( validaNumPages(num_pag) ){
		loader.create();
		$.getJSON(base_url+"yuppics/load_page", {"num_pag": num_pag}, function(data){
			if(data.msg.ico = "success"){
				build_load_page(data, num_pag);
			}
			// noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
		}, "json").complete(function(){
			loader.close();
		});
	}
}

function validaNumPages(num_pag, show){
	if( parseFloat(num_pag) <= 22){
		return true;
	}else{
		if(show == undefined)
			noty({"text": "No puedes agregar más de 22 páginas al yuppic", "layout":"topRight", "type": "error"});
		return false;
	}
}

// construlle los elementos de la pagina cargada
function build_load_page(data, pagina){
	if (data.page == false){ //no existe la sig pag, nueva pagina
		var num_pag = $("#num_pag"), numero;
		
		if (pagina == 1){ //si la pag 1 no se carga no hay pags
			numero = 1;
		}else{
			numero = parseInt( (num_pag.val()==''? 1: num_pag.val()) )+1;
		}

		$("#pag_active").html("");
		$("#idpage").val("");
		$("#id_ypage").val("");

		num_pag.val(numero);
		$("#barratop_pagina").text(num_pag.val());
		$("#prev_page_save").attr("data-page", parseInt(num_pag.val())-1).attr("disabled", "disabled"); //.hide(200);
		$("#next_page_save").attr("data-page", parseInt(num_pag.val())+1);
		if(parseInt(num_pag.val()) > 1)
			$("#prev_page_save").removeAttr("disabled"); //.show(200);
	}else{
		var html = "";
		var num_pag = $("#num_pag");
		$("#idpage").val(data.page.id_page);
		$("#id_ypage").val(data.page.id_ypage);
		num_pag.val(data.page.num_pag);
		$("#barratop_pagina").text(num_pag.val());
		$("#prev_page_save").attr("data-page", parseInt(num_pag.val())-1).attr("disabled", "disabled"); //.hide(200);
		$("#next_page_save").attr("data-page", parseInt(num_pag.val())+1);
		if(data.page.num_pag > 1)
			$("#prev_page_save").removeAttr("disabled"); //.show(200);

		for (var i = 0; i < data.page.images.length; i++) {
			html += '<div class="img_in_page" style="top:'+data.page.images[i].coord_y+'%;left:'+data.page.images[i].coord_x+'%;width:'+data.page.images[i].width+'%;height:'+data.page.images[i].height+'%;" '+
				'data-idimg="'+data.page.images[i].id_img+'" data-idpagimg="'+data.page.images[i].id_page_img+'" data-width="'+data.page.images[i].width+'" data-height="'+data.page.images[i].height+'" '+
				(data.page.images[i].id_frame!=null? 'data-idframe="'+data.page.images[i].id_frame+'"': '')+' data-idphoto="'+data.page.images[i].id_photo+'">'+
				'	<div class="photo" style="top:'+data.page.images[i].pos_y+'%;left:'+data.page.images[i].pos_x+'%;">'+ 
					(data.page.images[i].url_img? '<img id="img_'+data.page.images[i].id_img+data.page.images[i].id_page_img+'" src="'+base_url+data.page.images[i].url_img+'">': '') +'</div>'+
				'	<div class="frame">'+ (data.page.images[i].url_frame? '<img src="'+base_url+data.page.images[i].url_frame+'">': '')+'</div>'+
				'	<span class="live_aviary" data-id="img_'+data.page.images[i].id_img+data.page.images[i].id_page_img+'"><i class="icon-picture"></i></span>'+
				'</div>';
		};

		$("#pag_active").html(html);
		redimPage();
		images_drag_drop.setDropEvent();
		images_drag_drop.setDragEvent();
	}
}


function redimPage(){
	$(".img_in_page").each(function(){
		var img_sel = $(this), 
		img_photo = $(".photo img", img_sel),
		img_frame = $(".frame img", img_sel);
		
		img_photo.load(function(){
			redimImgPhoto(this, img_sel.width(), img_sel.height());
		}).each(function(){
			if(this.complete){
				img_photo.off('load');
				redimImgPhoto(this, img_sel.width(), img_sel.height());
			}
		});

		img_frame.load(function(){
			redimImgFrame(this, img_sel);
		}).each(function(){
			if(this.complete){
				img_frame.off('load');
				redimImgFrame(this, img_sel);
			}
		});
	});
}
function redimImgPhoto(vthis, img_sel_width, img_sel_height){
	var diff_pix; // vthis=img del load

	
		var item_sel = $(vthis).parents('.img_in_page');
		$(".msg_photo_resolution", item_sel).remove();
		if (vthis.width < 400 || vthis.height < 350) {
			item_sel.append('<span class="msg_photo_resolution"><i class="icon-warning-sign icon-white"></i> La resolución de esta imagen es demasiada pequeña y puede reflejarse en la calidad de impresión.</span>');
		}

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


// Activa el aviary
var featherEditor;
function launchEditor() {
	featherEditor = new Aviary.Feather({
		apiKey: '2e63f9892',
		apiVersion: 2,
		tools: 'all',
		appendTo: '',
		onSave: function(imageID, newURL) {
			var img = $("#"+imageID), d = new Date();
			img.attr("src", img.attr("src")+"?dt="+d.getTime());
			// .load(function(){
			// 	var img_sel = img.parents(".img_in_page");
			// 	console.log($(img_sel[0]).width());
			// 	redimImgPhoto(this, $(img_sel[0]).width(), $(img_sel[0]).height());
			// });
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


// Listado de imagenes se reinicializa
function reinitializeScrollPane() {
  // var pane = $('.horizontal-only'),
  //   api = pane.data('jsp');
  //   api.reinitialise();
}
// Elimina fotos del listado de imagenes
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


function trunc2Dec(num) {
	return Math.floor(num * 100) / 100;
}



var images_drag_drop = (function($){
	var objr = {};

	function init(){
		$(".setphoto").draggable({ 
			revert: true,
			appendTo: 'body',
			containment: 'body',
			scroll: true,
			helper: 'clone',
			zIndex: 1000,
		});

		$(".frame_photo").draggable({ 
			revert: true,
			appendTo: 'body',
			containment: 'body',
			scroll: true,
			helper: 'clone',
			zIndex: 1000,
		});

		setDropEvent();
		setDragEvent();
	}

	// Asigna el evento de drop a las imagenes del book para que cargue las fotos y marcos
	function setDropEvent(){
		$(".img_in_page").droppable({
			accept: '.setphoto, .frame_photo',
			activeClass: 'drop_hover',
			hoverClass: 'drop_hover',
			drop: function(event, ui) {
				if (ui.draggable.is(".frame_photo"))
					setFrameToPhoto(ui.draggable, $(this));
				else
					setPhotoToBook(ui.draggable, $(this));
			}
		});
	}

	// Asigna el evento de Drag a las imagenes del book, para moverlas
	function setDragEvent(){
		$(".img_in_page .photo").draggable({
			revert: false,
			scroll: false,
			stop: function( event, ui ) {
				$("#page_edited").val("true"); // se edito la pagina
			}
		});
	}

	// Asigna la foto a una de las imagenes del photobook
	function setPhotoToBook(vphoto, vimg_book){
		var info = $.parseJSON(vphoto.attr("data-info")),
			// img_sel = $(".img_in_page.active"),
			img_sel_width = parseInt(vimg_book.css('width')),
			img_sel_height = parseInt(vimg_book.css('height')),
			attr_img;

		if (vimg_book.length > 0) {
			loader.create();

			attr_img = 'img_'+vimg_book.attr("data-idimg")+vimg_book.attr("data-idpagimg");
			$(".live_aviary", vimg_book).attr("data-id", attr_img);

			// asigna la imagen y se redimenciona
			vimg_book.attr("data-idphoto", info.id_photo);
			$(".photo", vimg_book).html('<img id="'+attr_img+'" src="'+base_url+info.url_img+'">');
			$(".photo img", vimg_book).load(function(){
				redimImgPhoto(this, img_sel_width, img_sel_height);
				loader.close();
			});

			$("#page_edited").val("true"); // se edito la pagina
		}
	}

	// evento para cargar el borde de una imagen
	function setFrameToPhoto(vframe, vimg_book){
		var params = {
			id_frame: vframe.attr("data-id"),
			id_img: vimg_book.attr("data-idimg")
		};

		if (params.id_frame && params.id_img){
			loader.create();
			$.getJSON(base_url+"yuppics/getFrame", params, function(data){
				if (data.msg.ico == "success") {
					vimg_book.attr("data-idframe", data.marco.id_frame);
					$(".frame", vimg_book).html('<img src="'+base_url+data.marco.url_frame+'">');
					$(".frame img", vimg_book).load(function(){
						redimImgFrame(this, vimg_book);
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
	}

	objr.init            = init;
	objr.setPhotoToBook  = setPhotoToBook;
	objr.setFrameToPhoto = setFrameToPhoto;
	objr.setDropEvent    = setDropEvent;
	objr.setDragEvent    = setDragEvent;
	return objr;
})(jQuery);

