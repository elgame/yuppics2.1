	<div class="span12 progress_yuppic"><!-- START PROGRESS BAR -->
		<div class="pross_yupp_conte">
			<div class="progress_bg">
				<div class="progress" id="progressbar_yuppic">
				  <div class="bar" style="width: -1%;"
		  			data-progress="<?php echo (isset($status->progress)? $status->progress: '0'); ?>"></div>
				</div>
				<span class="paso1"></span>
				<span class="circl1"></span>
				<span class="paso2"></span>
				<span class="circl2" style="display: none;"></span>
        <span class="paso3"></span>
			</div>
			<p class="txtpaso1"><a href="<?php echo base_url('yuppics'); ?>" class="muted">Seleccionar tema</a></p>
			<p class="txtpaso2">Seleccionar fotografias</p>
			<p class="muted txtpaso3"><a href="#modal_upload" class="muted" data-toggle="modal" data-target="#modal_upload" id="modal">Creación de Yuppic</a></p>
			<div class="clearfix"></div>
		</div>
	</div><!-- END PROGRESS BAR -->


  <div class="row-fluid">
    <div class="span12 barratop">
      <div class="tema_barratop no-box-shadow">
        <div class="pull-left" style="line-height: 42px;font-size: 17px;margin-left: 28px;color: #F4F4F5;">
          <div class="bullet"></div>Seleccción de Fotografías
        </div>

        <div class="pull-right">
          <div class="input-append ir_pasos pull-right hover-blue">
            <a href="#modal_upload" id="modal" class="btn textura" data-toggle="modal" data-target="#modal_upload">Siguiente paso <i class="icon-right"></i></i></a>
          </div>

          <div class="input-append ir_pasos pull-right hover-blue">
            <a href="<?php echo base_url('yuppics'); ?>" id="btn_select_theme2" class="btn textura"><i class="icon-left"></i> Paso anterior</a>
          </div>
        </div>

        <div class="clearfix"></div>
      </div>
    </div>
  </div>


	<div class="row-fluid contenido_crea_yupp" id="content-album-photos"> <!-- START ROW FLUID -->

		<div class="span4 barrpreview"> <!-- START SPAN3 -->
			<div class="span12 bg-albums" id="content-albums">
        <div class="span12" style="font-size: 17px;font-weight: bold;color: #3B3F46;padding: 38px 0 15px 55px;">Lista de Albums</div>

        <div class="scroll-pane albums-list">
          <ul class="nav nav-tabs nav-stacked hover-blue-albums" id="albums">
            <input type="hidden" value="<?php echo $max_fotos?>" id="mf">
            <input type="hidden" value="<?php echo $access_token?>" id="at">

            <?php if(isset($albums)){
                foreach ($albums as $k => $v) {?>
                  <li><a href="javascript:album(<?php echo '\''.$v->id.'\''; ?>)"><i class="icon-album"></i><span><?php echo $v->name ?></span></a></li>
            <?php }} ?>
            <!-- <li><a href="javascript:album('all')">Mostrar todas las fotos</a></li> -->
          </ul>
        </div>

			</div>
		</div> <!-- END SPAN3 -->

		<div class="span8 lista_temas lista_photos" id="thmslists" style="position: relative;"> <!-- START SPAN9 -->

			<div class="row-fluid bar-white"> <!-- START ROW FLUID -->
				<div class="span12">

          <i class="icon-select-photo" style="margin-left: 15px;"></i>
          <span style="font-weight:bold;">Album</span>
          <i class="big-arrow-right"></i>

          <span id="barratop_album" style="margin-left: 30px;">Ningún album seleccionado</span>
          <!-- <span class="badge badge-success badge-total-photos" id="total-choose">0</span> -->

          <button type="button" class="btn pull-right btn-photos btn-pag-right" data-next="" id="btn-next" disabled><i class="icon-right-type2 active"></i></button>
          <button type="button" class="btn pull-right btn-photos btn-pag-left" data-prev="" id="btn-prev" disabled><i class="icon-left-type2 active"></i></button>

          <button class="btn pull-right btn-photos" id="selectall">Seleccionar todo</button>

				</div>
			</div> <!-- END ROW FLUID -->

			<div class="row-fluid"> <!-- START ROW FLUID -->
        <div class="span1" style="width: 4.3% !important;"></div>
				<div class="span11 photos-list" style="position: relative; height: 150px;margin-top: 30px; margin-bottom: 30px;">

          <!-- START LISTADO FOTOS FB -->
          <ul class="thumbnails" id="photos-list" style="height: 130px; text-align:center; margin-top: 0px;">

            <span style="display:block; text-align:center; margin-top: 7%; color:#7B4F28; font-weight:bolder;">
              Para comenzar Selecciona alguna categoría de la Lista de Albums
            </span>

          </ul><!-- END LISTADO FOTOS FB -->

				</div>
			</div> <!-- END ROW FLUID -->

			<div class="row-fluid bar-white"> <!-- START ROW FLUID -->
				<div class="span12">

					<i class="icon-select-photo" style="margin-left: 15px;"></i>
          <span style="font-weight:bold;">Fotografías Seleccionadas</span>
          <span class="badge badge-success badge-total-photos" id="total-choose"><? echo $totalp;?></span>

          <button type="button" class="btn pull-right btn-photos btn-pag-right" data-next="" id="btn-next-scroll"><i class="icon-right-type2 active"></i></button>
          <button type="button" class="btn pull-right btn-photos btn-pag-left" data-prev="" id="btn-prev-scroll"><i class="icon-left-type2 active"></i></button>

          <button class="btn pull-right btn-photos" id="removeall">Remover todo</button>

        </div>
			</div> <!-- END ROW FLUID -->

			<div class="row-fluid"> <!-- START ROW FLUID -->

        <div class="span1"></div>
        <div class="span10 myClass photos-select" id="content-selected-photos" style="width: 85.6% !important;">

          <ul class="thumbnails" style="margin-top: 8px">
            <?php if (isset($photos)){
                    foreach($photos as $k => $p) {?>
                      <li class="span2 relative">
                        <div class="thumbnail">
                          <img alt="" src="<?php echo base_url($p->url_thumb)?>">
                        </div>
                        <button type="button" class="close delete" data-id="<?php echo $p->id_photo?>" data-exist="true" title="Eliminar" id="delete"></button>
                      </li>
            <?php }} else {?>
                    <span id="txt-msg-2"style="display:block; text-align:center; margin-top: 6%; color:#7B4F28; font-weight:bolder;">No hay Fotografías seleccionas aún</span>
            <?php } ?>
          </ul>

          <form id="form" style="width: 1px;margin: 0px;">
              <?php if (isset($photos)){
                      foreach($photos as $k => $p) {?>
                        <input type="hidden" name="photos[]" value="false" id="<?php echo $p->id_photo ?>" class="src-<?php echo $p->id_photo ?> ori">
               <?php }}?>
          </form>

        </div>



			</div> <!-- END ROW FLUID -->

		</div> <!-- END SPAN9 -->
	</div> <!-- END ROW FLUID -->

<div id="modal_upload" class="modal hide fade span7" style="left: 20%;"><!-- START modal contacto -->
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h2 id="myModalLabel1" style="display: inline;">Fotos Seleccionadas</h2><span class="muted">
    Espera mientras se suben las fotos que has seleccionado.</span>
    <button type="button" class="btn btn-primary disabled" id="save_photos" disabled>Upload</button>
  </div>
  <div class="modal-body">
    <ul class="thumbnails">
    </ul>
  </div>
  <div class="modal-footer center" style="background-color: #fff;">
    <h3 class="muted" id="txt-msg"></h3>
    <span class="muted"></span>
  </div>
</div><!-- END modal contacto -->


<div id="messajes_alerts" class="modal hide fade">
  <div class="modal-body">
    <p></p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Ok</a>
  </div>
</div>