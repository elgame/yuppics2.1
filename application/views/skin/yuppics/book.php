	<div class="span12">
		<div style="background-color: #fff; padding: 1% 4% 0% 4%;">
			<div class="progress" id="progressbar_yuppic">
			  <div class="bar" style="width: -4%;" 
			  		data-progress="<?php echo (isset($status->progress)? $status->progress: '100'); ?>"></div>
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
					<span class="pull-left"><i class="icon-book"></i> Acomodación de imágenes</span>
				</div>
				<div class="span8">
					<div class="pull-left"><i class="icon-th"></i> Página: <span id="barratop_pagina"><?php echo ($page!==false? $page->num_pag: '') ?></span></div>
					<div class="pull-right">
						<button class="btn" id="magicBook">Magia Book</button>
						<button class="btn" id="magicPage">Magia Página</button>

						<button class="btn btn-danger" id="deletePage">Eliminar página</button>
						<button class="btn btn-success" id="finalizarCompra">Finalizar compra</button>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div> <!-- END BARRA TOP -->

	<div class="row-fluid">
		<div class="span3">
			
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

			<div class="yuppic_barratop">
				<i class="icon-book"></i> Estilos marcos
			</div>
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

		</div><!-- /span4 -->

		<!-- Lista de temas -->
		<div class="span9">

			<div class="row-fluid">
				<div class="span12" style="position: relative">
					<a id="prev_page_save" class="carousel-control left hide" href="#myCarousel" data-page="<?php echo ($page!==false? $page->num_pag-1: '') ?>" data-slide="prev">&lsaquo;</a>
					<input type="hidden" id="idpage" value="<?php echo ($page!==false? $page->id_page: '') ?>">
					<input type="hidden" id="id_ypage" value="<?php echo ($page!==false? $page->id_ypage: '') ?>">
					<input type="hidden" id="num_pag" value="<?php echo ($page!==false? $page->num_pag: '') ?>">

					<input type="hidden" id="id_yuppic" value="<?php echo $this->session->userdata('id_yuppics'); ?>">

					<input type="hidden" id="page_edited" value="false">

					<div id="pag_active" class="well center">
						<?php
						if ($page !== false) {
							foreach ($page->images as $key => $value) {
								echo '<div class="img_in_page" style="top:'.$value->coord_y.'%;left:'.$value->coord_x.'%;width:'.$value->width.'%;height:'.$value->height.'%;"
									data-idimg="'.$value->id_img.'" data-idpagimg="'.$value->id_page_img.'" data-width="'.$value->width.'" data-height="'.$value->height.'"
									'.(isset($value->id_frame)? 'data-idframe="'.$value->id_frame.'"': '').' '.(isset($value->id_photo)? 'data-idphoto="'.$value->id_photo.'"': '').'>
									<div class="photo" style="top:'.$value->pos_y.'%;left:'.$value->pos_x.'%;">
										'.(isset($value->url_img)? '<img id="img_'.$value->id_img.$value->id_page_img.'" src="'.base_url($value->url_img).'">': '').'
									</div>
									<div class="frame">
										'.(isset($value->url_frame)? '<img src="'.base_url($value->url_frame).'">': '').'
									</div>
									<span class="live_aviary" data-id="img_'.$value->id_img.$value->id_page_img.'"><i class="icon-picture"></i></span>
									
								</div>';
							}
						}
						 ?>
					</div>

					<a id="next_page_save" class="carousel-control right" href="#myCarousel" data-page="<?php echo ($page!==false? $page->num_pag+1: '') ?>" data-slide="next">&rsaquo;</a>
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
				<div class="span12 photos-select" style="height:170px;">
					<div class="scroll-pane horizontal-only">
						<!-- 165px cada imagen agregada min-width: 822px; -->
						<div style="width: <?php echo (count($photos)*166); ?>px" id="content-selected-photos">
							<ul class="thumbnails">
            <?php if (isset($photos)) {
                foreach($photos as $k => $p) {?>
                  <li class="span2 relative">
                    <div class="thumbnail setphoto" data-info='<?php echo json_encode($p); ?>'>
                      <img alt="" src="<?php echo base_url($p->url_thumb)?>">
                    </div>
                    <button type="button" class="close delete" data-id="<?php echo $p->id_photo?>" data-exist="true" title="Eliminar" id="delete">×</button>
                  </li>
            <?php }
          		}?>
              </ul>

              <form id="form" style="width: 1px;margin: 0px;">
                  <?php if (isset($photos)) {
                    foreach($photos as $k => $p) {?>
                      <input type="hidden" name="photos[]" value="false" id="<?php echo $p->id_photo ?>" class="src-<?php echo $p->id_photo ?> ori">
                  <?php }}?>
              </form>
							
					  </div>
					</div>
				</div>
			</div>

			<p style="margin-bottom: 20px;">
				<a href="<?php echo base_url('yuppics/photos') ?>" class="pull-left"><i class="icon-arrow-left"></i> Paso anterior</a>
				<div class="clearfix"></div>
			</p>

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