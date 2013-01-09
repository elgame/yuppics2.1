		<div id="content" class="span8">
			<!-- content starts -->
			
			<div>
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo base_url(); ?>">Inicio</a> <span class="divider">/</span>
					</li>
					<li>
						Historial de compras
					</li>
				</ul>
			</div>

			<div class="row-fluid">
				<div class="hero-unit">
					<h3>Revisa tus reportes</h3>
					<p>A continuación se presenta un listado con el historial de cada una de tus compras dentro de la aplicacion, para consultar más detalles sobre cada compra, da clic sobre la celda para ver más detalles al respecto.</p>
				</div>
			</div>
					
			
			<h2>Historial de compras</h2>

			<div class="row-fluid">
					<div class="span12">
						<div class="accordion" id="hisotry-accordion">
				<?php 
				if(isset($orders)){
					if (is_array($orders)) {
						foreach ($orders as $key => $value) {
				?>
							<div class="accordion-group">
						    <div class="accordion-heading">
						      <a class="accordion-toggle" data-toggle="collapse" data-parent="#hisotry-accordion" href="#collapse<?php echo $value->id_order; ?>">
						        <span class="span2">Orden No <?php echo $value->id_order; ?></span>
						        <span class="span3"><?php echo String::humanDate(strtotime($value->created)); ?></span>
						        <span class="span2"><?php echo count($value->yuppics); ?></span>
						        <span class="span2"><?php echo String::formatoNumero($value->total_discount); ?>MXN</span>
						        <span class="span3"><?php echo String::formatoNumero($value->total); ?>MXN</span>
						      </a>
						      <div class="clearfix"></div>
						    </div>
						    <div id="collapse<?php echo $value->id_order; ?>" class="accordion-body collapse">
						      <div class="accordion-inner">
						  <?php  
						  foreach ($value->yuppics as $key => $yuppic) {
						  ?>
						      	<fieldset>
						      		<legend style="font-size: 15px;"><?php echo $yuppic->title; ?></legend>
						      		<div class="row-fluid">
								        <span class="span3 tacenter">Precio</span>
								        <span class="span3"><?php echo String::formatoNumero($yuppic->unitary_price); ?>MXN</span>
								        <span class="span3 tacenter">Fecha</span>
								        <span class="span3"><?php echo String::humanDate(strtotime($yuppic->created)); ?></span>
								      </div>
								      <div class="row-fluid">
								        <span class="span3 tacenter">Cantidad</span>
								        <span class="span3"><?php echo $yuppic->quantity; ?> yuppic<?php echo ($yuppic->quantity>1? 's': ''); ?></span>
								        <span class="span3 tacenter">Subtotal</span>
								        <span class="span3"><?php echo String::formatoNumero($yuppic->quantity*$yuppic->unitary_price); ?>MXN</span>
								      </div>
						      	</fieldset>
						  <?php 
							} ?>

						      </div>
						    </div>
						  </div>
				<?php
						}
					}else{
						echo '<div class="alert alert-info">No has realizado ninguna compra</div>';
					}
				}
				 ?>

						</div>
					</div><!--/span-->
			</div><!--/row-->

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