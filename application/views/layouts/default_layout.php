<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo(isset($title) ? $title : "Sin Título") ?></title>
	<?php isset($css_files) ? get_external_css($css_files) : '' ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome/css/all.min.css') ?>"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/sb-admin-2.min.css') ?>"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/estilos.css') ?>"/>
</head>
<body>
<div id="wrapper">
	<?php $this->load->view('partials/sidebar'); ?>
	<div id="content-wrapper" class="d-flex flex-column">
		<div id="content">
<!--			--><?php
//				echo "<pre>";
//				var_dump($this->uri->uri_string()  );
//				echo "</pre>";
//			?>
			<?php $this->load->view('partials/top_navbar'); ?>
			<div class="container-fluid py-4">
				<?php isset($page) ? load_page($page) : '' ?>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/sb-admin-2.js') ?>"></script>
<?php isset($js_files) ? get_external_js($js_files) : '' ?>
</body>
</html>
