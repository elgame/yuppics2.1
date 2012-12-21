<form action="<?php echo base_url('contact/send');?>" method="POST" id="frm_contact" class="form-horizontal" data-sendajax="true"
		data-alert="register_contact" data-reset="true">
	<div class="control-group">
		<div id="register_contact" class="alert hide">
			<button type="button" class="close">×</button>
			<span></span>
		</div>
	</div>
	<div class="control-group">
		<input type="text" name="name" class="input-block-level" id="name" placeholder="Nombre(nick)" maxlength="80">
	</div>
	<div class="control-group">
		<input type="email" name="email" class="input-block-level" id="email" placeholder="Dirección de email" maxlength="30">
	</div>
	<div class="control-group">		
		<textarea name="message" rows="7" cols="10" class="input-block-level" id="message" placeholder="Inserte comentarios en este apartado"></textarea>
	</div>
	<div class="control-group">
		<button type="submit" class="btn btn-primary input-xxlarge" id="btn-contact">Enviar mensaje</button>
	</div>
</form>