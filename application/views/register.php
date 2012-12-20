<div class="container">
	<div class="row-fluid">
		<div class="span12" style="text-align:center;">
			<h3 class="muted">Registro</h3>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset3">
			<form action="<?php echo base_url('user/register/')?>" method="POST" class="form-horizontal">
				<div class="control-group">
					<label for="firstname" class="control-label">Nombre</label>
					<div class="controls">
						<input type="text" name="firstname" value="<?php echo set_value('firstname')?>" class="" id="firstname" placeholder="Nombre" autofocus required maxlength="30">
						<?php echo form_error('firstname');?>
					</div>
				</div>
				<div class="control-group">
					<label for="lastname" class="control-label">Apellido Paterno</label>
					<div class="controls">
						<input type="text" name="lastname" value="<?php echo set_value('lastname')?>" class="" id="lastname" placeholder="Apellido Paterno" maxlength="40">
						<?php echo form_error('lastname');?>
					</div>
				</div>
				<div class="control-group">
					<label for="email" class="control-label">E-mail</label>
					<div class="controls">
						<input type="email" name="email" value="<?php echo set_value('email')?>" class="" id="email" placeholder="email@ejemplo.com" required maxlength="60">
						<?php echo form_error('email');?>
						<span class="help-block">Asegurate de introducir un email valido</span>
					</div>
				</div>
				<div class="control-group">
					<label for="username" class="control-label">Usuario</label>
					<div class="controls">
						<input type="text" name="username" value="<?php echo set_value('username')?>" class="" id="username" placeholder="Usuario" required maxlength="15">
						<?php echo form_error('usernama');?>
					</div>
				</div>
				<div class="control-group">
					<label for="password" class="control-label">Password</label>
					<div class="controls">
						<input type="password" name="password" value="" class="" id="password" placeholder="Password" required maxlength="15">
						<?php echo form_error('password');?>
						<span class="help-block">8 caracteres mínimo</span>
					</div>
				</div>
				<div class="control-group">
					<label for="repassword" class="control-label">Repetir password</label>
					<div class="controls">
						<input type="password" name="repassword" value="" class="" id="repassword" placeholder="Re-password" required maxlength="15">
						<?php echo form_error('repassword');?>
						<span class="help-block">8 caracteres mínimo</span>
					</div>
				</div>
				<div class="form-actions" style="background-color:white;border-top:white;">
					<button type="submit" class="btn btn-primary">Registrarme</button>
					<button type="reset" class="btn">Limpiar</button>
				</div>
			</form>
		</div>
	</div>
</div>