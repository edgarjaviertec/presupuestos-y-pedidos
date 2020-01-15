<?php
$flash_message = $this->session->flashdata('flash_message');
$flash_msg_data_attr = '';
if (isset($flash_message["type"]) && isset($flash_message["title"])) {
	$flash_msg_data_attr = 'data-flash-msg-type="' . $flash_message["type"] . '"';
	$flash_msg_data_attr .= ' data-flash-msg-title="' . $flash_message["title"] . '"';
}
?>
<h2 class="page-heading">
	<span>Clientes</span>
	<a href="<?php echo base_url('admin/clientes/nuevo') ?>" class="ml-3 btn btn-lg btn-success">
		<i class="fas fa-plus"></i>
		<span class="ml-1">Nuevo cliente</span>
	</a>
</h2>

<div class="card shadow-lg dt-card">
	<div class="card-body">
			<table class="table table-bordered dt-responsive nowrap" id="data_tables" <?php echo $flash_msg_data_attr ?> style="width:100%">
				<thead>
				<tr>
					<th>Id</th>
					<th>Nombre completo</th>
					<th>Empresa</th>
					<th>RFC</th>
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




