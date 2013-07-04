		<?php 
		if (isset($themes)) {
			if (is_array($themes)) {
				foreach ($themes as $key => $value) {
					$a = explode('/', $value->background_img);
					$b = explode('.', $a[count($a)-1]);
					unset($a[count($a)-1]);
					$thume = implode('/', $a).'/'.$b[0].'_thumb.'.$b[1];
					
					if (($key % 2) == 0)
						echo '
						<div class="row-fluid">';
		 ?>
							<div class="span6">
								<div class="well">
									<img src="<?php echo base_url($value->background_img); ?>" class="img-rounded">
									<h4><?php echo $value->name; ?></h4>
									<button class="btn btn-success use-theme" data-img="<?php echo $value->background_img; ?>" 
										data-imgthum="<?php echo $thume; ?>" 
										data-colorfondo="<?php echo $value->background_color; ?>" 
										data-colortexto="<?php echo $value->text_color; ?>" 
										data-backgroundfranja="<?php echo $value->background_franja; ?>" 
										data-backgroundfranjacolor="<?php echo $value->background_franja_color; ?>" 
										data-backgroundfranjaposition="c" 
										data-fontcover="<?php echo $value->font_cover; ?>">Usar tema</button>
									<div class="them_autrr">
										Creado por <strong><?php echo $value->autor; ?></strong>
									</div>
								</div>
							</div>
		<?php 
					if (($key % 2) == 1)
						echo '
						</div><!-- /row -->';
				}
			}else
				$noresult = true;
		}else
			$noresult = true;

		if (isset($noresult)) {
			echo '<div class="well no-resultados">No se encontraron resultados.</div>';
		}
		?>
