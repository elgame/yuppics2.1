<!-- start: Header -->
  <div class="navbar">
    <div class="navbar-inner">
      <div class="container-fluid">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a id="main-menu-toggle" class="hidden-phone open"><i class="icon-reorder"></i></a>
        <div class="row-fluid">
        <a class="brand span2" href="<?php echo base_url('panel') ?>"><span>SimpliQ</span></a>
        </div>
        <!-- start: Header Menu -->
        <div class="nav-no-collapse header-nav">
          <ul class="nav pull-right">

            <!-- start: Notifications Dropdown -->
            <!-- <li class="dropdown hidden-phone">
              <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-warning-sign"></i>
              </a>
              <ul class="dropdown-menu notifications">
                <li class="dropdown-menu-title">
                  <span>You have 11 notifications</span>
                </li>
                <li>
                  <a href="#">
                    <span class="icon blue"><i class="icon-user"></i></span>
                    <span class="message">New user registration</span>
                    <span class="time">1 min</span>
                  </a>
                </li>
                <li class="dropdown-menu-sub-footer">
                  <a>View all notifications</a>
                </li>
              </ul>
            </li> -->
            <!-- end: Notifications Dropdown -->

            <!-- start: Notifications Dropdown -->
            <!-- <li class="dropdown hidden-phone">
              <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-tasks"></i>
              </a>
              <ul class="dropdown-menu tasks">
                <li>
                  <span class="dropdown-menu-title">You have 17 tasks in progress</span>
                </li>
                <li>
                  <a href="#">
                    <span class="header">
                      <span class="title">iOS Development</span>
                      <span class="percent"></span>
                    </span>
                    <div class="taskProgress progressSlim progressBlue">80</div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-menu-sub-footer">View all tasks</a>
                </li>
              </ul>
            </li> -->
            <!-- end: Notifications Dropdown -->

            <!-- start: Message Dropdown -->
            <!-- <li class="dropdown hidden-phone">
              <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-envelope"></i>
              </a>
              <ul class="dropdown-menu messages">
                <li>
                  <span class="dropdown-menu-title">You have 9 messages</span>
                </li>
                <li>
                  <a href="#">
                    <span class="avatar"><img src="img/avatar.jpg" alt="Avatar"></span>
                    <span class="header">
                      <span class="from">
                          Łukasz Holeczek
                      </span>
                      <span class="time">
                          6 min
                      </span>
                    </span>
                    <span class="message">
                      Lorem ipsum dolor sit amet consectetur adipiscing elit, et al commore
                    </span>
                  </a>
                </li>
                <li>
                  <a class="dropdown-menu-sub-footer">View all messages</a>
                </li>
              </ul>
            </li> -->
            <!-- end: Message Dropdown -->

            <!-- <li>
              <a class="btn" href="#">
                <i class="icon-wrench"></i>
              </a>
            </li> -->

            <!-- start: User Dropdown -->
            <li class="dropdown">
              <a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
                <div class="avatar"><img src="img/avatar.jpg" alt="Avatar"></div>
                <div class="user">
                  <span class="hello">Bienvenido!</span>
                  <span class="name"><?php echo $this->session->userdata('nombre_panel') ?></span>
                </div>
              </a>
              <ul class="dropdown-menu">
                <li class="dropdown-menu-title">

                </li>
                <!-- <li><a href="#"><i class="icon-user"></i> Profile</a></li>
                <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
                <li><a href="#"><i class="icon-envelope"></i> Messages</a></li> -->
                <li><a href="<?php echo base_url('panel/home/logout') ?>"><i class="icon-off"></i> Logout</a></li>
              </ul>
            </li>
            <!-- end: User Dropdown -->
          </ul>
        </div>
        <!-- end: Header Menu -->

      </div>
    </div>
  </div>
  <!-- start: Header -->