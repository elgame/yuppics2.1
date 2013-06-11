		<div id="content" class="span7 mtop-content">
			<!-- content starts -->

			<div class="row-fluid">
				<div class="hero-unit unitwhite">
					<div class="unit-body">
						<h3 class="myriad">Bienvenido/a <?php echo $this->session->userdata('nombre'); ?>, estás viendo tu Dashboard</h3>
						<div class="row-fluid" style="color:rgba(74,74,74, .45);">
							<div class="span6">Total de Yuppics en envío: <?php echo $info_dash->purchases; ?></div>
							<div class="span6">Total de descuentos activos: 5</div>
						</div>
					</div>
					<div class="unit-footer">
						<p>Cambia tu dirección de envío o de facturación <a href="<?php echo base_url('customer/perfil/');?>" class="link_black">aquí</a></p>
					</div>
				</div>
			</div>


			<h2 class="myriad" style="color:#464f55;">Tus Yuppis</h2>

			<div class="row-fluid">
					<div class="span12">
						<!-- <div class="btn-group btn-sstyle">
							<button class="btn btn-large">Comprados</button>
							<button class="btn btn-large">Pendientes</button>
						</div> -->

            <div class="tabbable"> <!-- Only required for left/right tabs -->
              <div class="btn-group btn-sstyle">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab-pendientes" class="btn" data-toggle="tab">Pendientes</a></li>
                  <li><a href="#tab-comprados" class="btn" data-toggle="tab">Comprados</a></li>
                </ul>
              </div>
              <div class="tab-content">

                <div class="tab-pane active" id="tab-pendientes"> <!-- class="tab-pane active scroll-pane horizontal-only" -->
            <?php
              if (is_array($info_dash->listado2)) { ?>
                  <!-- 374 -->
                  <ul class="thumbnails thumbnails_yuppics"> <!-- style="width: <?php echo count($info_dash->listado2)*374; ?>px;" -->
              <?php
                foreach ($info_dash->listado2 as $key => $value) {
                  $style = '';
                  if ($value->bg_pattern == '1') {
                    $style = "background: url('".base_url($value->url_img)."') repeat;";
                  }
              ?>
                    <li class="span6" style="width: 360px;">
                      <div class="thumbnail">
                        <div style="<?php echo $style; ?>width: 360px; height: 200px;overflow: hidden;">
                      <?php if ($value->bg_pattern == '0') { ?>
                          <img alt="<?php echo $value->title; ?>" style="width: 360px; height: auto;" src="<?php echo base_url($value->url_img); ?>">
                      <?php } ?>
                        </div>
                        <div class="caption">
                          <h3><?php echo $value->title; ?></h3>
                          <p>Creado: <?php echo String::humanDate(strtotime($value->created)); ?></p>
                        </div>
                        <a class="shopcar" href="<?php echo base_url('yuppics/set_yuppic?yuppic='.$value->id_yuppic) ?>"></a>
                      </div>
                    </li>
          <?php } ?>
                  </ul>
        <?php } ?>
                </div>


                <div class="tab-pane" id="tab-comprados"> <!-- class="tab-pane scroll-pane horizontal-only" -->

              <?php
              if (is_array($info_dash->listado1)) { ?>
                  <!-- 374 -->
                  <ul class="thumbnails thumbnails_yuppics"> <!-- style="width: <?php echo count($info_dash->listado1)*374; ?>px;" -->
              <?php
                foreach ($info_dash->listado1 as $key => $value) {
              ?>
                    <li class="span6" style="width: 360px;">
                      <div class="thumbnail">
                        <div style="width: 360px; height: 200px;overflow: hidden;">
                          <img alt="<?php echo $value->title; ?>" style="width: 360px; height: auto;" src="<?php echo base_url($value->url_img); ?>">
                        </div>
                        <div class="caption">
                          <h3><?php echo $value->title; ?></h3>
                          <p>Creado: <?php echo String::humanDate(strtotime($value->created)); ?></p>
                        </div>
                        <a class="shopcar" href="<?php echo base_url('yuppics/set_yuppic?yuppic='.$value->id_yuppic) ?>"></a>
                      </div>
                    </li>
          <?php } ?>
                  </ul>
        <?php } ?>

                </div>

              </div>
            </div>



					</div><!--/span-->

			</div><!--/row-->




					<!-- content ends -->
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