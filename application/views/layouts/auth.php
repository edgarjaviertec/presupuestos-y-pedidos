<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo (isset($title) ? $title : "Sin Título") ?></title>
	<?php $this->load->view('partials/styles.php'); ?>
</head>
<body class="bg-gradient-dark">
<?php
if( isset($page) ){
	$this->load->view('pages/' . $page);
}
?>
</body>
</html>
