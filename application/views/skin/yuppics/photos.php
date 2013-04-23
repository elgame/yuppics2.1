	<div class="span12 progress_yuppic"><!-- START PROGRESS BAR -->
		<div class="pross_yupp_conte">
			<div class="progress_bg">
				<div class="progress" id="progressbar_yuppic">
				  <div class="bar" style="width: -1%;"
		  			data-progress="<?php echo (isset($status->progress)? $status->progress: '0'); ?>"></div>
				</div>
				<span class="paso1"></span>
				<span class="circl1"></span>
				<span class="paso2" style="display: none;"></span>
				<span class="circl2" style="display: none;"></span>
			</div>
			<p class="txtpaso1"><a href="<?php echo base_url('yuppics'); ?>">Seleccionar tema</a></p>
			<p class="muted txtpaso2">Seleccionar fotografias</p>
			<p class="muted txtpaso3"><a href="#modal_upload" data-toggle="modal" data-target="#modal_upload" id="modal">Creación de Yuppic</a></p>
			<div class="clearfix"></div>
		</div>
	</div><!-- END PROGRESS BAR -->


<div class="container-fluid"> <!-- START CONTAINER FLUID -->
	<div class="row-fluid"> <!-- START ROW FLUID -->
		<div class="span3"> <!-- START SPAN3 -->
			<div class="span12 tema_barratop"><i class="icon-book"></i> Albums</div>
			<div class="span12 albums-list scroll-pane">
				<ul class="nav nav-tabs nav-stacked" id="albums">
          <input type="hidden" value="<?php echo $max_fotos?>" id="mf">
					<input type="hidden" value="<?php echo $access_token?>" id="at">
					<?php if(isset($albums)){
							foreach ($albums as $k => $v) {?>
								<li><a href="javascript:album(<?php echo '\''.$v->id.'\''; ?>)"><?php echo $v->name ?></a></li>
					<?php }} ?>
          <li><a href="javascript:album('all')">Mostrar todas las fotos</a></li>
				</ul>
			</div>
		</div> <!-- END SPAN3 -->
		<div class="span9"> <!-- START SPAN9 -->
			<div class="row-fluid"> <!-- START ROW FLUID -->
				<div class="span12 tema_barratop">
					<i class="icon-th"></i> Album > <span id="barratop_album">Mostrar todas las fotos</span>
					<button class="btn btn-success btn-mini pull-right" id="selectall">Seleccionar todo</button>
				</div>
			</div> <!-- END ROW FLUID -->
			<div class="row-fluid"> <!-- START ROW FLUID -->
				<div class="span12 photos-list">
					<ul class="thumbnails" id="photos-list"><!-- START LISTADO FOTOS FB -->
	        </ul><!-- END LISTADO FOTOS FB -->
	        <div class="pagination center">
              <button type="button" class="btn btn-warning" data-prev="" id="btn-prev" disabled>Prev</button>
              <button type="button" class="btn btn-success" data-next="" id="btn-next" disabled>Next</button>
              <i></i>
          </div>
				</div>
			</div> <!-- END ROW FLUID -->
			<div class="row-fluid"> <!-- START ROW FLUID -->
				<div class="span12 tema_barratop">
					<i class="icon-picture"></i> Fotos Seleccionadas <span class="badge badge-success" style="margin-left:5px;" id="total-choose"><? echo $totalp;?></span>
					<button class="btn btn-danger btn-mini pull-right" id="removeall">Remover todo</button>
				</div>
			</div> <!-- END ROW FLUID -->
			<div class="row-fluid"> <!-- START ROW FLUID -->
				<div class="span12 photos-select" style="height:170px;">
					<div class="scroll-pane horizontal-only">
						<div style="width: <?php echo (isset($width) ? $width : '0').'px';?>" id="content-selected-photos">
							<ul class="thumbnails">
								<?php if (isset($photos)){
    										foreach($photos as $k => $p) {?>
    											<li class="span2 relative">
    												<div class="thumbnail">
    													<img alt="" src="<?php echo base_url($p->url_thumb)?>">
    												</div>
    												<button type="button" class="close delete" data-id="<?php echo $p->id_photo?>" data-exist="true" title="Eliminar" id="delete">×</button>
    											</li>
								<?php }}?>
					        </ul>

                  <form id="form" style="width: 1px;margin: 0px;">
                      <?php if (isset($photos)){
                              foreach($photos as $k => $p) {?>
                                <input type="hidden" name="photos[]" value="false" id="<?php echo $p->id_photo ?>" class="src-<?php echo $p->id_photo ?> ori">
                       <?php }}?>
                  </form>
					    </div>
					</div>
				</div>
			</div> <!-- END ROW FLUID -->

			<p style="margin-bottom: 20px;">
				<a href="<?php echo base_url('yuppics'); ?>" class="pull-left"><i class="icon-arrow-left"></i> Paso anterior</a>

				<a href="#modal_upload" id="modal" class="pull-right"data-toggle="modal" data-target="#modal_upload">Siguiente paso <i class="icon-arrow-right"></i></a>
				<div class="clearfix"></div>
			</p>

		</div> <!-- END SPAN9 -->
	</div> <!-- END ROW FLUID -->
</div> <!-- END CONTAINER FLUID -->

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