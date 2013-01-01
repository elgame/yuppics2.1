var msb = {
	confirm: function(msg, title, obj, callback){
		this.wrap_html(msg, title);

		$('#myModal').modal().on('hidden', function(){
			$(this).remove();
		});
		$('#myModal .btn-primary').on('click', function(){
			if($.isFunction(callback))
				callback.call(this, obj);
			else
				window.location = obj.href;
		});
		return false;
	},

	info: function(msg, title, obj, callback){
		this.wrap_html(msg, title);

		$('#myModal').modal().on('hidden', function(){
			$(this).remove();
		});
		$('#myModal .btn-primary').on('click', function(){
			if($.isFunction(callback))
				callback.call(this, obj);
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
	}

	wrap_html: function(msg, title, type){
		var html="", footer="";

		switch(type){
			case 'confirm': 
				html = '<a href="#" class="btn" data-dismiss="modal">No</a>'+
							 '<a href="#" class="btn btn-primary">Si</a>';
			break;
			case 'info': 
				html = '<a href="#" class="btn btn-primary" data-dismiss="modal">Ok</a>';
			break;
		}

		html = '<div class="modal hide fade" id="myModal">';
		if (title) {
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