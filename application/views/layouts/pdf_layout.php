<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo(isset($title) ? $title : "Sin Título") ?></title>
	<?php isset($css_files) ? get_external_css($css_files) : '' ?>
	<link href='https://fonts.googleapis.com/css?family=Tangerine:700' rel='stylesheet' type='text/css'>
</head>
<body>
<?php isset($page) ? load_page($page) : '' ?>
</body>
</html>
