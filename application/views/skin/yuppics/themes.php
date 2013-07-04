	<div class="span12 progress_yuppic"><!-- START PROGRESS BAR -->
		<div class="pross_yupp_conte">
			<div class="progress_bg">
				<div class="progress">
				  <div class="bar" style="width: <?php echo (isset($status->progress)? $status->progress: '0'); ?>%;"></div>
				</div>
				<span class="paso1"></span>
				<span class="circl1"></span>
				<span class="paso2"></span>
				<!-- <span class="circl2"></span> -->
				<span class="paso3"></span>
				<!-- <span class="circl3"></span> -->
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
				<div class="pull-left" style="line-height: 42px;font-size: 17px;margin-left: 28px;color: #F4F4F5;">
					<div class="bullet"></div> Elige un tema y personalizalo a tu gusto
				</div>

				<div class="pull-right">
					<div class="input-append ir_pasos pull-right hover-blue under_gpp">
						<a href="javascript:void(0);" id="btn_select_theme2" class="btn textura">Siguiente paso <i class="icon-right"></i></a>
					</div>

					<form action="<?php echo base_url('yuppics/theme_search'); ?>" method="get" id="tema_frm_buscar" class="tema_form-buscar pull-right under_gpp mnu-info-usr">
						<div class="btn-group">
						  <a class="btn dropdown-toggle textura" data-toggle="dropdown" href="#">
						    <span id="autor_sel_tthem">Todos los temas</span>
						    <i class="icon-chevron-down1"></i>
						  </a>
						  <ul class="dropdown-menu">
						    <li><a href="javascript:yuppic_tema.selThemesAutor('');">Todos los temas</a></li>
						    <li><a href="javascript:yuppic_tema.selThemesAutor('Gusanito');">Gusanito</a></li>
						    <li><a href="javascript:yuppic_tema.selThemesAutor('Johnson&johnson');">Johnson&johnson</a></li>
						    <li><a href="javascript:yuppic_tema.selThemesAutor('Yuppics');">Yuppics</a></li>
						  </ul>
						</div>

					  <div class="input-append" style="display:none;">
							<input type="text" name="qs" class="span1" id="appendedInputButtons" placeholder="Titulo, Autor.."><button class="btn submmit" type="submit">Buscar</button>
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
		$style_img = 'top:0%; left:0%; display:none;';
		$txt_color = '';
		$img_bg = '';
		if (isset($theme_sel)) {
			$style = '';
			$img_bg = $theme_sel->background_img_thum;
			if($theme_sel->bg_pattern=='1'){
				$img_bg = $theme_sel->background_img;
				$style .= 'background-image: url('.base_url($img_bg).');background-repeat: repeat;';
			}
			$style .= 'background-color: '.$theme_sel->background_color.';color: '.$theme_sel->text_color.';';
			$txt_color = 'color: '.$theme_sel->text_color.';';

			$style_img = 'top:'.$theme_sel->bg_img_y.'%; left:'.$theme_sel->bg_img_x.'%; display:'.($theme_sel->bg_pattern=='0'? 'block': 'none').';';

			//franja
			$style_franja = '';
			if($theme_sel->background_franja != ''){
				$style_franja = "background: url(".$theme_sel->background_franja.");";
			}elseif($theme_sel->background_franja_color != ''){
				$style_franja = "background-color: ".$theme_sel->background_franja_color.";";
			}
			switch($theme_sel->background_franja_position){
				case 't': $style_franja .= 'top: 0px;'; break;
				case 'b': $style_franja .= 'bottom: 0px'; break;
			}
		}
		?>
				<div id="tema_prev_yuppic" class="tema_prev_yuppic center" style="<?php echo $style; ?>">

					<img src="<?php echo ($img_bg != ''? base_url($img_bg): ''); ?>" class="img_move_preview" 
						style="<?php echo $style_img; ?>">
					
					<br><br><br>
					<div class="bgtitulo" style="<?php echo $style_franja; ?>">
						<input id="tema_prev_titulo" class="titulo center tacenter" value="<?php echo (isset($theme_sel->title)? $theme_sel->title: '—— TÍTULO ——'); ?>" style="<?php echo $txt_color; ?>">
						<input id="tema_prev_autor" class="autor center tacenter" value="<?php echo (isset($theme_sel->author)? $theme_sel->author: 'Autor de Yuppic'); ?>" style="<?php echo $txt_color; ?>">
						<span class="fecha center"><?php echo String::fechaATexto(date("Y-m-d")); ?></span>
					</div>
				</div>

				<div class="accordion" id="accordion2">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_img">Cambiar imagen de fondo</a>
						</div>
						<div id="collapse_img" class="accordion-body collapse">
							<div class="accordion-inner">
								<input type="hidden" name="font_cover" id="font_cover" value="<?php echo (isset($theme_sel->font_cover)? $theme_sel->font_cover: ''); ?>">
								<input type="hidden" name="background_franja" id="background_franja" value="<?php echo (isset($theme_sel->background_franja)? $theme_sel->background_franja: ''); ?>">
								<input type="hidden" name="background_franja_color" id="background_franja_color" value="<?php echo (isset($theme_sel->background_franja_color)? $theme_sel->background_franja_color: ''); ?>">
								<input type="hidden" name="background_franja_position" id="background_franja_position" value="<?php echo (isset($theme_sel->background_franja_position)? $theme_sel->background_franja_position: ''); ?>">

								<form action="<?php echo base_url('yuppics/theme_image'); ?>" id="tema_frm_imagen" method="post" enctype="multipart/form-data">
									<label for="pattern_imagen_fondo">Repetir imagen 
										<input type="checkbox" id="pattern_imagen_fondo" value="si" <?php echo (isset($theme_sel->bg_pattern)? ($theme_sel->bg_pattern=='1'? 'checked': ''): ''); ?>></label>

									<input type="hidden" id="path_imagen_fondo" value="<?php echo (isset($theme_sel->background_img)? $theme_sel->background_img: ''); ?>">
									<label for="imagen_fondo pull-left">Subir imagen</label>
									<input type="file" name="imagen_fondo" id="imagen_fondo"><br>
									<button type="submit" class="btn btn-primary">Cargar</button>
									<button type="button" id="remove_imagesel" class="btn btn-danger">Quitar</button>
								</form>

								<form action="<?php echo base_url('yuppics/theme_franja_img'); ?>" id="tema_frm_franja" method="post" enctype="multipart/form-data">
									<label for="imagen_franja pull-left">Subir imagen de la franja</label>
									<input type="file" name="imagen_franja" id="imagen_franja"><br>
									<button type="submit" class="btn btn-primary">Cargar</button>
									<button type="button" id="remove_image_franja" class="btn btn-danger">Quitar</button>
								</form>

								<label for="imagen_franja pull-left">Posición de la franja</label>
								<select name="posicion_franja" id="posicion_franja">
									<option value="c">Centrado</option>
									<option value="t">Arriba</option>
									<option value="b">Abajo</option>
								</select>

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
										<input type="text" id="color_fondo" name="color_fondo" value="<?php echo (isset($theme_sel->background_color)? str_replace('#', '', $theme_sel->background_color): 'cccccc'); ?>">
										<span id="minibox_color_fondo" style="cursor: pointer; background-color: <?php echo (isset($theme_sel->background_color)? $theme_sel->background_color: '#cccccc'); ?>;"></span>
									</div>
								</div>
								<div>
									<div class="span6" style="text-align: left;color: #5c5c67;">Color de texto</div>
									<div class="span6">
										<input type="text" id="color_texto" name="color_texto" value="<?php echo (isset($theme_sel->text_color)? str_replace('#', '', $theme_sel->text_color): '555555'); ?>">
										<span id="minibox_color_texto" style="cursor: pointer; background-color: <?php echo (isset($theme_sel->text_color)? $theme_sel->text_color: '#555555'); ?>;"></span>
									</div>
								</div>
								<div>
									<div class="span6" style="text-align: left;color: #5c5c67;">Color de la franja</div>
									<div class="span6">
										<input type="text" id="color_franja" name="color_franja" value="<?php echo (isset($theme_sel->background_franja_color)? str_replace('#', '', $theme_sel->background_franja_color): '555555'); ?>">
										<span id="minibox_color_franja" style="cursor: pointer; background-color: <?php echo (isset($theme_sel->background_franja_color)? $theme_sel->background_franja_color: '#555555'); ?>;"></span>
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