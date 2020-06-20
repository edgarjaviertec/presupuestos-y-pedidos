<?php
$flash_message = $this->session->flashdata('flash_message');
$flash_msg_data_attr = '';
if (isset($flash_message["type"]) && isset($flash_message["title"])) {
    $flash_msg_data_attr = 'data-flash-msg-type="' . $flash_message["type"] . '"';
    $flash_msg_data_attr .= ' data-flash-msg-title="' . $flash_message["title"] . '"';
}
?>
<?php $this->load->view('partials/spinner'); ?>
<div id="pageContent" class="d-none">
	<h1 class="page-heading h3 mb-3">
		<span>Productos</span>
		<a href="<?php echo base_url('admin/productos/nuevo') ?>"
		   class="ml-2 btn btn-lg btn-success  align-items-center">
			<i class="fas fa-plus"></i>
			<span class="ml-1 d-none d-sm-inline-block">Nuevo producto</span>
		</a>
	</h1>
	<div class="card shadow">
		<div class="card-body p-3">
			<table class="table table-bordered dt-responsive" id="dataTables" <?php echo $flash_msg_data_attr ?> style="width:100%">
				<thead>
				<tr>
					<th>Id</th>
					<th>Nombre</th>
					<th>Descripción</th>
					<th>Precio unitario</th>
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
