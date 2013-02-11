var msb = {
	confirm: function(msg, title, obj, callback, callback_cancel){
		this.wrap_html(msg, title, "confirm");

		$('#myModal').modal().on('hidden', function(){
			$(this).remove();
		});
		$('#myModal .btn-success').on('click', function(){
			if($.isFunction(callback)){
				$('#myModal').modal("hide");
				callback.call(this, obj);
			}else
				window.location = obj.href;
		});
		if ($.isFunction(callback_cancel)){
			$('#myModal .cancel').on('click', function(){
				callback_cancel.call(this, obj);
			});
		};

		return false;
	},

	info: function(msg, title, obj, callback){
		this.wrap_html(msg, title, "info");

		$('#myModal').modal().on('hidden', function(){
			$(this).remove();
		});
		$('#myModal .btn-success').on('click', function(){
			if($.isFunction(callback)){
				$('#myModal').modal("hide");
				callback.call(this, obj);
			}
		});
		
		return false;

		$.msgbox(msg, {
		  type: "info"
		}, function(result) {
		  if (result) {
			  if($.isFunction(callback))
				  callback.call(this, obj);
			  /*else
				  window.location = obj.href;*/
		  }
		});
	},

	error: function(msg, obj, callback){
		$.msgbox(msg, {
			  type: "error"
			}, function(result) {
			  if (result) {
				  if($.isFunction(callback))
					  callback.call(this, obj);
				  /*else
					  window.location = obj.href;*/
			  }
			});
	},

	wrap_html: function(msg, title, type){
		var html="", footer="";

		switch(type){
			case 'confirm': 
				footer = '<a href="#" class="btn cancel" data-dismiss="modal">No</a>'+
							 '<a href="#" class="btn btn-success">Si</a>';
			break;
			case 'info': 
				footer = '<a href="#" class="btn btn-success" data-dismiss="modal">Ok</a>';
			break;
		}

		html = '<div class="modal hide fade" id="myModal">';
		if (title && title != '') {
			html += '	<div class="modal-header">'+
			'		<button type="button" class="close" data-dismiss="modal">×</button>'+
			'		<h3>'+title+'</h3>'+
			'	</div>';
		}

		html += '	<div class="modal-body">'+
			'		<p>'+msg+'</p>'+
			'	</div>'+
			'	<div class="modal-footer">'+
			footer+
			'	</div>'+
			'</div>';

		$("body").append(html);
	}
};