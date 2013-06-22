$(function(){
  $('.scroll-pane').jScrollPane({
		autoReinitialise: true
	});

  paginationYuppics(4);

	// var entro_rezise = false;
	// $(window).resize(function() {
	//   if($(window).width() > 1200){ //1746
	//   	obj1.recalcula({ step: 6 });
	//   	entro_rezise = true;
	//   }else if(entro_rezise){
	//   	obj1.recalcula({ step: 4 });
	//   	entro_rezise = false;
	//   }
	// });
});

var obj1;

function paginationYuppics(items, refresh){
	//Paginacion de los yuppics
	obj1 = $('ul#yppendientesp').easyPaginate({
		step: items
	}, refresh);

	$('ul#ypcompradosp').easyPaginate({
		step: items
	}, refresh);
}