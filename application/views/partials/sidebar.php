<?php
$logged_in_user = $this->session->userdata('logged_in_user');
?>

<aside id="sidebar" class="sidebar">
	<h3 class="sidebar-heading">
		<span>Catálogos</span>
	</h3>
	<ul class="nav flex-column">
		<li class="nav-item">
			<a class="nav-link <?php echo is_active('admin/clientes') ? 'active' : '' ?>"
			   href="<?php echo base_url('admin/clientes') ?>">
				<i class="fas fa-user-tie"></i>
				<span>Clientes</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php echo is_active('admin/productos') ? 'active' : '' ?>"
			   href="<?php echo base_url('admin/productos') ?>">
				<i class="fas fa-shopping-basket"></i>
				<span>Productos</span>
			</a>
		</li>
	</ul>
	<h3 class="sidebar-heading">
		<span>Documents</span>
	</h3>
	<ul class="nav flex-column">
		<li class="nav-item">
			<a class="nav-link" href="#">
				<i class="fas fa-calculator"></i>
				<span>Estimates</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">
				<i class="fas fa-file-invoice"></i>
				<span>Orders</span>
			</a>
		</li>
	</ul>

	<?php if ($logged_in_user['role'] === 'admin'): ?>
		<h3 class="sidebar-heading">
			<span>Administrador</span>
		</h3>
		<ul class="nav flex-column">
			<li class="nav-item">
				<a class="nav-link <?php echo is_active('admin/usuarios') ? 'active' : '' ?>"
				   href="<?php echo base_url('admin/usuarios') ?>">
					<i class="fas fa-users"></i>
					<span>Usuarios</span>
				</a>
			</li>
		</ul>
	<?php endif ?>


</aside>

