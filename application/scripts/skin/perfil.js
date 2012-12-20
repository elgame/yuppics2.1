var obj_update_address;
$(function(){
	form_ajax.init();

	$('#modal_updateaddress').on('show', function () {
	  $.getJSON(base_url+"address_book/info_address", 
	  	"address="+obj_update_address.attr("data-id"), 
	  	function(data){
	  		if (data.frm_errors.ico == 'success'){
	  			$("#frm_updateaddress").autofill( data.address );
	  		}
	  });
	});

	$(".update_address").on("click", function(){
		obj_update_address = $(this);
	})
});


function address_success(){
	setTimeout(function(){
		window.location.href = base_url+"customer/perfil";
	}, 1000);
}