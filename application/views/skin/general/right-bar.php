	<div class="span3">
		<div class="mtop-content">
			<div class="row-fluid">
				<div class="span12">
					<div class="well well-small center create_yupps">
						<div class="cy_body">
							<h3 class="myriad brr_right">Comienza ahora!</h3>
							<a href="<?php echo base_url('yuppics'); ?>" class="btn btn-large mtop">Crear tu propio yuppics</a><br>
							<span class="cy_descrip">Imprime tus mejores recuerdos <br>
								por tan sólo <strong><?php echo String::formatoNumero($product_yuppic->price); ?> MXM</strong>
							</span>
						</div>

						<div class="cy_footer">
							Gana un <a href="<?php echo base_url('promotions'); ?>" class="link_green bold">yuppics gratis</a> en 4 simples pasos!
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="mtop">
			<h3 class="myriad">Preguntas Populares</h3>
			<div class="row-fluid">
				<div class="span12">
				<?php 
				$CI =& get_instance();
				$CI->load->model('faqs_model');
				$faqs_popular = $CI->faqs_model->getFaqs('popular = 1');
				 ?>
					<div class="accordion accord_barr_rig" id="faqssqs">
				<?php 
	        if (is_array($faqs_popular)) {
	          foreach ($faqs_popular as $key => $value) {
	       ?>
					  <div class="accordion-group">
					    <div class="accordion-heading">
					      <a class="accordion-toggle" data-toggle="collapse" data-parent="#faqssqs" href="#faqss_collapse<?php echo $key; ?>">
					        <i class="icon-ok"></i> <?php echo $value->question; ?>
					      </a>
					    </div>
					    <div id="faqss_collapse<?php echo $key; ?>" class="accordion-body collapse">
					      <div class="accordion-inner">
					        <?php echo $value->response; ?>
					      </div>
					    </div>
					  </div>
				<?php 
            }
          } ?>
					  
					</div><!-- /accordion -->

				</div>
			</div>
		</div>
	</div>