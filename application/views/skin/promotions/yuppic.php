		<div id="content" class="span8">
			<!-- content starts -->
			
			<div>
				<ul class="breadcrumb">
					<li>
						<a href="<?php echo base_url(); ?>">Inicio</a> <span class="divider">/</span>
					</li>
					<li>
						Promociones
					</li>
				</ul>
			</div>

			<div class="row-fluid">
				<div class="hero-unit">
					<h3>Gana un Yuppic Gratis en cuestión de segundos!</h3>
					<p>Gana un yuppic completamente gratis con tan solo seguir los siguientes pasos. <br>
						Cada actividad de la lista forma parte de un porcentaje para ganar un yuppic</p>
				</div>
			</div>
					
			
			<div class="row-fluid">
					<div class="span12">
						
						<div style="background-color: #fff; padding: 3% 4% 1% 4%;">
							<div class="progress">
							  <div class="bar" style="width: 0%;"></div>
							</div>
							<p class="muted pull-left">0%</p>
							<p class="muted pull-left" style="margin-left: 48%;">50%</p>
							<p class="muted pull-right">100%</p>
							<div class="clearfix"></div>
						</div>

						<div id="fb-root"></div>
						<script type="text/javascript">
							var fb_app_id = <?php echo $fb_app_id; ?>
						</script>

						<table class="table table-condensed">
							<tr id="fb_comparte_link">
								<td>
									<input type="checkbox" style="margin: 20px 0 0 20px;">
								</td>
								<td>
									<h3 class="muted">Comparte nuestro enlace en tu muro!</h3>
									<p>Comparte en enlace o direccion de nuestra app vía  facebook</p>
								</td>
							</tr>
							<tr id="fb_invita_link">
								<td>
									<input type="checkbox" style="margin: 20px 0 0 20px;">
								</td>
								<td>
									<h3 class="muted">Invita a tus amigos a crear sus propios Photobooks</h3>
									<p>Invita a tus amigos vía facebook a utilizar nuestra app</p>
								</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" style="margin: 20px 0 0 20px;">
								</td>
								<td>
									<h3 class="muted">Tweetea tu amor por Yuppics</h3>
									<p>Comparte tu amor por yuppics vía twitter</p>
								</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" style="margin: 20px 0 0 20px;">
								</td>
								<td>
									<h3 class="muted">Proporcionanos feedback</h3>
									<p>Todas tus ideas y sugerencias nos son esenciales.</p>
								</td>
							</tr>
						</table>

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