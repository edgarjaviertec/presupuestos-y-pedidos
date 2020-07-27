<?php
$url = isset($url) ? $url : NULL;
$id = isset($id) ? $id : NULL;
$csrf_name = isset($csrf_name) ? $csrf_name : NULL;
$csrf_hash = isset($csrf_hash) ? $csrf_hash : NULL;
?>
<form class="btn btn-secondary p-0" method="GET" action="<?php echo $url ?>" title="Reutilizar">
	<input type="hidden" name="reuse" value="<?php echo $id ?>">
	<button class="btn btn-secondary h-100 w-auto border-0 bg-transparent">
		<i class="fas fa-sync"></i>
	</button>
</form>
