	<div class="span12">
		<div style="background-color: #fff; padding: 1% 4% 0% 4%;">
			<div class="progress">
			  <div class="bar" style="width: <?php echo (isset($status->progress)? $status->progress: '0'); ?>%;"></div>
			</div>
			<p class="muted pull-left">Seleccionar tema</p>
			<p class="muted pull-left" style="margin-left: 39%;"><a href="javascript:void(0);" id="btn_select_theme">Seleccionar fotografias</a></p>
			<p class="muted pull-right">Creación de Yuppic</p>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12 ">
			<div class="tema_barratop">
				<div class="pull-left">
					Selecciona un tema y personaliza tu Yuppic
				</div>

				<div class="pull-right">
					<form action="<?php echo base_url('yuppics/theme_search'); ?>" method="get" id="tema_frm_buscar" class="tema_form-buscar">
						<div class="input-append">
							<input type="text" name="qs" class="span2" id="appendedInputButtons"><button class="btn" type="submit">Buscar</button>
						</div>
					</form>
				</div>

				<div class="clearfix"></div>
			</div>
		</div>
	</div>


	<div class="row-fluid">
		<div class="span4">
			<div id="tema_preview">
				<h4>Preview Yuppic</h4>
		<?php 
		$style = '';
		if (isset($theme_sel)) {
			$style = 'background-image: url('.base_url($theme_sel->background_img_thum).');background-color: '.$theme_sel->background_color.'; color: '.$theme_sel->text_color.'; background-repeat: no-repeat no-repeat;';
		}
		?>
				<div id="tema_prev_yuppic" class="tema_prev_yuppic center" style="<?php echo $style; ?>">
					<div id="tema_prev_titulo" class="titulo center"><?php echo (isset($theme_sel->title)? $theme_sel->title: 'Titulo del Yuppic'); ?></div>
					<div id="tema_prev_autor" class="autor center"><?php echo (isset($theme_sel->author)? $theme_sel->author: 'Autor de Yuppic'); ?></div>
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
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_fondo">Color de fondo</a>
						</div>
						<div id="collapse_fondo" class="accordion-body collapse">
							<div class="accordion-inner">
								<input type="hidden" id="color_fondo" name="color_fondo" value="<?php echo (isset($theme_sel->background_color)? $theme_sel->background_color: '#ccc'); ?>">
									<div id="colorpicker_fondo" class="colorpicker center"></div>
							</div>
						</div>
					</div>

					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_texto">Color de texto</a>
						</div>
						<div id="collapse_texto" class="accordion-body collapse">
							<div class="accordion-inner">
								<input type="hidden" id="color_texto" name="color_texto" value="<?php echo (isset($theme_sel->text_color)? $theme_sel->text_color: '#555'); ?>">
									<div id="colorpicker_texto" class="colorpicker center"></div>
							</div>
						</div>
					</div>

					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_titulo">Titulo del Yuppic</a>
						</div>
						<div id="collapse_titulo" class="accordion-body collapse">
							<div class="accordion-inner">
								<input type="text" id="titulo_yuppic" value="<?php echo (isset($theme_sel->title)? $theme_sel->title: ''); ?>" placeholder="Titulo de tu Yuppic">
							</div>
						</div>
					</div>

					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_autor">Autor del Yuppic</a>
						</div>
						<div id="collapse_autor" class="accordion-body collapse">
							<div class="accordion-inner">
								<input type="text" id="autor_yuppic" value="<?php echo (isset($theme_sel->author)? $theme_sel->author: ''); ?>" placeholder="Autor de tu Yuppic">
							</div>
						</div>
					</div>

				</div>

			</div>

		</div><!-- /span4 -->

		<!-- Lista de temas -->
		<div class="span8">
			<div id="list_themes">
<?php 
	if (isset($themes)) {
			echo $themes;
	} ?>
			</div>
			<div class="pagination"></div>

		</div><!-- /span8 -->

		<a href="javascript:void(0);" id="btn_select_theme2" class="pull-right">Siguiente paso <i class="icon-arrow-right"></i></a>

	</div><!-- /row -->
	


<div id="messajes_alerts" class="modal hide fade">
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Ok</a>
  </div>
</div>