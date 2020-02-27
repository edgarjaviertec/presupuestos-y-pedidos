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
            <div class="text-white mb-4 d-flex justify-content-center">
                <div>
                    <i class="fas fa-file-invoice-dollar brand fa-4x logo"></i>
                    <span class="ml-3 brand-text h2 font-weight-bold">Pedidos <sup>1.0</sup></span>
                </div>
            </div>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger shadow text-body">
                    <p class="mb-2">
                        <strong>Hay algunos errores:</strong>
                    </p>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="card shadow">
                <div class="card-body">
                    <form id="loginForm" action="<?php echo base_url('auth/login_validation') ?>" method="post">
                        <input id="csrf" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                        <div class="form-group">
                            <h1 class="mb-3 h3">Inicie sesión</h1>
                            <label>Nombre de usuario o correo electrónico</label>
                            <input id="username"
                                   type="text"
                                   class="form-control form-control-lg"
                                   name="username"
                                   autocomplete="off"
                                   value="<?php echo isset($old['username']) ? $old['username'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Contraseña</label>
                            <input id="password"
                                   type="password"
                                   class="form-control form-control-lg"
                                   name="password"
                                   autocomplete="off"
                                   value="<?php echo isset($old['password']) ? $old['password'] : '' ?>">
                        </div>
                        <input class="btn btn-lg btn-success btn-block" type="submit" value="Iniciar sesión">
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>