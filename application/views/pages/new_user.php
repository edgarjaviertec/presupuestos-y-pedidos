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

if (isset($old['username'])) {
	$username = $old['username'];
} else {
	$username = '';
}

if (isset($old['email'])) {
	$email = $old['email'];
} else {
	$email = '';
}


if (isset($old['password'])) {
	$password = $old['password'];
} else {
	$password = '';
}


if (isset($old['role'])) {
	$role = $old['role'];
} else {
	$role = '';
}

?>


<div class="mb-3">
	<h3 class="mb-0">
		<span>Nuevo usuario</span>
	</h3>
</div>

<div class="row">
	<div class="col-12 col-lg-6">
		<div class="card bg-white shadow">
			<div class="card-body">
				<form method="post" action="<?php echo base_url('admin/users/new_user_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
					<div class="form-group">
						<label>Nombre de usuario</label>
						<input
							autocomplete="off"
							type="text"
							class="form-control<?php echo isset($errors['username']) ? ' is-invalid' : '' ?>"
							name="username"
							value="<?php echo $username ?>"
						>
						<div class="invalid-feedback">
							<?php echo isset($errors['username']) ? $errors['username'] : '' ?>
						</div>
					</div>
					<div class="form-group">
						<label>Correo electrónico</label>
						<input
							autocomplete="off"
							type="text"
							class="form-control<?php echo isset($errors['email']) ? ' is-invalid' : '' ?>"
							name="email"
							value="<?php echo $email ?>"
						>
						<div class="invalid-feedback">
							<?php echo isset($errors['email']) ? $errors['email'] : '' ?>
						</div>
					</div>
					<div class="form-group">
						<label>Contraseña</label>
						<input
							autocomplete="off"
							type="password"
							   class="form-control<?php echo isset($errors['password']) ? ' is-invalid' : '' ?>"
							   name="password"
							   value="<?php echo $password ?>"
						>
						<div class="invalid-feedback">
							<?php echo isset($errors['password']) ? $errors['password'] : '' ?>
						</div>
					</div>
					<div class="form-group">
						<label for="role">Rol</label>
						<select
							class="custom-select<?php echo isset($errors['role']) ? ' is-invalid' : '' ?>"
							name="role">
							<option
								value="user" <?php echo $role === 'user' ? 'selected' : '' ?>>
								Usuario normal
							</option>
							<option
								value="admin" <?php echo $role === 'admin' ? 'selected' : '' ?>>
								Administrador
							</option>
						</select>
						<div class="invalid-feedback">
							<?php echo isset($errors['role']) ? $errors['role'] : '' ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<a href="<?php echo base_url('admin/usuarios') ?>" class="btn btn-lg btn-secondary">Cancelar</a>
						<button type="submit" class="btn btn-lg btn-primary btn-user">Crear usuario</button>
					</div>
				</form>
			</div>
		</div>

	</div>
</div>
