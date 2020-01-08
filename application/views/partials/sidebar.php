<?php
$logged_in_user = $this->session->userdata('logged_in_user');
?>
<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
	<a class="sidebar-brand d-flex align-items-center justify-content-center"
	   href="<?php echo base_url('admin/clientes') ?>">
		<div class="sidebar-brand-icon rotate-n-15">
			<i class="fas fa-file-invoice-dollar"></i>
		</div>
		<div class="sidebar-brand-text mx-3">Pedidos <sup>1.0</sup></div>
	</a>
	<hr class="sidebar-divider">
	<div class="sidebar-heading">
		Catálogos
	</div>
	<li class="nav-item <?php echo is_active('admin/clientes') ? 'active' : '' ?>">
		<a class="nav-link" href="<?php echo base_url('admin/clientes') ?>">
			<i class="fas fa-user-tie"></i>
			<span>Clientes</span>
		</a>
	</li>
	<li class="nav-item <?php echo is_active('admin/productos') ? 'active' : '' ?>">
		<a class="nav-link" href="<?php echo base_url('admin/productos') ?>">
			<i class="fas fa-shopping-basket"></i>
			<span>Productos</span>
		</a>
	</li>
	<hr class="sidebar-divider">
	<div class="sidebar-heading">
		Documentos
	</div>
	<li class="nav-item">
		<a class="nav-link" href="#">
			<i class="fas fa-calculator"></i>
			<span>Presupuestos</span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="#">
			<i class="fas fa-file-invoice"></i>
			<span>Pedidos</span>
		</a>
	</li>
	<?php if ($logged_in_user['role'] === 'admin'): ?>
		<hr class="sidebar-divider">
		<div class="sidebar-heading">
			Administrador
		</div>
		<li class="nav-item <?php echo is_active('admin/usuarios') ? 'active' : '' ?>">
			<a class="nav-link" href="<?php echo base_url('admin/usuarios') ?>">
				<i class="fas fa-users"></i>
				<span>Usuarios</span>
			</a>
		</li>
	<?php endif ?>
	<hr class="sidebar-divider d-none d-md-block">
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
