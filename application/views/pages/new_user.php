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
        <h1 class="mb-2 h3">Nuevo usuario</h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son obligatorios</p>
        <div class="card bg-white shadow">
            <div class="card-body">
                <form id="userForm" method="post" action="<?php echo base_url('admin/users/new_user_validation') ?>">
                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Nombre de usuario</label>
                        <input autocomplete="off"
                               type="text"
                               class="form-control"
                               name="username"
                               value="<?php echo $username ?>"
                               placeholder="Ej. juanh_63">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Correo
                            electrónico</label>
                        <input autocomplete="off"
                               type="text"
                               class="form-control"
                               name="email"
                               value="<?php echo $email ?>"
                               placeholder="Ej. juanh@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Contraseña</label>
                        <input autocomplete="off"
                               type="password"
                               class="form-control"
                               name="password"
                               value="<?php echo $password ?>"
                               placeholder="Ej. b8gVx3x6">
                    </div>
                    <div class="form-group">
                        <label for="role">Rol</label>
                        <select class="custom-select"  name="role">
                            <option value="user" <?php echo $role === 'user' ? 'selected' : '' ?>>Usuario normal</option>
                            <option value="admin" <?php echo $role === 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
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