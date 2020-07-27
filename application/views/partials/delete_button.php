<?php
$url = isset($url) ? $url : NULL;
$id = isset($id) ? $id : NULL;
$csrf_name = isset($csrf_name) ? $csrf_name : NULL;
$csrf_hash = isset($csrf_hash) ? $csrf_hash : NULL;
?>
<form class="btn btn-secondary p-0" method="POST" action="<?php echo $url ?>" title="Eliminar">
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<input type="hidden" name="<?php echo $csrf_name ?>" value="<?php echo $csrf_hash ?>">
	<button class="btn btn-secondary h-100 w-auto border-0 bg-transparent">
		<i class="fas fa-trash"></i>
	</button>
</form>
