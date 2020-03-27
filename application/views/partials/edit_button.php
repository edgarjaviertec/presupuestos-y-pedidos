<?php
$url = isset($url) ? $url : NULL;
$status = isset($status) ? $status : NULL;
?>
<a href="<?php echo $url ?>" class="btn btn-secondary mr-2 <?php echo ($status==='cancelled') ? 'disabled': '' ?>" title="Editar" >
	<i class="fas fa-pencil-alt"></i>
</a>