		<div id="content" class="span7">
			<!-- content starts -->

			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body">
						<h3 class="myriad">Bienvenido/a <?php echo $this->session->userdata('nombre'); ?>, estás viendo tu Dashboard</h3>
						<div class="row-fluid" style="color:rgba(74,74,74, .45);">
							<div class="span6">Total de Yuppics en envío: 3</div>
							<div class="span6">Total de descuentos activos: 5</div>
						</div>
					</div>
					<div class="unit-footer">
						<p>Cambia tu dirección de envío o de facturación <a href="<?php echo base_url('customer/perfil/');?>" class="link_black">aquí</a></p>
					</div>
				</div>
			</div>


			<h2 class="myriad" style="color:#464f55;">Tus Yuppis</h2>

			<div class="row-fluid">
					<div class="span12">
						<div class="btn-group btn-sstyle">
							<button class="btn btn-large">Comprados</button>
							<button class="btn btn-large">Pendientes</button>
						</div>
					</div><!--/span-->

			</div><!--/row-->




					<!-- content ends -->
	</div><!--/#content.span8-->



<!-- Bloque de alertas -->
<?php if(isset($frm_errors)){
	if($frm_errors['msg'] != ''){
?>
<script type="text/javascript" charset="UTF-8">
	$(document).ready(function(){
		var valert = $("#<?php echo $frm_errors['objs']; ?>");

		valert.addClass("alert-<?php echo $frm_errors['ico']; ?>").show(300);
		$("span", valert).html("<?php echo $frm_errors['msg']; ?>");
	});
</script>
<?php }
}?>
<!-- Bloque de alertas -->