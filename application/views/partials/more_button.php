<?php
$id = isset($id) ? $id : NULL;
$csrf_name = isset($csrf_name) ? $csrf_name : NULL;
$csrf_hash = isset($csrf_hash) ? $csrf_hash : NULL;
?>
<div class="dropdown d-inline-block">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" title="Más opciones">
        <i class="fas fa-ellipsis-h"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <form method="POST" action="<?php echo base_url('admin/estimates/change_status') ?>">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="status" value="draft">
            <input type="hidden" name="<?php echo $csrf_name ?>" value="<?php echo $csrf_hash ?>">
            <button class="dropdown-item change-status-btn" type="button">
                <i class="fas fa-circle mr-1"></i></i>
                <span>Marcar como borrador</span>
            </button>
        </form>
        <form method="POST" action="<?php echo base_url('admin/estimates/change_status') ?>">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="status" value="accepted">
            <input type="hidden" name="<?php echo $csrf_name ?>" value="<?php echo $csrf_hash ?>">
            <button class="dropdown-item change-status-btn" type="button">
                <i class="fas fa-check-circle mr-1"></i>
                <span>Marcar como aceptado</span>
            </button>
        </form>
        <form method="POST" action="<?php echo base_url('admin/estimates/change_status') ?>">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="<?php echo $csrf_name ?>" value="<?php echo $csrf_hash ?>">
            <button class="dropdown-item change-status-btn" type="button">
                <i class="fas fa-times-circle mr-1"></i>
                <span>Marcar como rechazado</span>
            </button>
        </form>
     </div>
</div>
