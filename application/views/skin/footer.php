	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

	<div class="clear"></div>

	<div id="modal_contact" class="modal hide fade"><!-- START modal contacto -->
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h2 id="myModalLabel1" style="display: inline;">Contacto</h2>
	  </div>
	  <div class="modal-body">
	  	<span class="muted" style="font-size:16px;">¿Tienes alguna duda, comentario o sugerencia?</span>
	  	<form action="<?php echo base_url('contact/send');?>" method="POST" id="frm_contact" class="form-horizontal" data-sendajax="true"
				data-alert="register_contact" data-reset="true">
			<div class="control-group">
				<div id="register_contact" class="alert hide">
					<button type="button" class="close">×</button>
					<span></span>
				</div>
			</div>
			<div class="control-group">
				<input type="text" name="name" class="input-block-level" id="name" placeholder="Nombre(nick)" required maxlength="80">
			</div>
			<div class="control-group">
				<input type="text" name="email" class="input-block-level" id="email" placeholder="Dirección de email" required maxlength="30">
			</div>
			<div class="control-group">		
				<textarea name="message" rows="7" cols="10" class="input-block-level" id="message" placeholder="Inserte comentarios en este apartado" required></textarea>
			</div>
			<div class="control-group">
				<button type="submit" class="btn btn-success btn-large input-xxlarge" id="btn-contact">Enviar mensaje</button>
			</div>
		</form>

	  </div>
	  <div class="modal-footer center" style="background-color: #fff;">
	  	<!-- <h3 class="muted">¿Deseas contactarnos? Puedes hacerlo también a través de nuestras redes sociales!</h3>
	  	<span class="muted">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</span> -->
	  </div>
	</div><!-- END modal contacto -->

<?php if (isset($info_customer)) 
{ ?>
	<div id="modal_newsletter" class="modal hide fade"><!-- START modal newsletter -->
	  <div class="modal-header tacenter">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h2 id="myModalLabel2">Suscribete a nuestro <span class="green">Newsletter</span> y mantente informado siempre!</h2>
	    <span class="muted">No esperes más! y mantente al tanto de todas nuestras promociones, novedades y actividades que tenemos pensadas para ti.</span>
	  </div>
	  <div class="modal-body">

	  	<form action="<?php echo base_url('newsletter/send');?>" method="POST" id="frm_contact" class="form-horizontal" data-sendajax="true"
				data-alert="register_newsletter" data-reset="false" data-callback="newsletter_success">
			<div class="control-group">
				<div id="register_newsletter" class="alert hide">
					<button type="button" class="close">×</button>
					<span></span>
				</div>
			</div>
			<div class="control-group tacenter">
				<input type="hidden" name="first_name" value="<?php echo $info_customer->first_name; ?>">
				<input type="hidden" name="last_name" value="<?php echo $info_customer->last_name; ?>">
				<input type="hidden" name="email" value="<?php echo $info_customer->email; ?>">
				<input type="hidden" name="newsletter" value="<?php echo $info_customer->newsletter; ?>">
				<h4 class="muted"><?php echo $info_customer->first_name.' '.$info_customer->last_name; ?></h4>
				<h4 class="muted"><?php echo $info_customer->email; ?></h4>
			</div>

			<div class="control-group">
		<?php 
		if ($info_customer->newsletter == 0){
		 ?>
				<button type="submit" class="btn btn-success btn-large input-xxlarge" id="btn-contact">Suscribete</button>
		<?php 
		}else{
		?>
				<span class="muted">Ya te encuentras suscrito al newsletter.</span>
				<button type="submit" class="btn btn-danger btn-large input-xxlarge" id="btn-contact">Desuscribete</button>
		<?php
		} ?>
			</div>
		</form>

	  </div>
	  <div class="modal-footer center" style="background-color: #fff;">
	  	<span class="muted" style="color:#bdbdbd;">Revisa tu correo electrónico y confirma  tu subscripción a través de la url incluida en dicho email, para formar parte de nuestra lista de novedades.</span>
	  </div>
	</div><!-- END modal newsletter -->
<?php } ?>

</body>
</html>