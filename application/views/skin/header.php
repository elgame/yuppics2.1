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
				
				<a class="brand" href="<?php echo base_url(); ?>"> 
					<img alt="logo" src="<?php echo base_url('application/images/logo.png'); ?>" width="67" height="67">
					<span>Yuppics</span>
				</a>
				
		<?php if ($this->session->userdata('nombre')!='') { ?>
				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i><span class="hidden-phone"> <?php echo $this->session->userdata('nombre'); ?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo base_url('customer/perfil/');?>">Perfil</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo base_url('customer/logout'); ?>">Cerrar sesión</a></li>
					</ul>
				</div>
				<!-- user dropdown ends -->
		<?php } ?>

		<?php if (isset($carrito_compra)) {  ?>
				<!-- carrito dropdown starts -->
				<div id="menu_carrito_compra" class="btn-group pull-right" >
					<a class="btn dropdown-toggle" href="#">
						<span class="badge badge-warning"><?php echo count($carrito_compra); ?></span>
						<span class="hidden-phone"> Carrito de compras</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu carrito_compra">
						<li><strong style="margin-left: 5%;">Resumen de compra</strong></li>
						<li class="divider"></li>
				<?php 
				$car_total = 0; $car_items = '';
				foreach ($carrito_compra as $key => $value) { 
						$car_total += $value->price;
					?>
						<li class="car_item">
							<div class="row-fluid" style="padding: 0px 5px;">
								<div class="span6">
									<a href="<?php echo base_url('yuppics/set_yuppic?yuppic='.$value->id_yuppic); ?>"><?php echo $value->title; ?></a>
								</div>
								<div class="span3">
									<input type="number" class="span8 car_quantity" value="<?php echo $value->quantity; ?>" 
										data-price="<?php echo $value->price; ?>" data-yuppic="<?php echo $value->id_yuppic; ?>" min="0">
								</div>
								<div class="span3 car_importe"><?php echo String::formatoNumero( ($value->price*$value->quantity) ); ?></div>
							</div>
						</li>
				<?php } ?>
						<li style="background-color: #eee;">
							<div class="row-fluid" style="margin: 0px 5px;">
								<div class="span8"><strong>Total <span id="car_total_comp"><?php echo String::formatoNumero($car_total); ?></span></strong></div>
								<div class="span4"><button type="button" id="car_btn_comprar" class="btn">Comprar</button></div>
							</div>
						</li>
					</ul>
				</div>
				<!-- carrito dropdown ends -->
		<?php } ?>
				
			</div>
		</div>
	</div>
	<!-- topbar ends -->

	<div class="container-fluid">
		<div class="row-fluid">
			<!--[if lt IE 7]>
        <div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<p>Usted está usando un navegador desactualizado. <a href="http://browsehappy.com/">Actualice su navegador</a> o <a href="http://www.google.com/chromeframe/?redirect=true">instale Google Chrome Frame</a> para experimentar mejor este sitio.</p>
				</div>
      <![endif]-->