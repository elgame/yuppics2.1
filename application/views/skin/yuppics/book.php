	<div class="span12 progress_yuppic"><!-- START PROGRESS BAR -->
		<div class="pross_yupp_conte">
			<div class="progress_bg">
				<div class="progress" id="progressbar_yuppic">
				  <div class="bar" style="width: -1%;" 
			  		data-progress="<?php echo (isset($status->progress)? $status->progress: '100'); ?>"></div>
				</div>
				<span class="paso1"></span>
				<span class="circl1"></span>
				<span class="paso2"></span>
				<span class="circl2"></span>
				<span class="paso3"></span>
				<span class="circl3"></span>
			</div>
			<p class="txtpaso1"><a href="<?php echo base_url('yuppics'); ?>" class="muted">Seleccionar tema</a></p>
			<p class="txtpaso2"><a href="<?php echo base_url('yuppics/photos') ?>" class="muted">Seleccionar fotografias</a></p>
			<p class="txtpaso3">Creación de Yuppic</p>
			<div class="clearfix"></div>
		</div>
	</div><!-- END PROGRESS BAR -->
	

	<div class="row-fluid"> <!-- START BARRA TOP -->
		<div class="span12 barratop">
			<div class="tema_barratop no-box-shadow">
				<div class="span4" style="line-height: 42px;font-size: 17px;color: #F4F4F5;">
          <div class="bullet" style="margin-left: 28px;"></div>Creación Photo Book
        </div>
				
				<div class="span8">
					<div class="pull-right">
						<div class="input-append ir_pasos pull-right hover-blue">
							<button class="btn btn-success" id="finalizarCompra">Finalizar compra</button>
						</div>
						<div class="input-append ir_pasos pull-right hover-blue" style="margin-left: 25px;">
	            <a href="<?php echo base_url('yuppics/photos'); ?>" id="btn_select_theme2" class="btn textura"><i class="icon-left"></i> Paso anterior</a>
	          </div>

						<div class="input-append ir_pasos pull-right hover-blue">
							<button class="btn textura" id="deletePage">Eliminar página</button>
						</div>

						<div class="input-append ir_pasos pull-right hover-blue">
							<button class="btn textura" id="magicPage">Magia Página</button>
						</div>
						<div class="input-append ir_pasos pull-right hover-blue">
							<button class="btn textura" id="magicBook">Magia Book</button>
						</div>

					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div> <!-- END BARRA TOP -->

	<div class="row-fluid contenido_crea_yupp">
		<div class="span4 barrpreview bg-albums" style="padding: 0 30px;">

			<div class="accordion" id="accordion2" style="margin-top: 20px;">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-parent="#accordion2" href="#" style="text-decoration: none;cursor: default;">Acomodación de Imágenes por Página</a>
					</div>
					<div id="collapse_img" class="accordion-body collapse in">
						<div class="accordion-inner">
							
							<div class="scroll-pane frames">
								<ul class="thumbnails" style="width: <?php echo (isset($pages)? count($pages)*83: 83); ?>px;">
							<?php
							if (isset($pages)) {
								foreach ($pages as $key => $value) {
							 ?>
									<li class="prevv">
										<a href="javascript:void(0);" class="thumbnail prev_pag" data-id="<?php echo $value->id_page; ?>"
											data-info='<?php echo json_encode($value->images); ?>'>
											<img src="<?php echo base_url($value->url_preview); ?>">
											<span class="hover_bg_prev_pag"></span>
										</a>
									</li>
							<?php
								}
							} ?>
								</ul>
							</div>

						</div>
					</div>
				</div>

			</div>


			<div class="accordion" id="accordion3">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-parent="#accordion3" href="#" style="text-decoration: none;cursor: default;">Elige un estilo de marco</a>
					</div>
					<div id="collapse_img1" class="accordion-body collapse in">
						<div class="accordion-inner">
							
							<div class="scroll-pane frames fborder">
								<ul class="thumbnails" style="width: <?php echo (isset($frames)? count($frames)*90: 90); ?>px;">
							<?php
							if (isset($frames)) {
								foreach ($frames as $key => $value) {
							 ?>
									<li class="prevv">
										<a href="javascript:void(0);" class="thumbnail frame_photo" data-id="<?php echo $value->id_frame; ?>">
											<img src="<?php echo base_url($value->url_preview); ?>">
											<span class="hover_bg_prev_pag"></span>
										</a>
										<!-- <p class="center"><?php echo $value->name; ?></p> -->
									</li>
							<?php
								}
							} ?>
								</ul>
							</div>

						</div>
					</div>
				</div>

			</div>
			

		</div><!-- /span4 -->

		<!-- Lista de temas -->
		<div class="span8 lista_temas lista_photos" id="thmslists" style="position: relative; min-height: 478px;">
			<div class="row-fluid bar-white"> <!-- START ROW FLUID -->
				<div class="span12">

          <i class="icon-select-photo" style="margin-left: 15px;"></i>
          <span style="font-weight:bold;"><?php echo $theme_sel->title; ?></span>
          <i class="big-arrow-right arrowbook"></i>

          <span id="barratop_album" style="margin-left: 30px;">Página: 
          	<span id="barratop_pagina" class="badge badge-success badge-total-photos"><?php echo ($page!==false? $page->num_pag: '') ?></span></span>
          <!-- <span class="badge badge-success badge-total-photos" id="total-choose">0</span> -->

          <button type="button" class="btn pull-right btn-photos btn-pag-right" data-page="<?php echo ($page!==false? $page->num_pag+1: '') ?>" id="next_page_save"><i class="icon-right-type2 active"></i></button>
          <button type="button" class="btn pull-right btn-photos btn-pag-left" data-page="<?php echo ($page!==false? $page->num_pag-1: '') ?>" id="prev_page_save"><i class="icon-left-type2 active"></i></button>

          <button class="btn pull-right btn-photos" id="save_page_active">Guardar Página</button>

				</div>
			</div> <!-- END ROW FLUID -->

			<div class="row-fluid">
				<div class="span12" style="position: relative">
					<!-- <a id="prev_page_save" class="carousel-control left hide" href="#myCarousel" data-page="<?php echo ($page!==false? $page->num_pag-1: '') ?>" data-slide="prev">&lsaquo;</a> -->
					<input type="hidden" id="idpage" value="<?php echo ($page!==false? $page->id_page: '') ?>">
					<input type="hidden" id="id_ypage" value="<?php echo ($page!==false? $page->id_ypage: '') ?>">
					<input type="hidden" id="num_pag" value="<?php echo ($page!==false? $page->num_pag: '') ?>">

					<input type="hidden" id="id_yuppic" value="<?php echo $this->session->userdata('id_yuppics'); ?>">

					<input type="hidden" id="page_edited" value="false">

					<!-- <div id="conte_pag_active"> -->
						<div id="pag_active" class="center">
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
						<div class="pag_active_foot center"></div>
					<!-- </div> -->

					<!-- <a id="next_page_save" class="carousel-control right" href="#myCarousel" data-page="<?php echo ($page!==false? $page->num_pag+1: '') ?>" data-slide="next">&rsaquo;</a> -->
				</div>
			</div>


			<div class="row-fluid bar-white"> <!-- START ROW FLUID -->
				<div class="span12">

					<i class="icon-select-photo" style="margin-left: 15px;"></i>
          <span style="font-weight:bold;">Fotografías Seleccionadas</span>
          <span class="badge badge-success badge-total-photos" id="total-choose"><?php echo (isset($photos)? count($photos): 0) ?></span>

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
                        <div class="thumbnail setphoto" data-info='<?php echo json_encode($p); ?>'>
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