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
		$this->carabiner->display('base_panel_simpliq');
		$this->carabiner->display('js');
	}
?>

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

  <!--[if IE 9]>
    <link id="ie9style" href="<?php echo base_url('application/css/simpliq/ie9.css') ?>" rel="stylesheet">
  <![endif]-->

  <!-- start: Favicon and Touch Icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('application/images/simpliq/ico/apple-touch-icon-144-precomposed.png') ?>">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('application/images/simpliq/ico/apple-touch-icon-114-precomposed.png') ?>">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('application/images/simpliq/ico/apple-touch-icon-72-precomposed.png') ?>">
  <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('application/images/simpliq/ico/apple-touch-icon-57-precomposed.png') ?>">
  <link rel="shortcut icon" href="<?php echo base_url('application/images/simpliq/ico/favicon.png') ?>">
  <!-- end: Favicon and Touch Icons -->

<script type="text/javascript" charset="UTF-8">
	var base_url = "<?php echo base_url();?>";
</script>
</head>
<body>

	<?php if ($this->session->userdata('acceso_panel')==='admin') {
    echo $this->load->view('panel/header-menu', true);
  } ?>

	<div class="container-fluid-full">
    <div class="row-fluid">