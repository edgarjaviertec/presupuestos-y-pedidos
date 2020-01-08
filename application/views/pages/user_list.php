<?php
$flash_message = $this->session->flashdata('flash_message');
$flash_msg_data_attr = '';
if (isset($flash_message["type"]) && isset($flash_message["title"])) {
	$flash_msg_data_attr = 'data-flash-msg-type="' . $flash_message["type"] . '"';
	$flash_msg_data_attr .= ' data-flash-msg-title="' . $flash_message["title"] . '"';
}
?>
<h3 class="mb-4">
	<span>Usuarios</span>
	<a href="<?php echo base_url('admin/usuarios/nuevo') ?>" class="ml-3 btn btn-primary">
		<i class="fas fa-plus"></i>
		<span class="ml-1">Nuevo usuario</span>
	</a>
</h3>

<div class="card shadow">
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="data_tables" <?php echo $flash_msg_data_attr ?>>
				<thead>
				<tr>
					<th>Id</th>
					<th>Nombre de usuario</th>
					<th>Correo electrónico</th>
					<th>Rol</th>
					<th class="no-sort">Acciones</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>loading...</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
