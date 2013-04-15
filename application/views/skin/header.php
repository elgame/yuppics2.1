<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="es" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $seo['titulo'];?></title>
	<meta name="description" content="<?php echo $seo['titulo'];?>">
	<meta name="viewport" content="width=device-width">

<?php
	if(isset($this->carabiner)){
		$this->carabiner->display('css');
		$this->carabiner->display('base_panel');
		$this->carabiner->display('js');
	}
?>
	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('application/css/skin/ie.css'); ?>" />
	<![endif]-->

<script type="text/javascript" charset="UTF-8">
	var base_url = "<?php echo base_url();?>";
</script>
</head>
<body>

	<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner navinner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<!-- <a class="brand" href="<?php echo base_url(); ?>">
					<img alt="logo" src="<?php echo base_url('application/images/logo.png'); ?>" width="67" height="67">
					<span>Yuppics</span>
				</a> -->

				<div class="under_group head_tw_fb pull-left">
					<div class="btn-group">
					  <a class="btn"><i class="icon-tw"></i></a>
					  <a class="btn"><i class="icon-fb"></i></a>
					</div>
				</div>

		<?php if ($this->session->userdata('nombre')!='') { ?>
				<!-- user dropdown starts -->
				<div class="under_group pull-right mnu-info-usr">
					<div class="btn-group" >
						<a class="btn" href="<?php echo base_url('customer/perfil/');?>">
							<i class="icon-user visible-phone"></i> <span class="hidden-phone"> <?php echo $this->session->userdata('nombre'); ?></span>
						</a>
						<button class="btn dropdown-toggle" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="<?php echo base_url('customer/perfil/');?>">Perfil</a></li>
							<!-- <li class="divider"></li> -->
							<li><a href="<?php echo base_url('customer/logout'); ?>">Cerrar sesión</a></li>
						</ul>
					</div>
				</div>
				<!-- user dropdown ends -->
		<?php } ?>

		<?php if (isset($carrito_compra)) {
						$carrito_compra = is_array($carrito_compra)? $carrito_compra: array();
			?>
				<!-- carrito dropdown starts -->
				<div class="under_group pull-right">
					<div id="menu_carrito_compra" class="btn-group" >
						<a class="btn dropdown-toggle" href="#">
							<span id="carr_badge_success" class="badge badge-success"><?php echo count($carrito_compra); ?></span>
							<i class="icon-shopping-cart visible-phone"></i> <span class="hidden-phone"> Carrito de compras</span>
						</a>
						<ul class="dropdown-menu carrito_compra">
							<li class="secc-titulo"><span>Resumen de <strong>Compra</strong></span></li>
							<!-- <li class="divider"></li> -->
					<?php
					$car_total = 0; $car_items = '';
					foreach ($carrito_compra as $key => $value) {
							$car_total += $value->price;
						?>
							<li class="car_item">
								<div class="row-fluid" style="padding: 0px 5px;">
									<div class="span3">
                    <a href="<?php echo base_url('yuppics/set_yuppic?yuppic='.$value->id_yuppic); ?>" style="padding: 8% 0; width: 35px; height: 37px;"><img src="<?php echo base_url($value->url_img); ?>" width="35" height="37"></a>
									</div>
									<div class="span9">
                    <div class="span12">
                      <a href="<?php echo base_url('yuppics/set_yuppic?yuppic='.$value->id_yuppic); ?>" class="yp-title"><?php echo $value->title; ?></a>
                    </div>
                    <div class="span12">
                      <input type="number" class="span8 car_quantity" value="<?php echo $value->quantity; ?>"
                             data-price="<?php echo $value->price; ?>" data-yuppic="<?php echo $value->id_yuppic; ?>" min="0">
                      <div class="span3 car_importe"><?php echo String::formatoNumero( ($value->price*$value->quantity) ); ?></div>
                    </div>

									</div>
								</div>
							</li>
					<?php } ?>
<!-- 							<li class="divider"></li> -->
							<li class="secc-total">
								<div class="row-fluid">
									<div class="span7 carr_total"><strong>Total <span class="green-text" id="car_total_comp"><?php echo String::formatoNumero($car_total); ?> MXN</span></strong></div>
									<div class="span5"><button type="button" id="car_btn_comprar" class="btn btn-success">Comprar</button></div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!-- carrito dropdown ends -->
		<?php } ?>

			</div>
		</div>
	</div>
	<!-- topbar ends -->

	<div class="barra_menu_fix hidden-phone">
	</div>
    <!--[if lt IE 8]>
    <div class="alert alert-info center alert-browser">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <p>Usted está usando un navegador desactualizado. <a href="http://browsehappy.com/">Actualice su navegador</a> o <a href="http://www.google.com/chromeframe/?redirect=true">instale Google Chrome Frame</a> para experimentar mejor este sitio.</p>
    </div>
  <![endif]-->
	<div class="container-fluid nopadding_lf">
		<div class="row-fluid">