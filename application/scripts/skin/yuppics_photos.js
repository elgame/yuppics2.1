$(function(){

	$('.scroll-pane').jScrollPane();
	album('all');

	$(document).on('click', 'button#delete', function () {
  		var obj = $(this),
  			objtotalch = $('#total-choose');

  		$('li#'+obj.attr('data-id')).removeClass('choose-photo').find('.choosed').remove();
  		$('input.src-'+obj.attr('data-id')).remove();

  		var  obj_content_selected_photos = $('#content-selected-photos'),
			 px = parseInt(obj_content_selected_photos.css('width')) - 165;

		obj_content_selected_photos.css('width', px);

		var pane = $('.horizontal-only'),
			api = pane.data('jsp');
		api.reinitialise();

		objtotalch.html(parseInt(objtotalch.html()) - 1);
	});

	$(document).on('click', '#photos-list > li', function(){
		var obj = $(this),
			photo_id = obj.attr('id'),
			photo_src = obj.find('img').attr('src'),
			photo_ori = obj.find('img').attr('data-ori'),
			// photo_title = obj.find('#thumbnail-title').html(),
			exist = false,
			objtotalch = $('#total-choose');

		var html_input = '<input type="hidden" name="photos[]" value="'+photo_ori+'" id="'+photo_id+'" class="src-'+photo_id+' ori">'
		var html_input_thumb = '<input type="hidden" name="thumbs[]" value="'+photo_src+'" id="inpthumb-'+photo_id+'" class="src-'+photo_id+'">';
		
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
										'<button type="button" class="close delete" data-dismiss="alert" data-id="'+photo_id+'" title="Eliminar" id="delete">×</button>' +
									'</li>',
			obj_content_selected_photos = $('#content-selected-photos'),
			px = parseInt(obj_content_selected_photos.css('width')) + 165;

			obj_content_selected_photos.css('width', px)

			var pane = $('.horizontal-only'),
				api = pane.data('jsp');
			obj_content_selected_photos.find('.thumbnails').append(html_clone_photo);
			api.reinitialise();

			
			obj.addClass('choose-photo').append('<div class="choosed" id="chossed"></div>');

			$('#form').append(html_input+html_input_thumb);
			objtotalch.html(parseInt(objtotalch.html()) + 1);
		}
		// else
		// 	alert('ya existe la imagen seleccionada');

	});

	$('#selectall').on('click', function(){
		loader.create();
		$('#photos-list').find('li').each(function(i, e){
			$(e).click();
		});
		loader.close();
	});

	$('#removeall').on('click', function(){
		loader.create();
		$('button#delete').each(function(i, e){
			$(e).click();
		});
		loader.close();
		$('.jspPane').css('left', '0px')
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
	loader.create();
	var html_photos = '',
		access_token = $('#at').val(),
		method = 'get_user_album_photos';

	if (ida === 'all') {
		method = 'get_user_photos';
	}

	params = {};
	params.access_token = access_token;
	params.ida = ida;

	$.post(base_url+"yuppics/"+method, params, function(data){
		for (var i in data) {
			var title = (data[i].name) ? data[i].name : 'Sin titulo';

			html_photos += '<li class="span2 relative" id="'+data[i].id+'">' +
							'<div class="thumbnail">' +
							  '<img alt="" src="'+data[i].picture+'" data-ori="'+data[i].images[0].source+'">' +
							  // '<div class="caption center">' +
							  // 	'<span><strong id="thumbnail-title">'+title+'</strong></span>' +
							  // '</div>' +
							'</div>' +
						 '</li>';
		}
		loader.close();
		$('#photos-list').html(html_photos);
		pagination();
	}, "json");
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
		params['photos[]'].push($(e).val());

		var id = $(e).attr('id');
		params['thumbs[]'].push($('#inpthumb-'+id).val());
	});

	$.post(base_url+"yuppics/photos_save", params, function(data){
		if (data.frm_errors.ico === 'success') {
			window.location = base_url+"yuppics/book";
		}else{
			$(".modal-body", msg_modal).html(data.frm_errors.msg);
			msg_modal.modal('show');
		}
		loader.close();
	}, "json");
}