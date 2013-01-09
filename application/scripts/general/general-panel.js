$(function(){
	panel.init();

	carro_compras.init();
});

var carro_compras = (function($){
	var objr = {};

	function init(){
		var menu_carrito_compra = $("#menu_carrito_compra");
		$("#menu_carrito_compra .btn.dropdown-toggle").on("click", function(){
			if (menu_carrito_compra.is('.open')) {
				menu_carrito_compra.removeClass("open");
			}else
				menu_carrito_compra.addClass("open");
		});

		$(".car_quantity").on('change', function(){
			calculaTotal();
		});

		$("#car_btn_comprar").on("click", enviar_carro);
	}

	function enviar_carro(){
		var params = {
			yupics: [],
			quantity: []
		};
		$(".carrito_compra .car_item").each(function(){
			var vthis = $(this), item = $(".car_quantity", vthis),
			importe = ( parseInt(item.val())*parseFloat(item.attr("data-price")) );
			
			params.yupics.push(item.attr("data-yuppic"));
			params.quantity.push(item.val());
		});

		if (params.yupics.length > 0 && params.yupics.length == params.quantity.length) {
			loader.create();
			$.post(base_url+"yuppics/shop_car", params, 
				function(data){
					if (data.msg.ico == "success") {
						window.location = base_url+"buy/order?y="+data.items;
					}else
						noty({"text": data.msg.msg, "layout":"topRight", "type": data.msg.ico});
			}, "json").complete(function(){
				loader.close();
			});
		}else
			noty({"text": "El carro de compras esta vacio", "layout":"topRight", "type": "error"});
	}

	function calculaTotal(){
		var total = 0;
		$(".carrito_compra .car_item").each(function(){
			var vthis = $(this), item = $(".car_quantity", vthis),
			importe = ( parseInt(item.val())*parseFloat(item.attr("data-price")) );
			total += importe;

			$(".car_importe", vthis).text( util.darFormatoNum(importe) );
		});

		$("#car_total_comp").text( util.darFormatoNum(total) );
	}

	objr.init = init;
	return objr;
})(jQuery);


var panel = (function($){
	var objr = {};

	function activeMenu(){
		//highlight current / active link
		$('ul.main-menu li a').each(function(){
			//var url = String(window.location).split("?");
			var url = String(window.location).replace(base_url, ''), url1 = url.split('/'), url2 = url.split("?"),
			link = $($(this))[0].href.replace(base_url, ''), link1 = link.split('/');
			if(link==url || (link1[0]==url1[0] && link1[1]==url1[1]) || link==url2[0])
				$(this).parents('li').addClass('active');
		});
		$('ul.main-menu > li.active > a').click();
	};

	function animeMenu(){
		//animating menus on hover
		$('ul.main-menu li:not(.nav-header,.submenu)').hover(function(){
			$(this).animate({'margin-left':'+=5'},300);
		},
		function(){
			$(this).animate({'margin-left':'-=5'},300);
		});
	};

	function boxBtns(){
		$('.btn-close').click(function(e){
			e.preventDefault();
			$(this).parent().parent().parent().fadeOut();
		});
		$('.btn-minimize').click(function(e){
			e.preventDefault();
			var $target = $(this).parent().parent().next('.box-content');
			if($target.is(':visible')) $('i',$(this)).removeClass('icon-chevron-up').addClass('icon-chevron-down');
			else 					   $('i',$(this)).removeClass('icon-chevron-down').addClass('icon-chevron-up');
			$target.slideToggle();
		});
	};

	objr.menu = function(id){
		var obj = $("#subm"+id);
		if (obj.is(".hide"))
			obj.attr('class', 'show');
		else
			obj.attr('class', 'hide');
	};

	objr.init = function(){
		activeMenu();
		animeMenu();
		boxBtns();
	};

	return objr;
})(jQuery);




/**
 * Obj para crear un loader cuando se use Ajax
 */
var loader = {
	create: function(){
		$("body").append('<div id="ajax-loader" class="corner-bottom8">Cargando...</div>');
	},
	close: function(){
		$("#ajax-loader").remove();
	}
};
