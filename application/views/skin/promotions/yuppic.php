		<div id="content" class="span7 mtop-content">
			<!-- content starts -->
			
			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body unit-foo">
						<h3>Gana un Yuppic Gratis en cuestión de segundos!</h3>
						<p>Gana un yuppic completamente gratis con tan solo seguir los siguientes pasos. <br>
							Cada actividad de la lista forma parte de un porcentaje para ganar un yuppic</p>
					</div>
				</div>
			</div>
					
			
			<div class="hero-unit unitwhite">
				<div class="unit-body unit-foo" style="padding: 20px 0px;">

					<div class="row-fluid">
							<div class="span12">
								
								<div id="promo_alert" class="alert hide">
									<button type="button" class="close">×</button>
									<span></span>
								</div>

								<div style="background-color: #fff; padding: 3% 4% 1% 4%;">
									<div class="progress">
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
											<input type="checkbox" disabled <?php echo (isset($status->link_facebook)? ($status->link_facebook==1? 'checked': ''): ''); ?>>
										</td>
										<td>
											<h3 class="muted">Comparte nuestro enlace en tu muro!</h3>
											<p>Comparte en enlace o direccion de nuestra app vía  facebook</p>
										</td>
									</tr>
									<tr id="fb_invita_link" class="<?php echo (isset($status->invit_facebook)? ($status->invit_facebook==1? 'disable': ''): ''); ?>">
										<td>
											<input type="checkbox" disabled <?php echo (isset($status->invit_facebook)? ($status->invit_facebook==1? 'checked': ''): ''); ?>>
										</td>
										<td>
											<h3 class="muted">Invita a tus amigos a crear sus propios Photobooks</h3>
											<p>Invita a tus amigos vía facebook a utilizar nuestra app</p>
										</td>
									</tr>
									<tr id="prom_tweetea" class="<?php echo (isset($status->tweet)? ($status->tweet==1? 'disable': ''): ''); ?>">
										<td>
											<input type="checkbox" disabled <?php echo (isset($status->tweet)? ($status->tweet==1? 'checked': ''): ''); ?>>
										</td>
										<td>
											<h3 class="muted">Tweetea tu amor por Yuppics</h3>
											<p>Comparte tu amor por yuppics vía twitter</p>
										</td>
									</tr>
									<tr id="prom_feedback" class="<?php echo (isset($status->feedback)? ($status->feedback==1? 'disable': ''): ''); ?>">
										<td>
											<input type="checkbox" disabled <?php echo (isset($status->feedback)? ($status->feedback==1? 'checked': ''): ''); ?>>
										</td>
										<td>
											<h3 class="muted">Proporcionanos feedback</h3>
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
						<div style="background-color: #fff; padding: 3% 4% 1% 4%;">
							<div class="span7">
								<h4>Has ganado un cupón de descuento</h4>
								<p class="muted">Con un valor de <?php echo String::formatoNumero($status->coupon->amount,2,''); ?>MXN valido en la compra de un yuppic.</p>
							</div><!--/span-->

							<div class="span5 cupon">
								<div class="span6 tacenter cupon_left">
									<span><?php echo $status->coupon->percentage; ?>%</span>
									<p>De descuento</p>
								</div>
								<div class="span6">
									<div class="muted cupon_code">Codigo <?php echo $status->coupon->code; ?></div>
									<p>Valor del Cupón <?php echo String::formatoNumero($status->coupon->amount,2,''); ?>MXN</p>
								</div>
							</div><!--/span-->
							<div class="clearfix"></div>
						</div>

					</div><!--/row-->
		<?php }
			} ?>
				</div>
			</div><!-- /hero-unit -->

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
					<button type="submit" class="btn btn-primary btn-large input-xxlarge" id="btn-feedback">Enviar feedback</button>
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