		<div id="content" class="span7 mtop-content">
			<!-- content starts -->

			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body unit-foo">
						<h3>Revisa tus reportes</h3>
						<p>A continuación se presenta un listado con el historial de cada una de tus compras dentro de la aplicacion, para consultar más detalles sobre cada compra, da clic sobre la celda para ver más detalles al respecto.</p>
					</div>
				</div>
			</div>


			<h2 class="myriad" style="color:#464f55;">Historial de compras</h2>

			<div class="row-fluid">
					<div class="box span12">

            <div class="box-header well">
              <h2>Resumen de compras realizadas</h2>
              <div class="box-icon pull-right">
                <div class="btn-group">
                  <a href="#" class="btn btn-white btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
              </div>
            </div>

            <div class="box-content" style="padding: 0 !important;">
  						<div class="accordion accord_barr_rig" id="hisotry-accordion">
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
  						        <div class="clearfix"></div>
  						      </a>
  						    </div>
  						    <div id="collapse<?php echo $value->id_order; ?>" class="accordion-body collapse">
  						      <div class="accordion-inner">
  						  <?php
  						  foreach ($value->yuppics as $key => $yuppic) {
  						  ?>
  						      	<fieldset>
  						      		<legend style="font-size: 15px;"><?php echo $yuppic->title; ?></legend>
  						      		<div class="row-fluid">
  								        <span class="span3 tacenter strongss">Precio</span>
  								        <span class="span3"><?php echo String::formatoNumero($yuppic->unitary_price); ?>MXN</span>
  								        <span class="span3 tacenter strongss">Fecha</span>
  								        <span class="span3"><?php echo String::humanDate(strtotime($yuppic->created)); ?></span>
  								      </div>
  								      <div class="row-fluid">
  								        <span class="span3 tacenter strongss">Cantidad</span>
  								        <span class="span3"><?php echo $yuppic->quantity; ?> yuppic<?php echo ($yuppic->quantity>1? 's': ''); ?></span>
  								        <span class="span3 tacenter strongss">Subtotal</span>
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
  						echo '<div class="alert alert-transparent">No has realizado ninguna compra</div>';
  					}
  				}
  				 ?>

  						</div>
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