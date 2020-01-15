<?php
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);
?>

<div class="full-height-layout bg-primary">
	<main class="main">
		<div class="login">
			<div class="card shadow">
				<div class="card-body">
					<h1 class="h4 mb-4">Ingrese a su cuenta</h1>
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
						<input class="btn btn-lg btn-success btn-block" type="submit" value="Iniciar sesión">
					</form>
				</div>
			</div>
		</div>
	</main>
</div>


