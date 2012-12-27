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
									<img src="<?php echo base_url($value->background_img); ?>" class="img-rounded" width="400" height="300">
									<h4><?php echo $value->name; ?></h4>
									<button class="btn use-theme" data-img="<?php echo $value->background_img; ?>" 
										data-imgthum="<?php echo $thume; ?>" 
										data-colorfondo="<?php echo $value->background_color; ?>" 
										data-colortexto="<?php echo $value->text_color; ?>">Usar tema</button>
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
			echo '<div class="well">No se encontraron resultados.</div>';
		}
		?>
