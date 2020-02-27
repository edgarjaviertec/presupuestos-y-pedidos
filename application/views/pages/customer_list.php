<?php
$flash_message = $this->session->flashdata('flash_message');
$flash_msg_data_attr = '';
if (isset($flash_message["type"]) && isset($flash_message["title"])) {
    $flash_msg_data_attr = 'data-flash-msg-type="' . $flash_message["type"] . '"';
    $flash_msg_data_attr .= ' data-flash-msg-title="' . $flash_message["title"] . '"';
}
?>
<h1 class="page-heading h3 mb-3 ">
    <span>Clientes</span>
    <a href="<?php echo base_url('admin/clientes/nuevo') ?>"
       class="ml-2 btn btn-lg btn-success  align-items-center">
        <i class="fas fa-plus"></i>
        <span class="ml-1 d-none d-sm-inline-block">Nuevo cliente</span>
    </a>
</h1>
<div class="card shadow-lg dt-card">
    <div class="card-body p-3">
        <table class="table table-bordered dt-responsive" id="dataTables" <?php echo $flash_msg_data_attr ?>
               style="width:100%">
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