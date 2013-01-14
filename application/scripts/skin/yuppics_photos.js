//  Variables Globales
//  gPhotos: Obj tipo cache que contendra el album actual cargado con sus
//  imagenes cargadas.
//  gAlbSel: Contiene el album actualmente seleccionado.
//  ajaxReq: Obj Ajax
//  max_fotos: maximo de fotos permitidas
var gPhotos = {}, gAlbSel, ajaxReq, gMf;
$(function() {
  //  Habilita el Plugin jScrollPane
	$('.scroll-pane').jScrollPane();
  gMf = parseInt($('#mf').val());

  // Asigna evento "Click" a la imagenes que se cargan al selecciona un album de FB.
  $(document).on('click', '#photos-list > li', function(){
    var res = buildClonePhoto(this);
    if (res === 1)
      noty({"text": 'La fotografía/imagen ya está seleccionada.', "layout":"center", "type": 'warning'}); // topRight
    else if(res === 2)
      noty({"text": 'Has llegado al maximo de fotos permitidas.', "layout":"topRight", "type": 'error'}); // topRight
    reinitializeScrollPane();
  });

  // Asigna evento "Click" a los botones eliminar en la seccion "Fotos Seleccionadas".
	$(document).on('click', 'button#delete', function () {
		deleteClonePhoto(this);
		reinitializeScrollPane();
	});

  // Asigna evento "Click" al boton "Seleccionar todo" y carga todas las imagenes
  // que se estan visualizando.
	$('#selectall').on('click', function(){
		loader.create();
    var show_msg = false;
		$('#photos-list').find('li').each(function(i, e){
			if(buildClonePhoto(e) === 2) {
        show_msg = true;
        return false;
      }
		});
    if (show_msg) {
      noty({"text": 'Has llegado al maximo de fotos permitidas.', "layout":"topRight", "type": 'error'}); // topRight
    }
		reinitializeScrollPane();
		loader.close();
	});

  // Asigna evento "Click" al boton "Remover todo" y limpia las imagenes
  // de la seccion "Fotos Seleccionadas"
	$('#removeall').on('click', function(){
		loader.create();
		$('button#delete').each(function(i, e){
			deleteClonePhoto(e);
		});
		reinitializeScrollPane();
		$('.jspPane').css('left', '0px')
		loader.close();
	});

  // Asigna evento "Click" al listado de albums.
	$('#albums').find('li').on('click', function(){
		$('#albums').find('li.active').removeClass('active');
		$(this).addClass('active');
		$('#barratop_album').html($(this).find('a').html());
	});

  // Asigna evento "Click" a los link  para guardar las fotos.
	$('#save_photos').on('click', function(){
		save_photos();
  });

  $('a#modal').on('click', function(event) {
    var length = $('#form').find('input[name="photos[]"][value!="false"]').length,
        iFalse = $('#form').find('input[name="photos[]"][value="false"]').length;
    if ( length > 0) {
      $('#save_photos').removeAttr('disabled').removeClass('disabled');
      $('#modal_upload').find('#txt-msg').html('Da click en el boton "Upload" si las fotos seleccionadas son las correctas.');
    }
    else if (iFalse > 0)
      window.location = base_url+"yuppics/book/";
    else {
      $('#save_photos').attr('disabled', 'disabled').addClass('disabled');
      $('#modal_upload').find('#txt-msg').html('Selecciona al menos una Foto para continuar.');
    }
  });

	// animacion del progress bar
	var progressbar_yuppic = $("#progressbar_yuppic .bar")
	progressbar_yuppic.animate({
		width: progressbar_yuppic.attr('data-progress')+"%"
	}, 400);

  // Asigna evento "Click" al Boton "Next".
  $('#btn-next').on('click', function(event) {
    $('#btn-next').attr({'disabled': 'disabled'}).addClass('disabled');
    $('#btn-prev').attr({'disabled': 'disabled'}).addClass('disabled');
    buildPhotos(base_url+'yuppics/get_next_photos', {'url': $(this).attr('data-next')});
  });

  // Asigna evento "Click" al Boton "Prev".
  $('#btn-prev').on('click', function(event) {
    $('#btn-next').attr({'disabled': 'disabled'}).addClass('disabled');
    $('#btn-prev').attr({'disabled': 'disabled'}).addClass('disabled');
    buildPhotos(base_url+'yuppics/get_prev_photos', {'url': $(this).attr('data-prev')});
  });

});

/**
 * Cancela o Aborta la ultima peticion ajax.
 */
function abortAjaxRequest() {
  if (ajaxReq) {
    ajaxReq.abort();
    loader.close();
  }
}

/**
 * Procesa el album seleccionado.
 * @param  str ida [description]
 */
function album(ida) {
  abortAjaxRequest();
	$('.photos-list').find('ul').html('');
	// loader.create('.photos-list ul');
	var access_token = $('#at').val(),
		  method = 'get_user_album_photos',
      params = {},
      url = '';

	if (ida === 'all')
		method = 'get_user_photos';

  // if (!gPhotos.hasOwnProperty(ida))
  gPhotos[ida] = {}; // Inserta el album selecciona al obj global
  gAlbSel = ida; // Asigna que el album es el ultimo seleccionado

  $('#btn-next').attr({'data-next': '', 'disabled': 'disabled'}).addClass('disabled');
  $('#btn-prev').attr({'data-prev': '', 'disabled': 'disabled'}).addClass('disabled');

	params.access_token = access_token;
	params.ida = ida;
  url = base_url+"yuppics/"+method;
  buildPhotos(url, params);
}

/**
 * Esta funcion crea una "copia" de la imagen que se desee seleccionar
 * del conjunto de imagenes del album escogido.
 *
 * En caso de que la imagen ya exista o se encuentre seleccionada ya no se podra
 * seleccionar nuevamente.
 * @param  Obj obj [Imagen a la que se le da click]
 * @return Boolean [True: Ya existe. False:No existe]
 */
function buildClonePhoto(obj) {
	var obj = $(obj),
      photo_id  = obj.attr('id'),
      photo_src = obj.find('img').attr('src'),
      photo_ori = obj.find('img').attr('data-ori'),
		// photo_title = obj.find('#thumbnail-title').html(),
  		exist = false,
  		objtotalch = $('#total-choose');

  if (parseInt(objtotalch.html()) < gMf) {
    exist = validateExistPhoto(photo_id);
    if (!exist) {
      var html_input = '<input type="hidden" name="photos[]" value="'+photo_ori+'" id="'+photo_id+'" class="src-'+photo_id+' ori">',
          html_input_thumb = '<input type="hidden" name="thumbs[]" value="'+photo_src+'" id="inpthumb-'+photo_id+'" class="src-'+photo_id+'">',
          html_clone_photo = '<li class="span2 relative">' +
                                '<div class="thumbnail">' +
                                  '<img alt="" src="'+photo_src+'">'+
                                  // '<div class="caption">' +
                                  //  '<span class="center"><strong>'+photo_title+'</strong></span>' +
                                  // '</div>' +
                                '</div>' +
                                '<button type="button" class="close delete" data-dismiss="alert" data-id="'+photo_id+'" data-exist="false" title="Eliminar" id="delete">×</button>' +
                              '</li>',
          html_clone_photo_modal = '<li class="span2 relative" data-id="mdl-'+photo_id+'">' +
                                      '<div class="loader"></div>' +
                                      '<div class="thumbnail">' +
                                        '<img alt="" src="'+photo_src+'">'+
                                        // '<div class="caption">' +
                                        //  '<span class="center"><strong>'+photo_title+'</strong></span>' +
                                        // '</div>' +
                                      '</div>' +
                                    '</li>',
          obj_content_selected_photos = $('#content-selected-photos'),
          px = parseInt(obj_content_selected_photos.css('width')) + 165;

      obj_content_selected_photos.css('width', px);
      obj_content_selected_photos.find('.thumbnails').append(html_clone_photo);
      $('#modal_upload').find('.thumbnails').append(html_clone_photo_modal);
      obj.addClass('choose-photo'); //.append('<div class="choosed" id="chossed"></div>')

      $('#form').append(html_input+html_input_thumb);
      objtotalch.html(parseInt(objtotalch.html()) + 1);
    }
    else exist = 1;
    return exist;
  }
  else
    return 2
}

/**
 * Eliminar la Imagen a la que se le de click al boton eliminar
 * @param  Obj obj [Elemento al que se le da click]
 */
function deleteClonePhoto(obj) {
	var obj = $(obj),
		  objtotalch = $('#total-choose');

  		if (obj.attr('data-exist') == 'false') {
  			obj.parent().remove();
        $('#modal_upload').find('li[data-id="mdl-'+obj.attr('data-id')+'"]').remove();
  			$('input.src-'+obj.attr('data-id')).remove();
  			$('li#'+obj.attr('data-id')).removeClass('choose-photo').find('.choosed').remove();
  		}
  		else {
  			loader.create()
  			obj.parent().remove();
        $('input.src-'+obj.attr('data-id')).remove();
  			$.post(base_url + 'yuppics/photo_delete', {'idp': $(obj).attr('data-id')} , function(data){
  				loader.close();
  			});
  		}

		var obj_content_selected_photos = $('#content-selected-photos'),
		    px = parseInt(obj_content_selected_photos.css('width')) - 165;

		obj_content_selected_photos.css('width', px);
		var totalch = parseInt(objtotalch.html()) - 1;
		objtotalch.html(totalch);

		if (totalch < 7)
			$('.jspPane').css('left', '0px');
}

/**
 * Validador para verificar si una imagen ya fue seleccionada.
 * @param  Str id [id de la imagen]
 * @return Boolean [True: Ya existe, False: no existe]
 */
function validateExistPhoto (id) {
  var exist = false;
  $('#form').find('input').each(function(i, e) {
    if ($(this).attr('id') === id) {
      exist = true;
      return false;
    }
  });
  return exist;
}

/**
 * Reinicializa el jScrollPane.
 */
function reinitializeScrollPane() {
	var pane = $('.horizontal-only'),
		api = pane.data('jsp');
		api.reinitialise();
}

/**
 * Guarda la o las imagenes seleccionadas. Este es el paso final de esta
 * seccion.
 */
var gPhotosSave = {};
function save_photos() {
	loader.create();
	gPhotosSave = {'photos[]': [], 'thumbs[]': [], 'id[]': [], 'index[]': []};

	$('#form').find('input[type="hidden"].ori').each(function(i, e){
		var id = $(e).attr('id');
		gPhotosSave['photos[]'].push($(e).val());
		gPhotosSave['thumbs[]'].push($('#inpthumb-'+id).val());
    gPhotosSave['id[]'].push(id);
    gPhotosSave['index[]'].push(i);
	});
  buildParamsSave(0, 3);
}

var gInd = 3;
function buildParamsSave (start, max) {
  var i = start,
      params = {},
      length = gPhotosSave['photos[]'].length - 1;

  if (length < 3) {
    max = length;
    gInd = max;
  }

  for (i; i <= max; i += 1) {
    params['photo'] = gPhotosSave['photos[]'][i];
    params['thumb'] = gPhotosSave['thumbs[]'][i];
    params['id'] = gPhotosSave['id[]'][i];
    params['index'] = gPhotosSave['index[]'][i];
    ajaxSave(params, max);
    loader.create('');

    $('li[data-id="mdl-'+params.id+'"] .thumbnail').animate({opacity: '0.3'});
    loader.create('li[data-id="mdl-'+params.id+'"] .loader');
    params = {};
  }
}

function ajaxSave (params, max) {
  $.post(base_url+"yuppics/photos_upload", params, function(data) {
      $('li[data-id="mdl-'+params.id+'"]').hide('slow', function(){$(this).remove()});
      gInd = gInd + 1;
      if (gInd <= (gPhotosSave['photos[]'].length - 1)) {
        buildParamsSave(gInd, gInd);
      } else {
        if (parseInt(data.index) === parseInt(gPhotosSave['photos[]'].length - 1))
          window.location = base_url+"yuppics/book/";
      }
  }, "json");
}

/**
 * Esta funciona realiza varios procesos para la carga de imagenes.
 *
 * Verifica en el objeto cache "gPhotos" si la url a la que se hara la peticion
 * de las imagenes ya existe en dicho objeto.
 *
 *  - Si no existe entonces realiza la peticion por AJAX y carga las imagenes
 *     devueltas en la peticion.
 *  - Si la url ya existe en el objeto cache entonces ya no realiza la peticion
 *     por ajax, las carga del objeto cache.
 *
 * @param  {[Str]} url    [Url a la que se realizara la peticion AJAX]
 * @param  {[Str]} params [Parametros a enviar en la peticion AJAX]
 */
function buildPhotos(url, params) {
  var html_photos = '',
      i,
      do_getJSON = true;

  $('.photos-list').find('ul').html('');
  loader.create('.photos-list ul');
  if (params.hasOwnProperty('url')) {
    if (gPhotos[gAlbSel].hasOwnProperty(params.url)) {
      do_getJSON = false;
      var class_choose_photo = '', child_choose_photo = '';
      for (i in gPhotos[gAlbSel][params.url].data) {
        class_choose_photo = '';
        // child_choose_photo = '';
        if (validateExistPhoto(gPhotos[gAlbSel][params.url].data[i].id)) {
          class_choose_photo = 'choose-photo';
          child_choose_photo = '<div class="choosed" id="chossed"></div>';
        }
        html_photos += '<li class="span2 relative '+class_choose_photo+'" id="'+gPhotos[gAlbSel][params.url].data[i].id+'">' +
                       '<div class="thumbnail">' +
                         '<img alt="" src="'+gPhotos[gAlbSel][params.url].data[i].picture+'" data-ori="'+gPhotos[gAlbSel][params.url].data[i].images[0].source+'">' +
                          // child_choose_photo +
                      '</div>' +

                    '</li>';
      }
      loader.close();
      $('#photos-list').html(html_photos);

      if (gPhotos[gAlbSel][params.url].hasOwnProperty('paging')) {
        if (gPhotos[gAlbSel][params.url].paging.hasOwnProperty('next'))
          $('#btn-next').attr('data-next', gPhotos[gAlbSel][params.url].paging.next).removeAttr('disabled').removeClass('disabled');
        else
          $('#btn-next').attr({'data-next': '', 'disabled': 'disabled'}).addClass('disabled');

        if (gPhotos[gAlbSel][params.url].paging.hasOwnProperty('previous'))
          $('#btn-prev').attr('data-prev', gPhotos[gAlbSel][params.url].paging.previous).removeAttr('disabled').removeClass('disabled');
        else
          $('#btn-prev').attr({'data-prev': '', 'disabled': 'disabled'}).addClass('disabled');
      }
    }
  }

  // Si la url a la que se hara la peticion de facebook no existe en el objeto
  // cache entonces entra en esta condicion.
  if (do_getJSON) {
    ajaxReq = $.getJSON(url, params, function(data) {
      var class_choose_photo = '', child_choose_photo = '';
      for (i in data.data) {
        class_choose_photo = '';
        child_choose_photo = '';
        if (validateExistPhoto(data.data[i].id)) {
          class_choose_photo = 'choose-photo';
          child_choose_photo = '<div class="choosed" id="chossed"></div>';
        }

        html_photos += '<li class="span2 relative '+class_choose_photo+'" id="'+data.data[i].id+'">' +
                         '<div class="thumbnail">' +
                           '<img alt="" src="'+data.data[i].picture+'" data-ori="'+data.data[i].images[0].source+'">' +
                           // child_choose_photo +
                         '</div>' +
                      '</li>';
      }
      if (params.hasOwnProperty('url'))
        gPhotos[gAlbSel][params.url] = data;

      loader.close();
      $('#photos-list').html(html_photos);

      if (data.hasOwnProperty('paging')) {
        if (data.paging.hasOwnProperty('next'))
          $('#btn-next').attr('data-next', data.paging.next).removeAttr('disabled').removeClass('disabled');
        else
          $('#btn-next').attr({'data-next': '', 'disabled': 'disabled'}).addClass('disabled');

        if (data.paging.hasOwnProperty('previous'))
          $('#btn-prev').attr('data-prev', data.paging.previous).removeAttr('disabled').removeClass('disabled');
        else
          $('#btn-prev').attr({'data-prev': '', 'disabled': 'disabled'}).addClass('disabled');
      }
    }); //, "json"  END getSON
  }
}