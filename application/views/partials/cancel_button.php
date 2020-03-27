<?php
$url = isset($url) ? $url : NULL;
$id = isset($id) ? $id : NULL;
$status = isset($status) ? $status : NULL;
$csrf_name = isset($csrf_name) ? $csrf_name : NULL;
$csrf_hash = isset($csrf_hash) ? $csrf_hash : NULL;
$is_disabled = isset($is_disabled) && $is_disabled ? 'disabled' : '';
?>
<form class="d-inline" method="POST" action="<?php echo $url ?>" title="Cancelar">
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<input type="hidden" name="<?php echo $csrf_name ?>" value="<?php echo $csrf_hash ?>">
	<button class="btn btn-secondary delete_btn" <?php echo $is_disabled ?> <?php echo ($status==='cancelled') ? 'disabled': '' ?>>
        <i class="fas fa-ban"></i>
	</button>
</form>