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
			<div class="card shadow">
				<div class="card-body">
					<h1 class="h4 mb-4">Ingrese a su cuenta</h1>
					<form id="loginForm" action="<?php echo base_url('auth/login_validation') ?>" method="post">
						<input id="csrf" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
						<div class="form-group">
							<label for="name">Nombre de usuario o correo electrónico</label>
							<input id="username"
								   type="text"
								   class="form-control"
								   name="username"
								   autocomplete="off"
								   value="<?php echo isset($old['username']) ? $old['username'] : '' ?>">
						</div>
						<div class="form-group">
							<label for="name">Contraseña</label>
							<input id="password"
								   type="password"
								   class="form-control"
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


