		<div id="content" class="span7 mtop-content">
			<!-- content starts -->

			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body unit-foo">
						<h3>Lista de Preguntas y respuestas frecuentes</h3>
						<p>Si tienes alguna duda contemplada que no venga dentro de esta lista de preguntas, puedes hacernos llegar un mensaje y ponerte en contacto con
								nosotros mediante nuestro formulario de <a href="#" title="Contacto" data-toggle="modal" data-target="#modal_contact">contacto</a></p>
					</div>
				</div>
			</div>


			<h2 class="myriad" style="color:#464f55;">FAQ</h2>

			<div class="row-fluid">
					<div class="box span12">

            <div class="box-header well">
              <h2>Preguntas</h2>

              <!-- <div class="box-icon pull-right">
                <div class="btn-group">
                  <a href="#" class="btn btn-white btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
              </div> -->
            </div>

            <div class="box-content" style="padding: 0 !important;">
  						<div class="accordion accord_barr_rig" id="faq-accordion">
          <?php 
            if (is_array($all_faqs)) {
              foreach ($all_faqs as $key => $value) {
           ?>

  						  <div class="accordion-group">
  						    <div class="accordion-heading">
  						      <a class="accordion-toggle" data-toggle="collapse" data-parent="#faq-accordion" href="#collapse<?php echo $key; ?>">
  						        <i class="icon-faq-point"></i> <?php echo $value->question; ?>
  						      </a>
  						    </div>
  						    <div id="collapse<?php echo $key; ?>" class="accordion-body collapse">
  						      <div class="accordion-inner">
  						        <?php echo $value->response; ?>
  						      </div>
  						    </div>
  						  </div>
          <?php 
            }
          } ?>
  						</div>
            </div>
					</div><!--/span-->
			</div><!--/row-->

	</div><!--/#content.span8-->


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