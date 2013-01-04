<div class="span12"> <!-- START PROGRESS BAR -->
	<div style="background-color: #fff; padding: 1% 4% 0% 4%;">
		<div class="progress">
		  <div class="bar" style="width: <?php echo (isset($status->progress)? $status->progress: '0'); ?>%;"></div>
		</div>
		<p class="muted pull-left"><a href="http://localhost/yuppics2.1/yuppics">Seleccionar tema</a></p>
		<p class="muted pull-left" style="margin-left: 39%;">Seleccionar fotografias</p>
		<p class="muted pull-right"><a href="javascript:void(0);" id="save_photos">Creación de Yuppic</a></p>
		<div class="clearfix"></div>
	</div>
</div> <!-- END PROGRESS BAR -->


<div class="container-fluid"> <!-- START CONTAINER FLUID -->
	<div class="row-fluid"> <!-- START ROW FLUID -->
		<div class="span3"> <!-- START SPAN3 -->
			<div class="span12 tema_barratop"><i class="icon-book"></i> Albums</div>
			<div class="span12 albums-list scroll-pane">
				<ul class="nav nav-tabs nav-stacked" id="albums">
					<input type="hidden" value="<?php echo $access_token?>" id="at">
					<li class="active"><a href="javascript:album('all')">Mostrar todas las fotos</a></li>
					<?php if(isset($albums)){
							foreach ($albums as $k => $v) {?>
								<li><a href="javascript:album(<?php echo '\''.$v->id.'\''; ?>)"><?php echo $v->name ?></a></li>
					<?php }} ?>
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
			        <div class="pagination"></div>
				</div>
			</div> <!-- END ROW FLUID -->
			<div class="row-fluid"> <!-- START ROW FLUID -->
				<div class="span12 tema_barratop">
					<i class="icon-picture"></i> Fotos Seleccionadas <span class="badge badge-success" style="margin-left:5px;" id="total-choose"><? echo $totalp;?></span>
					<button class="btn btn-danger btn-mini pull-right" id="removeall">Remover todo</button>
				</div>
			</div> <!-- END ROW FLUID -->
			<div class="row-fluid"> <!-- START ROW FLUID -->
				<div class="span12 photos-select">
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
					        <form id="form"></form>
					    </div>
					</div>
				</div>
			</div> <!-- END ROW FLUID -->
		</div> <!-- END SPAN9 -->
	</div> <!-- END ROW FLUID -->
</div> <!-- END CONTAINER FLUID -->

<div id="messajes_alerts" class="modal hide fade">
  <div class="modal-body">
    <p></p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Ok</a>
  </div>
</div>