var gPhotos = {}, gAlbSel, ajaxReq;
$(function() {
	$('.scroll-pane').jScrollPane();
	// album('all');

	$(document).on('click', 'button#delete', function () {
		deleteClonePhoto(this);
		reinitializeScrollPane();
	});

	$(document).on('click', '#photos-list > li', function(){
		if (buildClonePhoto(this))
			alert('La fotografía/imagen ya está seleccionada.');

		reinitializeScrollPane();
	});

	$('#selectall').on('click', function(){
		loader.create();
		$('#photos-list').find('li').each(function(i, e){
			buildClonePhoto(e);
		});
		reinitializeScrollPane();
		loader.close();
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

	$('#albums').find('li').on('click', function(){
		$('#albums').find('li.active').removeClass('active');
		$(this).addClass('active');
		$('#barratop_album').html($(this).find('a').html());
	});

	$('#save_photos, #save_photos2').on('click', function(){
		save_photos();
	});

	// animacion del progress bar
	var progressbar_yuppic = $("#progressbar_yuppic .bar")
	progressbar_yuppic.animate({
		width: progressbar_yuppic.attr('data-progress')+"%"
	}, 400);

  $('#btn-next').on('click', function(event) {
    $('#btn-next').attr({'disabled': 'disabled'}).addClass('disabled');
    $('#btn-prev').attr({'disabled': 'disabled'}).addClass('disabled');
    buildPhotos(base_url+'yuppics/get_next_photos', {'url': $(this).attr('data-next')});
  });

  $('#btn-prev').on('click', function(event) {
    $('#btn-next').attr({'disabled': 'disabled'}).addClass('disabled');
    $('#btn-prev').attr({'disabled': 'disabled'}).addClass('disabled');
    buildPhotos(base_url+'yuppics/get_prev_photos', {'url': $(this).attr('data-prev')});
  });

});

function abortAjaxRequest() {
  if (ajaxReq) {
    ajaxReq.abort();
    loader.close();
  }
}

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

  if (!gPhotos.hasOwnProperty(ida))
    gPhotos[ida] = {}; // Inserta el album selecciona al obj global
  gAlbSel = ida; // Asigna que el album es el ultimo seleccionado

  $('#btn-next').attr({'data-next': '', 'disabled': 'disabled'}).addClass('disabled');
  $('#btn-prev').attr({'data-prev': '', 'disabled': 'disabled'}).addClass('disabled');

	params.access_token = access_token;
	params.ida = ida;
  url = base_url+"yuppics/"+method;
  buildPhotos(url, params);
}

function buildClonePhoto(obj) {
	var obj = $(obj),
		photo_id = obj.attr('id'),
		photo_src = obj.find('img').attr('src'),
		photo_ori = obj.find('img').attr('data-ori'),
		// photo_title = obj.find('#thumbnail-title').html(),
		exist = false,
		objtotalch = $('#total-choose');

  exist = validateExistPhoto(photo_id);
	if (!exist) {
    var html_input = '<input type="hidden" name="photos[]" value="'+photo_ori+'" id="'+photo_id+'" class="src-'+photo_id+' ori">',
        html_input_thumb = '<input type="hidden" name="thumbs[]" value="'+photo_src+'" id="inpthumb-'+photo_id+'" class="src-'+photo_id+'">',
		    html_clone_photo = '<li class="span2 relative">' +
									'<div class="thumbnail">' +
										'<img alt="" src="'+photo_src+'">'+
										// '<div class="caption">' +
										// 	'<span class="center"><strong>'+photo_title+'</strong></span>' +
										// '</div>' +
									'</div>' +
									'<button type="button" class="close delete" data-dismiss="alert" data-id="'+photo_id+'" data-exist="false" title="Eliminar" id="delete">×</button>' +
								'</li>',
    		obj_content_selected_photos = $('#content-selected-photos'),
    		px = parseInt(obj_content_selected_photos.css('width')) + 165;

		obj_content_selected_photos.css('width', px);
		obj_content_selected_photos.find('.thumbnails').append(html_clone_photo);
		obj.addClass('choose-photo'); //.append('<div class="choosed" id="chossed"></div>')

		$('#form').append(html_input+html_input_thumb);
		objtotalch.html(parseInt(objtotalch.html()) + 1);
	}
	return exist;
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

function reinitializeScrollPane() {
	var pane = $('.horizontal-only'),
		api = pane.data('jsp');
		api.reinitialise();
}

function pagination2 () {
	$("div.pagination").jPages({
		containerID: "photos-list",
		perPage      : 12,
		startPage    : 1,
		startRange   : 1,
		midRange     : 5,
		endRange     : 1
	});
}

function save_photos() {
	loader.create();
	var msg_modal = $("#messajes_alerts"),
	params = {'photos[]': [], 'thumbs[]': []};

	$('#form').find('input[type="hidden"].ori').each(function(i, e){
		var id = $(e).attr('id');
		params['photos[]'].push($(e).val());
		params['thumbs[]'].push($('#inpthumb-'+id).val());
	});

	$.post(base_url+"yuppics/photos_save", params, function(data){
		if (data.frm_errors.ico === 'success') {
			window.location = base_url+"yuppics/book/";
		}else{
			$(".modal-body", msg_modal).html(data.frm_errors.msg);
			msg_modal.modal('show');
		}
		loader.close();
	}, "json");
}


function buildPhotos(url, params) {
  var html_photos = '',
      i,
      do_getJSON = true;

  // $('.photos-list').find('ul').html('');
  loader.create('.pagination');
  if (params.hasOwnProperty('url')) {
    if (gPhotos[gAlbSel].hasOwnProperty(params.url)) {
      do_getJSON = false;
      console.log(gPhotos);
      var class_choose_photo = '', child_choose_photo = '';
      for (i in gPhotos[gAlbSel][params.url].data) {
        class_choose_photo = '';
        child_choose_photo = '';
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

  if (do_getJSON) {
    ajaxReq = $.getJSON(url, params, function(data) {
      console.log(gPhotos);
      // console.log(data);
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