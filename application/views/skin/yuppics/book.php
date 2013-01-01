	<div class="span12">
		<div style="background-color: #fff; padding: 1% 4% 0% 4%;">
			<div class="progress">
			  <div class="bar" style="width: <?php echo (isset($status->progress)? $status->progress: '100'); ?>%;"></div>
			</div>
			<p class="muted pull-left"><a href="<?php echo base_url('yuppics'); ?>">Seleccionar tema</a></p>
			<p class="muted pull-left" style="margin-left: 39%;"><a href="<?php echo base_url('yuppics/photos') ?>">Seleccionar fotografias</a></p>
			<p class="muted pull-right">Creación de Yuppic</p>
			<div class="clearfix"></div>
		</div>
	</div>

	<div class="row-fluid"> <!-- START BARRA TOP -->
		<div class="span12">
			<div class="tema_barratop">
				<div class="span4">
					<span class="pull-left"><i class="icon-book"></i> Estilos marcos</span>
				</div>
				<div class="span8">
					<div class="pull-left"><i class="icon-th"></i> Albums > <span id="barratop_album">Mostrar todas las fotos</span></div>
					<div class="pull-right"><button class="btn btn-success" id="selectall">Seleccionar todo</button></div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div> <!-- END BARRA TOP -->

	<div class="row-fluid">
		<div class="span4">
			<div class="scroll-pane frames">
				<ul class="thumbnails">
			<?php 
			if (isset($frames)) {
				foreach ($frames as $key => $value) {
			 ?>
					<li class="span4">
						<a href="javascript:void(0);" class="thumbnail frame_photo" data-id="<?php echo $value->id_frame; ?>">
							<img src="<?php echo base_url($value->url_preview); ?>"></a>
						<p class="center"><?php echo $value->name; ?></p>
					</li>
			<?php 
				}
			} ?>
				</ul>

			</div>

			<div class="yuppic_barratop">
				Acomodación de imágenes
			</div>
			<div class="scroll-pane frames">
				<ul class="thumbnails">
			<?php 
			if (isset($pages)) {
				foreach ($pages as $key => $value) {
			 ?>
					<li class="span4">
						<a href="javascript:void(0);" class="thumbnail prev_pag" data-id="<?php echo $value->id_page; ?>"
							data-info='<?php echo json_encode($value->images); ?>'>
							<img src="<?php echo base_url($value->url_preview); ?>"></a>
					</li>
			<?php 
				}
			} ?>
				</ul>
			</div>

		</div><!-- /span4 -->

		<!-- Lista de temas -->
		<div class="span8">

			<div class="row-fluid">
				<div class="span12" style="position: relative">
					<a id="" class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
					<input type="hidden" id="idpage" value="">
					<input type="hidden" id="id_ypage" value="">

					<div id="pag_active" class="well center">
						
					</div>

					<a id="nav_page_save" class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span12 tema_barratop">
					<div class="span3">
						<span class="pull-left"><i class="icon-picture"></i> Fotos Seleccionadas </span>
						<span class="badge badge-success" style="margin-left:5px;" id="total-choose"><?php echo (isset($photos)? count($photos): 0) ?></span>
					</div>
					<div class="span9">
						<div class="pull-right"><button class="btn btn-danger" id="removeall">Remover todo</button></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12 photos-select" style="height:180px;">
					<div class="scroll-pane horizontal-only">
						<!-- 165px cada imagen agregada min-width: 822px; -->
						<div style="width: 140%" id="content-selected-photos">
							<ul class="thumbnails">
						<?php 
						if (isset($photos)) {
						 	foreach ($photos as $key => $value) {
						?>
								<li class="span2 relative">
									<div class="thumbnail setphoto" data-info='<?php echo json_encode($value); ?>'>
										<img alt="" src="<?php echo base_url($value->url_thumb); ?>">
									</div>
									<button type="button" class="close delete" data-dismiss="alert" data-id="<?php echo $value->id_photo; ?>" title="Eliminar" id="delete">×</button>' +
								</li>
						<?php 
							}
						} ?>
					    </ul> 
					        <form action="<?php echo base_url()?>" method="POST" id="form"></form>
					  </div>
					</div>
				</div>
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