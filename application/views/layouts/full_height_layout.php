<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo (isset($title) ? $title : "Sin Título") ?></title>
	<?php isset($css_files) ? get_external_css($css_files) : '' ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/super-fresco.min.css') ?>"/>
</head>
<body class="bg-gradient-primary">
<?php isset($page) ? load_page($page) : '' ?>
<?php isset($js_files) ? get_external_js($js_files) : '' ?>
</body>
</html>
