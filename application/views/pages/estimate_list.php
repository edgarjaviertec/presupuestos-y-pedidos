<?php
$flash_message = $this->session->flashdata('flash_message');
$flash_msg_data_attr = '';
if (isset($flash_message["type"]) && isset($flash_message["title"])) {
    $flash_msg_data_attr = 'data-flash-msg-type="' . $flash_message["type"] . '"';
    $flash_msg_data_attr .= ' data-flash-msg-title="' . $flash_message["title"] . '"';
}
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
$current_year = intval(date("Y"));
?>
<h1 class="page-heading h3 mb-3 ">
    <span>Presupuesto</span>
    <div>
        <button type="button" class="btn btn-lg btn-secondary  align-items-center" data-toggle="modal" data-target="#generateReportModal">
            <i class="fas fa-table"></i>
            <span class="ml-1 d-none d-md-inline-block">Reporte mensual</span>
        </button>
        <a href="<?php echo base_url('admin/presupuestos/nuevo') ?>"
           class="ml-2 btn btn-lg btn-success  align-items-center">
            <i class="fas fa-plus"></i>
            <span class="ml-1 d-none d-sm-inline-block">Nuevo presupuesto</span>
        </a>
    </div>
</h1>
<div class="card shadow">
    <div class="card-body p-3">
        <table class="table table-bordered dt-responsive" id="dataTables" <?php echo $flash_msg_data_attr ?>
               style="width:100%">
            <thead>
            <tr>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Total</th>
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
<div class="modal fade" id="generateReportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form target="_blank" action="<?php echo base_url('admin/estimates/generate_pdf_report') ?>" method="get">
                <div class="modal-header">
                    <h5 class="modal-title">Generar reporte</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mes</label>
                        <select class="custom-select" name="month">
                            <option value="1" <?php echo(intval(date("m")) == 1 ? 'selected' : '') ?>>Enero</option>
                            <option value="2" <?php echo(intval(date("m")) == 2 ? 'selected' : '') ?>>Febrero</option>
                            <option value="3" <?php echo(intval(date("m")) == 3 ? 'selected' : '') ?>>Marzo</option>
                            <option value="4" <?php echo(intval(date("m")) == 4 ? 'selected' : '') ?>>Abril</option>
                            <option value="5" <?php echo(intval(date("m")) == 5 ? 'selected' : '') ?>>Mayo</option>
                            <option value="6" <?php echo(intval(date("m")) == 6 ? 'selected' : '') ?>>Junio</option>
                            <option value="7" <?php echo(intval(date("m")) == 7 ? 'selected' : '') ?>>Julio</option>
                            <option value="8" <?php echo(intval(date("m")) == 8 ? 'selected' : '') ?>>Agosto</option>
                            <option value="9" <?php echo(intval(date("m")) == 9 ? 'selected' : '') ?>>Septiembre</option>
                            <option value="10" <?php echo(intval(date("m")) == 10 ? 'selected' : '') ?>>Octubre</option>
                            <option value="11" <?php echo(intval(date("m")) == 11 ? 'selected' : '') ?>>Noviembre</option>
                            <option value="12" <?php echo(intval(date("m")) == 12 ? 'selected' : '') ?>>Diciembre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Año</label>
                        <select class="custom-select" name="year">
                            <?php for ($i = $current_year; $i > $current_year - 5; $i--): ?>
                                <option value="<?php echo $i ?>" <?php echo($current_year == $i ? 'selected' : '') ?> ><?php echo $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="submit-btn btn btn-success">
                        <span class="is-not-loading">Generar PDF</span>
                        <div class="loading d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span>Loading...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>