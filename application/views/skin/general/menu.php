
			<div class="span3 main-menu-span"><!-- left menu starts -->

        <!-- <div class="search">
          <form action="<?php echo base_url('customer/'); ?>" method="GET" class="form-search">
              <input type="text" name="search" class="input-small search-query input-search" id="search" placeholder="Realizar Búsqueda...">
              <button type="submit" class="btn btn-search"><i class="yup-icon-search"></i></button>
              <div class="clearfix"></div>
          </form>
        </div> -->

        <div class="well menu-user-info">
        	<div class="title_userinfo"><i class="icon-titleinfo"></i> Información Básica</div>
          <div class="user-info">
            <div class="user-avatar"><img src="<?php echo base_url($info_customer->url_avatar); ?>"></div>
            <span class="user-name"><?php echo $info_customer->first_name.' '.$info_customer->last_name; ?></span><br>
            <span class="user-email"><?php echo $info_customer->email; ?></span>
            <div style="clear: both;"></div>
          </div>
          <div class="user-subinfo">
            <span class="infoperso">Modifica tu información Personal</span>
            <a class="editbtn" href="<?php echo base_url('customer/perfil'); ?>">EDITAR</a>
            <div style="clear: both;"></div>
          </div>
          <div class="info-stats">
            <div class="pull-left">
              <span class="circlecanti"><?php echo $info_dash->purchases; ?></span>
              <span class="status">Comprados</span>
            </div>
            <div class="pull-left">
              <span class="circlecanti"><?php echo (is_array($carrito_compra)? count($carrito_compra): 0); ?></span>
              <span class="status">Pendientes</span>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>


				<div class="well nav-collapse sidebar-nav bg-black">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<!-- <li class="nav-header hidden-tablet">Menú</li> -->

						<li>
								<a class="ajax-link" href="<?php echo base_url(); ?>">
									<i class="icon-mhome"></i> <span class="hidden-tablet"> Dashboard</span> 
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('customer/perfil'); ?>">
									<i class="icon-mperfil"></i> <span class="hidden-tablet"> Perfil</span> 
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('history/'); ?>">
									<i class="icon-mhisto"></i> <span class="hidden-tablet"> Historial</span> 
								</a>
						</li>
						<li>
								<a href="#modal_newsletter" class="ajax-link" id="newsletterm" data-toggle="modal" data-target="#modal_newsletter">
									<i class="icon-mnews"></i> <span class="hidden-tablet"> Newsletter</span> 
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('faq/'); ?>">
									<i class="icon-mfaqs"></i> <span class="hidden-tablet"> FAQ</span> 
								</a>
						</li>
						<li>
								<a href="#modal_contact" class="ajax-link" id="contact" data-toggle="modal" data-target="#modal_contact">
									<i class="icon-mconta"></i> <span class="hidden-tablet"> Contacto</span> 
								</a>
						</li>

					</ul>
				</div><!--/.well -->
			</div><!--/span-->
			<!-- left menu ends -->

			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>


