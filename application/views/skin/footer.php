	</div><!--/fluid-row-->

</div><!--/.fluid-container-->

	<div class="clear"></div>

	<div id="modal_contact" class="modal hide fade"><!-- START modal contacto -->
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h2 id="myModalLabel" style="display: inline;">Contacto</h2><span class="muted"> ¿Tienes alguna duda, comentario o sugerencia?</span>
	  </div>
	  <div class="modal-body">

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
				<button type="submit" class="btn btn-primary btn-large input-xxlarge" id="btn-contact">Enviar mensaje</button>
			</div>
		</form>

	  </div>
	  <div class="modal-footer center" style="background-color: #fff;">
	  	<h3 class="muted">¿Deseas contactarnos? Puedes hacerlo también a través de nuestras redes sociales!</h3>
	  	<span class="muted">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</span>
	  </div>
	</div><!-- END modal contacto -->

</body>
</html>