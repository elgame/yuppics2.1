	<div class="span12 progress_yuppic"><!-- START PROGRESS BAR -->
		<div class="pross_yupp_conte">
			<div class="progress_bg">
				<div class="progress">
				  <div class="bar" style="width: <?php echo (isset($status->progress)? $status->progress: '0'); ?>%;"></div>
				</div>
				<span class="paso1"></span>
				<span class="circl1"></span>
				<span class="paso2"></span>
				<span class="circl2"></span>
				<span class="paso3"></span>
				<span class="circl3"></span>
			</div>
			<p class="txtpaso1">Seleccionar tema</p>
			<p class="muted txtpaso2"><a href="javascript:void(0);" id="btn_select_theme">Seleccionar fotografias</a></p>
			<p class="muted txtpaso3">Creación de Yuppic</p>
			<div class="clearfix"></div>
		</div>
	</div><!-- END PROGRESS BAR -->

	<div class="row-fluid">
		<div class="span12 barratop">
			<div class="tema_barratop">
				<div class="pull-left">
					Elige un tema y personalizalo a tu gusto
				</div>

				<div class="pull-right">
					<div class="input-append ir_pasos pull-right">
						<a href="javascript:void(0);" id="btn_select_theme2" class="btn">Siguiente paso <i class="icon-arrow-right"></i></a>
					</div>

					<form action="<?php echo base_url('yuppics/theme_search'); ?>" method="get" id="tema_frm_buscar" class="tema_form-buscar pull-right">
					  <div class="input-append">
							<input type="text" name="qs" class="span1" id="appendedInputButtons" placeholder="Titulo, Autor.."><button class="btn" type="submit">Buscar</button>
						</div>
					</form>
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
	</div>


	<div class="row-fluid contenido_crea_yupp">
		<div class="span4 barrpreview">
			<div id="tema_preview">
		<?php
		$style = '';
		$txt_color = '';
		if (isset($theme_sel)) {
			$style = 'background-image: url('.base_url($theme_sel->background_img_thum).');background-color: '.$theme_sel->background_color.';color: '.$theme_sel->text_color.'; background-repeat: no-repeat no-repeat;';
			$txt_color = 'color: '.$theme_sel->text_color.';';
		}
		?>
				<div id="tema_prev_yuppic" class="tema_prev_yuppic center" style="<?php echo $style; ?>">
					<br><br><br>
					<div class="bgtitulo">
						<input id="tema_prev_titulo" class="titulo center tacenter" value="<?php echo (isset($theme_sel->title)? $theme_sel->title: '—— TÍTULO ——'); ?>" style="<?php echo $txt_color; ?>">
					</div>
					<input id="tema_prev_autor" class="autor center tacenter" value="<?php echo (isset($theme_sel->author)? $theme_sel->author: 'Autor de Yuppic'); ?>" style="<?php echo $txt_color; ?>">
					<span class="fecha center"><?php echo String::fechaATexto(date("Y-m-d")); ?></span>
				</div>

				<div class="accordion" id="accordion2">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_img">Cambiar imagen de fondo</a>
						</div>
						<div id="collapse_img" class="accordion-body collapse in">
							<div class="accordion-inner">
								<form action="<?php echo base_url('yuppics/theme_image'); ?>" id="tema_frm_imagen" method="post" enctype="multipart/form-data">
									<input type="hidden" id="path_imagen_fondo" value="<?php echo (isset($theme_sel->background_img)? $theme_sel->background_img: ''); ?>">
									<label for="imagen_fondo pull-left">Subir imagen</label>
									<input type="file" name="imagen_fondo" id="imagen_fondo"><br>
									<button type="submit" class="btn btn-primary">Cargar</button>
									<button type="button" id="remove_imagesel" class="btn btn-danger">Quitar</button>
								</form>
								<div id="progress_img_fondo" class="progress hide">
									<div class="bar" style="width: 0%;"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_fondo">Personalizar Colores</a>
						</div>
						<div id="collapse_fondo" class="accordion-body collapse">
							<div class="accordion-inner">
								<div>
									<div class="span6" style="text-align: left;color: #5c5c67;">Color de fondo</div>
									<div class="span6">
										<input type="text" id="color_fondo" name="color_fondo" value="<?php echo (isset($theme_sel->background_color)? $theme_sel->background_color: '#ccc'); ?>">
									</div>
								</div>
								<div>
									<div class="span6" style="text-align: left;color: #5c5c67;">Color de fondo</div>
									<div class="span6">
										<input type="text" id="color_texto" name="color_texto" value="<?php echo (isset($theme_sel->text_color)? $theme_sel->text_color: '#555'); ?>">
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>

		</div><!-- /span4 -->

		<!-- Lista de temas -->
		<div id="thmslists" class="span8 lista_temas">
			<div class="span6 pgtitle">Selecciona un tema y personaliza tu Yuppics</div>
			<div class="span6">
				<div class="pagination pull-right"></div>
			</div>

			<div id="list_themes">
<?php
	if (isset($themes)) {
			echo $themes;
	} ?>
			</div>

		</div><!-- /span8 -->

	</div><!-- /row -->



<div id="messajes_alerts" class="modal hide fade">
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Ok</a>
  </div>
</div>