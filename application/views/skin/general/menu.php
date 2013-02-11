      <!-- left menu starts -->
			<div class="span2 main-menu-span">

        <div class="search">
          <form class="form-search">
              <input type="text" class="input-small search-query input-search" id="search" placeholder="Realizar Búsqueda...">
              <button type="submit" class="btn btn-search"><i class="yup-icon-search"></i></button>
          </form>
        </div>

        <div class="well menu-user-info">
          <div class="user-info">
            <div class="user-avatar"><img src="<?php echo base_url($info_customer->url_avatar); ?>"></div>
            <span class="user-name"><?php echo $info_customer->first_name.' '.$info_customer->last_name; ?></span>
            <span class="user-email"><?php echo $info_customer->email; ?></span>
          </div>
          <div class="info-stats">
            <div>
              <span><?php echo $info_dash->purchases; ?></span>
              <span class="status">Comprados</span>
            </div>
            <div>
              <span><?php echo (is_array($carrito_compra)? count($carrito_compra): 0); ?></span>
              <span class="status">Pendientes</span>
            </div>
          </div>
        </div>


				<div class="well nav-collapse sidebar-nav bg-black">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<!-- <li class="nav-header hidden-tablet">Menú</li> -->

						<li>
								<a class="ajax-link" href="<?php echo base_url(); ?>">
									<span class="hidden-tablet"> Dashboard</span> <i class="icon-mhome"></i>
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('customer/perfil'); ?>">
									<span class="hidden-tablet"> Perfil</span> <i class="icon-mperfil"></i>
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('history/'); ?>">
									<span class="hidden-tablet"> Historial</span> <i class="icon-mhisto"></i>
								</a>
						</li>
						<li>
								<a href="#modal_newsletter" class="ajax-link" id="newsletterm" data-toggle="modal" data-target="#modal_newsletter">
									<span class="hidden-tablet"> Newsletter</span> <i class="icon-mnews"></i>
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('faq/'); ?>">
									<span class="hidden-tablet"> FAQ</span> <i class="icon-mfaqs"></i>
								</a>
						</li>
						<li>
								<a href="#modal_contact" class="ajax-link" id="contact" data-toggle="modal" data-target="#modal_contact">
									<span class="hidden-tablet"> Contacto</span> <i class="icon-mconta"></i>
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


