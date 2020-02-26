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
<div class="row justify-content-center">
	<div class="col-12 col-lg-10 col-xl-8">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger shadow">
                <p class="mb-2">
                    <strong>Hay algunos errores, por favor corríjalos y vuelva a intentarlo:</strong>
                </p>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <h1 class="mb-2 h3">Cambiar la contraseña del usuario #<?php echo isset($user['id']) ? $user['id'] : '' ?></h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son obligatorios</p>
        <div class="card bg-white shadow">
			<div class="card-body p-3">
				<form id="changePasswordForm" method="post"
					  action="<?php echo base_url('admin/users/change_password_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
					<input type="hidden" name="id" value="<?php echo isset($user['id']) ? $user['id'] : '' ?>">
					<div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Nueva contraseña</label>
                        <input id="password"
							   type="password"
							   class="form-control"
							   name="password"
							   value="<?php echo $password ?>"
							   placeholder="Ej. b8gVx3x6">
					</div>
					<div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Repetir contraseña</label>
                        <input type="password"
							   class="form-control"
							   name="confirm_password"
							   value="<?php echo $confirm_password ?>"
							   placeholder="Ej. b8gVx3x6">
					</div>
                    <div class="action-buttons">
                        <a href="<?php echo base_url('admin/usuarios') ?>"
                           class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
                        <button type="submit" class="btn btn-lg btn-success ok-btn">Guardar</button>
                    </div>
				</form>
			</div>
		</div>
	</div>
</div>
