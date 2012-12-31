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

<div class="row-fluid"> <!-- START BARRA TOP -->
	<div class="span12">
		<div class="tema_barratop">
			<div class="span3">
				<span class="pull-left"><i class="icon-book"></i> Albums</span>
			</div>
			<div class="span9">
				<div class="pull-left"><i class="icon-th"></i> Albums > <span id="barratop_album">Mostrar todas las fotos</span></div>
				<div class="pull-right"><button class="btn btn-success" id="selectall">Seleccionar todo</button></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div> <!-- END BARRA TOP -->


<div class="container-fluid">

	<div class="row-fluid">

		<div class="span3 albums">
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
		</div>	


		<div class="span9">
			<div class="row-fluid">
				<div class="span12 photos-list"> <!-- START LISTADO DE FOTOS -->
					<ul class="thumbnails" id="photos-list">
						<!-- <li class="span2 relative" id="1">
							<div class="thumbnail">
							  <img alt="" src="http://photos-h.ak.fbcdn.net/hphotos-ak-ash4/603047_10151026508457603_1039118208_s.jpg">
							  <div class="caption center">
							  	<span><strong id="thumbnail-title">En casita</strong></span>
							  </div>
							</div>
						</li> -->
			        </ul>
				</div> <!-- END LISTADO DE FOTOS -->
				<div class="pagination"></div>
			</div>
			<div class="row-fluid">
				<div class="span12 tema_barratop">
					<div class="span3">
						<span class="pull-left"><i class="icon-picture"></i> Fotos Seleccionadas </span>
						<span class="badge badge-success" style="margin-left:5px;" id="total-choose">0</span>
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
						<div style="width: 0px;" id="content-selected-photos">
							<ul class="thumbnails">
					        </ul> 
					        <form action="<?php echo base_url()?>" method="POST" id="form"></form>
					    </div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<div id="messajes_alerts" class="modal hide fade">
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" aria-hidden="true">Ok</a>
  </div>
</div>