<?php

if (isset($user)) {
	$user = (array)$user;
}

$logged_in_user = $this->session->userdata('logged_in_user');
$disabled_attr = $logged_in_user['id'] === $user['id'] ? 'disabled' : '';

$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);

if (isset($old['username'])) {
	$username = $old['username'];
} else if (isset($user['nombre_usuario'])) {
	$username = $user['nombre_usuario'];
} else {
	$username = '';
}

if (isset($old['email'])) {
	$email = $old['email'];
} else if (isset($user['correo_electronico'])) {
	$email = $user['correo_electronico'];
} else {
	$email = '';
}

if (isset($old['role'])) {
	$role = $old['role'];
} else if (isset($user['rol'])) {
	$role = $user['rol'];
} else {
	$role = '';
}

?>


<div class="mb-3">
	<h3 class="mb-0">
		<span>Editar usuario #<?php echo isset($user['id']) ? $user['id'] : '' ?></span>
	</h3>
</div>
<div class="row">
	<div class="col-12 col-lg-6">
		<div class="card bg-white shadow">
			<div class="card-body">
				<form method="post" action="<?php echo base_url('admin/users/edit_user_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
					<input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id'] : '' ?>">
					<div class="form-group">
						<label for="name">Nombre de usuario</label>
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
						<label for="email">Correo electrónico</label>
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
						<label for="role">Rol</label>
						<?php if ($logged_in_user['id'] === $user['id']): ?>
							<input type="hidden" name="role" value="<?php echo $role ?>">
							<select class="custom-select" disabled>
								<option value="user" <?php echo $role === 'user' ? 'selected' : '' ?>>Usuario normal
								</option>
								<option value="admin" <?php echo $role === 'admin' ? 'selected' : '' ?>>Administrador
							</select>
						<?php else: ?>
							<select class="custom-select<?php echo isset($errors['role']) ? ' is-invalid' : '' ?>"
									name="role">
								<option value="user" <?php echo $role === 'user' ? 'selected' : '' ?>>Usuario normal
								</option>
								<option value="admin" <?php echo $role === 'admin' ? 'selected' : '' ?>>Administrador
								</option>
							</select>
						<?php endif; ?>
						<div class="invalid-feedback">
							<?php echo isset($errors['role']) ? $errors['role'] : '' ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<a href="<?php echo base_url('admin/usuarios') ?>" class="btn btn-lg btn-secondary">Cancelar</a>
						<button type="submit" class="btn btn-lg btn-primary btn-user">Guardar cambios</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
