<?php

if (isset($user)) {
	$user = (array)$user;
}

$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);

if (isset($old['password'])) {
	$password = $old['password'];
} else {
	$password = '';
}

if (isset($old['confirm_password'])) {
	$confirm_password = $old['confirm_password'];
} else {
	$confirm_password = '';
}

?>

<div class="mb-3">
	<h3 class="mb-0">
		<span>Cambiar la contraseña del usuario #<?php echo isset($user['id']) ? $user['id'] : '' ?></span>
	</h3>
</div>

<div class="row">
	<div class="col-12 col-lg-6">
		<div class="card bg-white shadow">
			<div class="card-body">
				<form method="post" action="<?php echo base_url('admin/users/change_password_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
					<input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id'] : '' ?>">
					<div class="form-group">
						<label>Nueva contraseña</label>
						<input type="password"
							   class="form-control<?php echo isset($errors['password']) ? ' is-invalid' : '' ?>"
							   name="password"
							   value="<?php echo $password ?>">
						<div class="invalid-feedback">
							<?php echo isset($errors['password']) ? $errors['password'] : '' ?>
						</div>
					</div>
					<div class="form-group">
						<label>Repetir contraseña</label>
						<input type="password"
							   class="form-control<?php echo isset($errors['confirm_password']) ? ' is-invalid' : '' ?>"
							   name="confirm_password"
							   value="<?php echo $confirm_password ?>">
						<div class="invalid-feedback">
							<?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : '' ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<a href="<?php echo base_url('admin/usuarios') ?>" class="btn btn-lg btn-secondary">Cancelar</a>
						<button type="submit" class="btn btn-lg btn-primary">Cambiar contraseña</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
