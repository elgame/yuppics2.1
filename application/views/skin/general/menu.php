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
            <div class="user-avatar"><img src="<?php echo base_url(''); ?>"></div>
            <span class="user-name">Jorge Suarez Basañez</span>
            <span class="user-email">jorgefsb@gmail.com</span>
          </div>
          <div class="info-stats">
            <div>
              <span>103</span>
              <span class="status">Comprados</span>
            </div>
            <div>
              <span>53</span>
              <span class="status">Pendientes</span>
            </div>
          </div>
        </div>


				<div class="well nav-collapse sidebar-nav bg-black">
					<ul class="nav nav-tabs nav-stacked main-menu">
						<!-- <li class="nav-header hidden-tablet">Menú</li> -->

						<li>
								<a class="ajax-link" href="<?php echo base_url(); ?>">
									<span class="hidden-tablet"> Dashboard</span> <i class="icon-home"></i>
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('customer/perfil'); ?>">
									<span class="hidden-tablet"> Perfil</span> <i class="icon-user"></i>
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('history/'); ?>">
									<span class="hidden-tablet"> Historial</span> <i class="icon-signal"></i>
								</a>
						</li>
						<li>
								<a href="#modal_newsletter" class="ajax-link" id="newsletterm" data-toggle="modal" data-target="#modal_newsletter">
									<i class="icon-check"></i><span class="hidden-tablet"> Newsletter</span>
								</a>
						</li>
						<li>
								<a class="ajax-link" href="<?php echo base_url('faq/'); ?>">
									<i class="icon-list"></i><span class="hidden-tablet"> FAQ</span>
								</a>
						</li>
						<li>
								<a href="#modal_contact" class="ajax-link" id="contact" data-toggle="modal" data-target="#modal_contact">
									<i class="icon-comment"></i><span class="hidden-tablet"> Contacto</span>
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


