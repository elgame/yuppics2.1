$(function(){
	$('.scroll-pane').jScrollPane();
	album('all');

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

	$('#save_photos').on('click', function(){
		save_photos();
	});
});

function album(ida) {
	$('.photos-list').find('ul').html('');
	loader.create('.photos-list ul');
	var html_photos = '',
		access_token = $('#at').val(),
		method = 'get_user_album_photos';

	if (ida === 'all')
		method = 'get_user_photos';

	params = {};
	params.access_token = access_token;
	params.ida = ida;

	// var title = (data[i].name) ? data[i].name : 'Sin titulo';
	// '<div class="caption center">' +
  	// 	'<span><strong id="thumbnail-title">'+title+'</strong></span>' +
  	// '</div>' +
	$.post(base_url+"yuppics/"+method, params, function(data){
		for (var i in data) {
			html_photos += '<li class="span2 relative" id="'+data[i].id+'">' +
							'<div class="thumbnail">' +
							  '<img alt="" src="'+data[i].picture+'" data-ori="'+data[i].images[0].source+'">' +
							'</div>' +
						 '</li>';
		}
		loader.close();
		$('#photos-list').html(html_photos);
		pagination();
	}, "json");
}

function buildClonePhoto(obj) {
	var obj = $(obj),
		photo_id = obj.attr('id'),
		photo_src = obj.find('img').attr('src'),
		photo_ori = obj.find('img').attr('data-ori'),
		// photo_title = obj.find('#thumbnail-title').html(),
		exist = false,
		objtotalch = $('#total-choose');

	var html_input = '<input type="hidden" name="photos[]" value="'+photo_ori+'" id="'+photo_id+'" class="src-'+photo_id+' ori">',
		html_input_thumb = '<input type="hidden" name="thumbs[]" value="'+photo_src+'" id="inpthumb-'+photo_id+'" class="src-'+photo_id+'">';
	
	$('#form').find('input').each(function(i, e) {
		if ($(this).attr('id') === photo_id) {
			exist = true;
			return false;
		}
	});

	if (!exist) {
		var html_clone_photo = '<li class="span2 relative">' +
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
		obj.addClass('choose-photo').append('<div class="choosed" id="chossed"></div>');

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

function reinitializeScrollPane() {
	var pane = $('.horizontal-only'),
		api = pane.data('jsp');
		api.reinitialise();
}

function pagination () {
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
			// window.location = base_url+"yuppics/create";
		}else{
			$(".modal-body", msg_modal).html(data.frm_errors.msg);
			msg_modal.modal('show');
		}
		loader.close();
	}, "json");
}