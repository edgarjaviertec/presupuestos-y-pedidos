<?php
$logged_in_user = $this->session->userdata('logged_in_user');
?>
<nav class="navbar navbar-expand navbar-dark bg-primary topbar static-top shadow">
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
		<i class="fa fa-bars"></i>
	</button>
	<ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle logged-in-user-menu" href="#" id="userDropdown" data-toggle="dropdown">
				<span
					class="mr-2 d-none d-lg-inline"><?php echo isset($logged_in_user['username']) ? $logged_in_user['username'] : '' ?></span>
				<span class="avatar-icon">
					<i class="fas fa-user"></i>
				</span>
			</a>
			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
				<a class="dropdown-item" href="<?php echo base_url('logout') ?>">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 "></i>
					<span>Cerrar sesión</span>
				</a>
			</div>
		</li>
	</ul>
</nav>
