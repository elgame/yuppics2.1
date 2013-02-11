		<div id="content" class="span7 mtop-content">
			<!-- content starts -->

			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body unit-foo">
						<h3>Importante</h3>
						<p>Recuerda que es necesario que proporciones correctamente todos tus datos de envío y facturacion antes de realizar tu compra.</p>
					</div>
				</div>
			</div>


			<h2 class="myriad" style="color:#464f55;">Edita tus datos</h2>

			<div class="row-fluid">
					<div class="box span12">
						<div class="box-header well">
							<h2>Informacion personal</h2>

							<div class="box-icon pull-right">
								<div class="btn-group">
								  <a href="#" class="btn btn-white btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
								</div>
							</div>
						</div>
						<div class="box-content">
							<form action="<?php echo base_url('customer/update/')?>" method="POST" class="form-horizontal" data-sendajax="true"
								data-alert="register_alert" data-reset="false">
								<input type="hidden" name="customer" value="<?php echo $this->session->userdata('id_usuario'); ?>">

								<div class="control-group">
									<div id="register_alert" class="alert hide">
										<button type="button" class="close">×</button>
										<span></span>
									</div>
								</div>

								<div class="control-group">
									<label for="firstname" class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" name="firstname" value="<?php echo $info_customer->first_name; ?>" class="input-block-level" id="firstname" placeholder="Nombre" autofocus required maxlength="30">
									</div>
								</div>

								<div class="control-group">
									<label for="lastname" class="control-label">Apellido Paterno</label>
									<div class="controls">
										<input type="text" name="lastname" value="<?php echo $info_customer->last_name; ?>" class="input-block-level" id="lastname" placeholder="Apellido Paterno" maxlength="40">
									</div>
								</div>

								<div class="control-group">
									<label for="email" class="control-label">E-mail</label>
									<div class="controls">
										<input type="email" name="email" value="<?php echo $info_customer->email; ?>" class="input-block-level" id="email" placeholder="email@ejemplo.com" required maxlength="60">
										<span class="help-block">Asegurate de introducir un email valido</span>
									</div>
								</div>

								<div class="control-group">
									<label for="username" class="control-label">Usuario</label>
									<div class="controls">
										<input type="text" name="username" value="<?php echo $info_customer->username; ?>" class="input-block-level" id="username" placeholder="Usuario" maxlength="15">
									</div>
								</div>

								<div class="password-container">
									<div class="control-group">
										<label for="password" class="control-label">Password</label>
										<div class="controls">
											<input type="password" name="password" value="" class="input-block-level strong-password" id="password" placeholder="Password" maxlength="15">
											<div class="help-inline"><div class="strength-indicator"><div class="meter"></div></div></div>
											<span class="help-block">6 caracteres mínimo</span>
										</div>
									</div>

									<div class="control-group">
										<label for="repassword" class="control-label">Repetir password</label>
										<div class="controls">
											<input type="password" name="repassword" value="" class="input-block-level strong-password" id="repassword" placeholder="Re-password" maxlength="15">
											<span class="help-block">6 caracteres mínimo</span>
										</div>
									</div>
								</div>

								<div class="form-actions white_action">
								  <button type="submit" class="btn btn-success">Guardar</button>
								</div>

							</form>
						</div>
					</div><!--/span-->

			</div><!--/row-->

			<div class="row-fluid">

				<div class="box span12" id="address">
					<div class="box-header well">
						<h2>Libro de direcciones</h2>

						<div class="box-icon pull-right">
							<div class="btn-group">
								<a href="#modal_addaddress" class="btn btn-white" title="Agregar direccion" role="button" data-toggle="modal"><i class="icon-plus"></i></a>
								<a href="#" class="btn btn-white btn-minimize"><i class="icon-chevron-up"></i></a>
							</div>
						</div>
					</div>
					<div class="box-content nopaddin">
						<div class="row-fluid">
							<div id="deleteaddress_alert" class="alert hide">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<span></span>
							</div>
						</div>

						<div class="row-fluid xxboxx">
							<div class="span6 title-box">
									Direccion predeterminada
							</div>
							<div class="span6 title-box">
									Direccion predeterminada
							</div>
						</div>
				<?php
					if ($address_books != false)
					{
				?>
					<div class="paddin10" style="background-color: #fbfbfc;">
						<div class="row-fluid">
							<div class="span6">
								

									<div class="control-group">
								<?php
								if (isset($address_books['default']))
								{
									foreach ($address_books['default'] as $key => $default) {
										if($default->default_billing==1 && $default->default_shipping == 1)
											echo '<strong>Direccion de facturacion y envio</strong>';
										else if ($default->default_billing==1)
											echo '<strong>Direccion de facturacion</strong>';
										else if ($default->default_shipping == 1)
											echo '<strong>Direccion de envio</strong>';
								?>
										<p>
											<span class="span2">
												<a href="#modal_updateaddress" class="btn-link update_address center" data-toggle="modal" data-id="<?php echo $default->id_address; ?>">
													<img src="<?php echo base_url('application/images/edit-dir.png'); ?>" width="20" height="20">
												</a>
											</span>
											<span class="span10">
												<?php
												echo $default->contact_first_name.' '.$default->contact_last_name.'<br>'.
													($default->company!=''? $default->company.'<br>': '').
													($default->rfc!=''? $default->rfc.'<br>': '').
													$default->street.', '.$default->colony.'<br>'.
													$default->city.', '.$default->state.', '.$default->country;
												?>
											</span>
										</p>
								<?php
									}
								} ?>
									</div>

							</div>

							<div class="span6">
									<div class="control-group">
									<?php
									if (isset($address_books['others']))
									{
										foreach ($address_books['others'] as $key => $default) {
									?>
										<strong>Direccion <?php echo $key+1; ?></strong>
											<p>
												<span class="span2">
													<a href="#modal_updateaddress" class="btn-link update_address center" data-toggle="modal" data-id="<?php echo $default->id_address; ?>">
														<img src="<?php echo base_url('application/images/edit-dir.png'); ?>" width="20" height="20">
													</a>
													<a href="<?php echo base_url('address_book/delete?id='.$default->id_address) ?>" class="btn-link center"
														onclick="msb.confirm('Estas seguro de eliminar la direccion?', 'Libro de direcciones', this); return false;">
														<img src="<?php echo base_url('application/images/delete-dir.png'); ?>" width="20" height="20">
													</a>
												</span>
												<span class="span10">
													<?php
													echo $default->contact_first_name.' '.$default->contact_last_name.'<br>'.
														($default->company!=''? $default->company.'<br>': '').
														($default->rfc!=''? $default->rfc.'<br>': '').
														$default->street.', '.$default->colony.'<br>'.
														$default->city.', '.$default->state.', '.$default->country;
													?>
												</span>
											</p>
									<?php
										}
									}?>
									</div>

							</div>
						</div>

					</div>
				<?php
				}
				else
				{
				?>
						<div class="alert alert-block">
						  <h4>No has registrado direcciones</h4>
						  Agrega almenos una direccion :)
						</div>
				<?php
				}
				?>
					</div>
				</div><!--/span-->

			</div><!--/row-->


			<br>
			<br>

					<!-- content ends -->
	</div><!--/#content.span8-->



	<div id="modal_addaddress" class="modal hide fade">
		<form action="<?php echo base_url('address_book/add/')?>" method="POST" class="form-horizontal" data-sendajax="true"
				data-alert="address_alert" data-callback="address_success">
			<div class="modal-header tacenter">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h2>Direccion</h2>
		  </div>
			<div class="modal-body">

				<div class="span12">
						<div class="control-group">
							<div id="address_alert" class="alert hide">
								<button type="button" class="close">×</button>
								<span></span>
							</div>
						</div>

						<div class="control-group">
							<label for="contact_first_name" class="control-label">Nombre</label>
							<div class="controls">
								<input type="text" name="contact_first_name" value="<?php echo $info_customer->first_name; ?>" class="" id="contact_first_name" placeholder="Nombre" autofocus required maxlength="30">
							</div>
						</div>
						<div class="control-group">
							<label for="contact_last_name" class="control-label">Apellido Paterno</label>
							<div class="controls">
								<input type="text" name="contact_last_name" value="<?php echo $info_customer->last_name; ?>" class="" id="contact_last_name" placeholder="Apellido Paterno" required maxlength="40">
							</div>
						</div>

						<div class="control-group">
							<label for="company" class="control-label">Compañia</label>
							<div class="controls">
								<input type="text" name="company" class="" id="company" placeholder="yuppics" maxlength="110">
							</div>
						</div>
						<div class="control-group">
							<label for="rfc" class="control-label">RFC</label>
							<div class="controls">
								<input type="text" name="rfc" class="" id="rfc" placeholder="RFC" maxlength="13">
							</div>
						</div>

						<div class="control-group">
							<label for="street" class="control-label">Direccion</label>
							<div class="controls">
								<input type="text" name="street" class="" id="street" placeholder="Direccion" maxlength="100" required>
							</div>
						</div>
						<div class="control-group">
							<label for="colony" class="control-label">Colonia</label>
							<div class="controls">
								<input type="text" name="colony" class="" id="colony" placeholder="Colonia" maxlength="70" required>
							</div>
						</div>
						<div class="control-group">
							<label for="city" class="control-label">Ciudad</label>
							<div class="controls">
								<input type="text" name="city" class="" id="city" placeholder="Ciudad" maxlength="70" required>
							</div>
						</div>
						<div class="control-group">
							<label for="between_streets" class="control-label">Entre calles</label>
							<div class="controls">
								<input type="text" name="between_streets" class="" id="between_streets" placeholder="Entre calles" maxlength="160">
							</div>
						</div>

						<div class="control-group">
							<label for="state" class="control-label">Estado</label>
							<div class="controls">
								<select name="state" id="state" required>
									<option value=""></option>
								<?php
								foreach ($states as $key => $value) {
								?>
									<option value="<?php echo $value->id_state ?>"><?php echo $value->name ?></option>
								<?php
								}
								?>
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label"></label>
							<div class="controls">
								<label for="default_billing">
									<input type="checkbox" name="default_billing" id="default_billing" value="si"> Direccion predeterminada de Facturacion
								</label>

								<label for="default_shipping">
									<input type="checkbox" name="default_shipping" id="default_shipping" value="si"> Direccion predeterminada de Envio
								</label>
							</div>
						</div>

				</div>


			</div>
			<div class="modal-footer" style="background-color: #fff;">
				<button type="submit" class="btn btn-success">Guardar</button>
			</div>
		</form>
	</div>


	<div id="modal_updateaddress" class="modal hide fade">
		<form id="frm_updateaddress" action="<?php echo base_url('address_book/update/')?>" method="POST" class="form-horizontal" data-sendajax="true"
				data-alert="updateaddress_alert" data-callback="address_success">
			<div class="modal-header tacenter">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h2>Modificar direccion</h2>
		  </div>
			<div class="modal-body">

				<div class="span12">
						<div class="control-group">
							<div id="updateaddress_alert" class="alert hide">
								<button type="button" class="close">×</button>
								<span></span>
							</div>
						</div>

						<input type="hidden" name="id_address" id="id_address" value="">
						<div class="control-group">
							<label for="contact_first_name" class="control-label">Nombre</label>
							<div class="controls">
								<input type="text" name="contact_first_name" value="<?php echo $info_customer->first_name; ?>" class="" id="contact_first_name" placeholder="Nombre" autofocus required maxlength="30">
							</div>
						</div>
						<div class="control-group">
							<label for="contact_last_name" class="control-label">Apellido Paterno</label>
							<div class="controls">
								<input type="text" name="contact_last_name" value="<?php echo $info_customer->last_name; ?>" class="" id="contact_last_name" placeholder="Apellido Paterno" required maxlength="40">
							</div>
						</div>

						<div class="control-group">
							<label for="company" class="control-label">Compañia</label>
							<div class="controls">
								<input type="text" name="company" class="" id="company" placeholder="yuppics" maxlength="110">
							</div>
						</div>
						<div class="control-group">
							<label for="rfc" class="control-label">RFC</label>
							<div class="controls">
								<input type="text" name="rfc" class="" id="rfc" placeholder="RFC" maxlength="13">
							</div>
						</div>

						<div class="control-group">
							<label for="street" class="control-label">Direccion</label>
							<div class="controls">
								<input type="text" name="street" class="" id="street" placeholder="Direccion" maxlength="100" required>
							</div>
						</div>
						<div class="control-group">
							<label for="colony" class="control-label">Colonia</label>
							<div class="controls">
								<input type="text" name="colony" class="" id="colony" placeholder="Colonia" maxlength="70" required>
							</div>
						</div>
						<div class="control-group">
							<label for="city" class="control-label">Ciudad</label>
							<div class="controls">
								<input type="text" name="city" class="" id="city" placeholder="Ciudad" maxlength="70" required>
							</div>
						</div>
						<div class="control-group">
							<label for="between_streets" class="control-label">Entre calles</label>
							<div class="controls">
								<input type="text" name="between_streets" class="" id="between_streets" placeholder="Entre calles" maxlength="160">
							</div>
						</div>

						<div class="control-group">
							<label for="id_state" class="control-label">Estado</label>
							<div class="controls">
								<select name="id_state" id="id_state" required>
									<option value=""></option>
								<?php
								foreach ($states as $key => $value) {
								?>
									<option value="<?php echo $value->id_state ?>"><?php echo $value->name ?></option>
								<?php
								}
								?>
								</select>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label"></label>
							<div class="controls">
								<label for="default_billing1">
									<input type="checkbox" name="default_billing" id="default_billing1" value="1"> Direccion predeterminada de Facturacion
								</label>

								<label for="default_shipping1">
									<input type="checkbox" name="default_shipping" id="default_shipping1" value="1"> Direccion predeterminada de Envio
								</label>
							</div>
						</div>

				</div>


			</div>
			<div class="modal-footer" style="background-color: #fff;">
				<button type="submit" class="btn btn-success">Guardar</button>
			</div>
		</form>
	</div>



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