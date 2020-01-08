<?php
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);
?>
<!--<div class="m-5 p-3 bg-white">-->
<!--	--><?php
//	echo "<pre>";
//	print_r($old);
//	echo "</pre>";
//	?>
<!--</div>-->
<div class="container d-flex align-items-center h-100 w-100">
	<div class="row w-100 justify-content-center">
		<div class="col-12 col-sm-12 col-md-6 col-lg-5">
			<div class="card o-hidden border-0 shadow-lg">
				<div class="card-body p-4">
					<div class="text-center">
						<h1 class="h4 text-gray-900 mb-4">Iniciar sesión</h1>
					</div>
					<form action="<?php echo base_url('auth/login_validation') ?>" method="post">
						<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
						<div class="form-group">
							<label for="name">Nombre de usuario o correo electrónico</label>
							<input type="text"
								   class="form-control<?php echo isset($errors['username']) ? ' is-invalid' : '' ?>"
								   name="username"
								   autocomplete="off"
								   value="<?php echo isset($old['username']) ? $old['username'] : '' ?>">
							<div class="invalid-feedback">
								<?php echo isset($errors['username']) ? $errors['username'] : '' ?>
							</div>
						</div>
						<div class="form-group">
							<label for="name">Contraseña</label>
							<input type="password"
								   class="form-control<?php echo isset($errors['password']) ? ' is-invalid' : '' ?>"
								   name="password"
								   autocomplete="off"
								   value="<?php echo isset($old['password']) ? $old['password'] : '' ?>">
							<div class="invalid-feedback">
								<?php echo isset($errors['password']) ? $errors['password'] : '' ?>
							</div>
						</div>
						<input class="btn btn-primary btn-block" type="submit" value="Iniciar sesión">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
