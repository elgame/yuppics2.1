		<div id="content" class="span6 mtop-content">
			<!-- content starts -->
			
			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body unit-foo">
						<h3>Gana un Yuppic Gratis en cuestión de segundos!</h3>
						<p>Gana un yuppic completamente gratis con tan solo seguir los siguientes pasos. <br>
							Nota: Cada actividad de la lista forma parte de un porcentaje para ganar un yuppic.</p>
					</div>
				</div>
			</div>
					
			
			<div class="row-fluid">
					<div class="box span12">
						<div class="box-header well">
							<h2>Pasos para ganar un yuppic gratis!</h2>
						</div>
						<div class="box-content nopaddin">
							
							<div class="row-fluid">
								<div class="span12">
									
									<div id="promo_alert" class="alert hide">
										<button type="button" class="close">×</button>
										<span></span>
									</div>

									<div class="ganayp_progrss" style="background-color: #fff; padding: 3% 4% 1% 4%;">
										<div class="progress progress-striped">
										  <div class="bar" style="width: <?php echo (isset($status->progress)? $status->progress: '0'); ?>%;"></div>
										</div>
										<p class="muted pull-left">0%</p>
										<p class="muted pull-left" style="margin-left: 48%;">50%</p>
										<p class="muted pull-right">100%</p>
										<div class="clearfix"></div>
									</div>

									<div id="fb-root"></div>
									<script type="text/javascript">
										var fb_app_id = <?php echo $fb_app_id; ?>
									</script>
				<?php if (isset($status->progress) || !isset($status->progress)) {
								$status->progress = isset($status->progress)? $status->progress: 0;
								if ($status->progress < 100) {
				?>
									<table class="table table-condensed table-promo">
										<tr id="fb_comparte_link" class="<?php echo (isset($status->link_facebook)? ($status->link_facebook==1? 'disable': ''): ''); ?>">
											<td>
												<div class="under_group_light ugl_chk">
													<input type="checkbox" <?php echo (isset($status->link_facebook)? ($status->link_facebook==1? 'checked disabled': ''): ''); ?>>
												</div>
											</td>
											<td>
												<h3>Comparte nuestro enlace en tu muro!</h3>
												<p>Comparte en enlace o direccion de nuestra app vía  facebook</p>
											</td>
										</tr>
										<tr id="fb_invita_link" class="<?php echo (isset($status->invit_facebook)? ($status->invit_facebook==1? 'disable': ''): ''); ?>">
											<td>
												<div class="under_group_light ugl_chk">
													<input type="checkbox" <?php echo (isset($status->invit_facebook)? ($status->invit_facebook==1? 'checked disabled': ''): ''); ?>>
												</div>
											</td>
											<td>
												<h3>Invita a tus amigos a crear sus propios Photobooks</h3>
												<p>Invita a tus amigos vía facebook a utilizar nuestra app</p>
											</td>
										</tr>
										<tr id="prom_tweetea" class="<?php echo (isset($status->tweet)? ($status->tweet==1? 'disable': ''): ''); ?>">
											<td>
												<div class="under_group_light ugl_chk">
													<input type="checkbox" <?php echo (isset($status->tweet)? ($status->tweet==1? 'checked disabled': ''): ''); ?>>
												</div>
											</td>
											<td>
												<h3>Tweetea tu amor por Yuppics</h3>
												<p>Comparte tu amor por yuppics vía twitter</p>
											</td>
										</tr>
										<tr id="prom_feedback" class="<?php echo (isset($status->feedback)? ($status->feedback==1? 'disable': ''): ''); ?>">
											<td>
												<div class="under_group_light ugl_chk">
													<input type="checkbox" <?php echo (isset($status->feedback)? ($status->feedback==1? 'checked disabled': ''): ''); ?>>
												</div>
											</td>
											<td>
												<h3>Proporcionanos feedback</h3>
												<p>Todas tus ideas y sugerencias nos son esenciales.</p>
											</td>
										</tr>
									</table>
				<?php }
					} ?>

								</div><!--/span-->
						</div><!--/row-->
					<?php if (isset($status->progress)) {
						if ($status->progress == 100) { 
					?>
							<div class="row-fluid">
								<div style="background-color: #ecf6fc; border-top: 1px #d5dee5 solid;">
									<div class="span7" style="padding: 6% 6%; border-right: 1px #d5dee5 solid;">
										<h4 style="color: #646a70; margin-bottom: 6px; font-size: 1.2em;">Has ganado un cupón de descuento</h4>
										<p class="muted" style="color: #98a1aa;">Con un valor de <?php echo String::formatoNumero($status->coupon->amount,2,''); ?>MXN valido en la compra de un yuppic.</p>
									</div><!--/span-->

									<div class="span5" style="padding: 3% 6%;"> <!-- cupon -->
										<!-- <div class="span6 tacenter cupon_left">
											<span style="color: #999;"><?php echo $status->coupon->percentage; ?>%</span>
											<p>De descuento</p>
										</div> -->
										<div class="span12">
											<div class="cupon_code">Código <span id="cupon_ganado"><?php echo $status->coupon->code; ?></span></div>
											<p class="cupinf">Valor del Cupón <span><?php echo String::formatoNumero($status->coupon->amount,2,''); ?>MXN</span></p>
											<button type="button" id="btn_usecupon" data-clipboard-text="<?php echo $status->coupon->code; ?>" class="btn btn-large btn-info center">Utilizar Cupón</button>
										</div>
									</div><!--/span-->
									<div class="clearfix"></div>
								</div>

							</div><!--/row-->
				<?php }
					} ?>

						</div>
					</div><!--/span-->

			</div><!--/row-->

			<br><br>
	</div><!--/#content.span7-->


	<div id="modal_feedback" class="modal hide fade"><!-- START modal contacto -->
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h2 id="myModalLabel3" style="display: inline;">Feedback</h2><span class="muted"> Envia tus comentarios, observaciones y/o sugerencias.</span>
	  </div>
	  <div class="modal-body">

				<div class="control-group">
					<div id="send_feedback_alert" class="alert hide">
						<button type="button" class="close">×</button>
						<span></span>
					</div>
				</div>

				<div class="control-group">		
					<textarea name="feedback_text" rows="7" cols="10" class="input-block-level" id="feedback_text" placeholder="comentarios, observaciones y/o sugerencias" required></textarea>
				</div>
				<div class="control-group">
					<button type="submit" class="btn btn-success btn-large input-xxlarge" id="btn-feedback">Enviar feedback</button>
				</div>

	  </div>
	</div><!-- END modal contacto -->

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