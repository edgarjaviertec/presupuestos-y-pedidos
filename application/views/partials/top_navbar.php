<?php
$logged_in_user = $this->session->userdata('logged_in_user');
?>
<nav class="navbar navbar-expand navbar-light topbar">
	<button id="sidebarToggler" type="button" class="btn btn-outline-primary mr-4 d-lg-none">
		<i class="fas fa-bars"></i>
	</button>
	<a class="navbar-brand" href="<?php echo base_url('admin/clientes') ?>">
		<i class="fas fa-file-invoice-dollar brand"></i>
		<span class="brand-text">Pedidos <sup>1.0</sup></span>
	</a>
	<ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown avatar-dropdown">
			<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
				<span><?php echo isset($logged_in_user['username']) ? $logged_in_user['username'] : '' ?></span>
				<?php if (isset($logged_in_user['avatar'])): ?>
					<img class="avatar" src="/uploads/<?php echo $logged_in_user['avatar'] ?>" alt="">
				<?php else: ?>
					<img class="avatar"  src="/assets/img/default-avatar.png">
				<?php endif; ?>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="<?php echo base_url('logout') ?>">Cerrar sesión</a>
			</div>
		</li>
	</ul>
</nav>
