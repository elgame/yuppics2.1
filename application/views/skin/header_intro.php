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
  <!--[if lt IE 8]>
    <div class="alert alert-info center alert-browser">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <p>Usted está usando un navegador desactualizado. <a href="http://browsehappy.com/">Actualice su navegador</a> o <a href="http://www.google.com/chromeframe/?redirect=true">instale Google Chrome Frame</a> para experimentar mejor este sitio.</p>
    </div>
  <![endif]-->

  <!-- Navbar
  ================================================== -->
  <div id="navbartop" class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
          <ul id="menunavbartop" class="nav pull-right">
            <li class="active">
              <a href="#intro">INICIO</a>
            </li>
            <li class="">
              <a href="#second">ABOUT</a>
            </li>
            <li class="">
              <a href="#third">¿CÓMO CREAR TU YUPPIC?</a>
            </li>
            <li class="">
              <a href="#modal_contact" role="button" class="" data-toggle="modal">CONTACTO</a>
            </li>
          </ul>
      </div>
    </div>
  </div>

	<div class="container-fluid nopadding">
		<div class="row-fluid">