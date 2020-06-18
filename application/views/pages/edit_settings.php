<?php


//echo "<pe>";
//echo var_dump($settings);
//echo "</pre>";
//die();

$encryption = NULL;


$csrf = [
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
];

$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');


$null_logo = empty($company_settings["company_logo"]) ? 1 : 0;

$company_settings = !empty($company_settings) ? $company_settings : [];
$mail_settings = !empty($mail_settings) ? $mail_settings : [];


if (!empty($old['business_name'])) {
	$business_name = $old['business_name'];
} elseif (!empty($company_settings['business_name'])) {
	$business_name = $company_settings['business_name'];
} else {
	$business_name = '';
}


if (!empty($old['company_name'])) {
	$company_name = $old['company_name'];
} elseif (!empty($company_settings['company_name'])) {
	$company_name = $company_settings['company_name'];
} else {
	$company_name = '';
}

if (!empty($old['company_address'])) {
	$company_address = $old['company_address'];
} elseif (!empty($company_settings['company_address'])) {
	$company_address = $company_settings['company_address'];
} else {
	$company_address = '';
}


if (!empty($old['mail_is_enabled'])) {
	$mail_is_enabled = $old['mail_is_enabled'];
} elseif (!empty($mail_settings['mail_is_enabled'])) {
	$mail_is_enabled = $mail_settings['mail_is_enabled'];
} else {
	$mail_is_enabled = '';
}


if (!empty($old['mail_host'])) {
	$mail_host = $old['mail_host'];
} elseif (!empty($mail_settings['mail_host'])) {
	$mail_host = $mail_settings['mail_host'];
} else {
	$mail_host = '';
}


if (!empty($old['mail_username'])) {
	$mail_username = $old['mail_username'];
} elseif (!empty($mail_settings['mail_username'])) {
	$mail_username = $mail_settings['mail_username'];
} else {
	$mail_username = '';
}


if (!empty($old['mail_password'])) {
	$mail_password = $old['mail_password'];
} elseif (!empty($mail_settings['mail_password'])) {
	$mail_password = $mail_settings['mail_password'];
} else {
	$mail_password = '';
}


if (!empty($old['mail_port'])) {
	$mail_port = $old['mail_port'];
} elseif (!empty($mail_settings['mail_port'])) {
	$mail_port = $mail_settings['mail_port'];
} else {
	$mail_port = '';
}


if (!empty($old['mail_smtp_secure'])) {
	$mail_smtp_secure = $old['mail_smtp_secure'];
} elseif (!empty($mail_settings['mail_smtp_secure'])) {
	$mail_smtp_secure = $mail_settings['mail_smtp_secure'];
} else {
	$mail_smtp_secure = '';
}


if (!empty($old['mail_from'])) {
	$mail_from = $old['mail_from'];
} elseif (!empty($mail_settings['mail_from'])) {
	$mail_from = $mail_settings['mail_from'];
} else {
	$mail_from = '';
}


if (!empty($old['mail_from_name'])) {
	$mail_from_name = $old['mail_from_name'];
} elseif (!empty($mail_settings['mail_from_name'])) {
	$mail_from_name = $mail_settings['mail_from_name'];
} else {
	$mail_from_name = '';
}


if (!empty($old['logo'])) {
	$logo = $old['logo'];
} else {
	$logo = '';
}


if (!empty($old['logo'])) {
	$src = $old['logo'];
} elseif (!empty($company_settings['company_logo'])) {
	$src = "/uploads/{$company_settings['company_logo']}";
} else {
	$src = "/assets/img/default-logo.png";
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
		<h1 class="mb-2 h3">Configuración general</h1>
		<p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
			obligatorios</p>
		<div class="card bg-white shadow">
			<div class="card-body p-3">
				<form id="settingsForm" method="post"
					  action="<?php echo base_url('admin/settings/edit_settings_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>

					<input type="hidden" id="nullLogo" name="null_logo" value="<?php echo $null_logo ?>">
					<input type="hidden" id="logo" name="logo" value="<?php echo $logo ?>">


					<fieldset class="mb-4">
						<legend class="mb-3">
							<i class="h2 fas fa-building text-muted"></i>
							<span class="h3 ml-2">Información de la compañía</span>
						</legend>
						<div class="form-group">
							<div class="card logo-upload-preview d-none d-lg-block">
								<div class="card-body">
									<img
											src='<?php echo $src ?>'
											data-src="/assets/img/default-logo.png"
											id="imagePreview"
											class="image-preview"
									>
									<div class="file-uploader btn btn-secondary btn-block mt-3">
										<input type="file"
											   class="file-input f1"
											   name="image"
											   id="fileInput"
											   accept="image/x-png,image/gif,image/jpeg">
										<span>Seleccionar foto</span>
									</div>
									<button <?php echo (!empty($null_logo) && empty($logo)) ? 'disabled' : '' ?>
											id="removeLogo"
											type="button"
											class="mt-2 btn btn-danger btn-block">
										Quitar foto
									</button>
								</div>
							</div>
						</div>


						<div class="form-group">
							<label class="font-weight-bold">
								<i class="fas fa-asterisk text-danger"></i>
								&nbsp;
								Razón social


							</label>
							<input autocomplete="off"
								   type="text"
								   class="form-control"
								   name="business_name"
								   value="<?php echo $business_name ?>"
								   placeholder="Ej. Juan Rulfo Vizcaíno">
						</div>


						<div class="form-group">
							<label class="font-weight-bold">
								<i class="fas fa-asterisk text-danger"></i>
								&nbsp;Nombre de la empresa
							</label>
							<input autocomplete="off"
								   type="text"
								   class="form-control"
								   name="company_name"
								   value="<?php echo $company_name ?>"
								   placeholder="Ej. Papelería La Estrella">
						</div>

						<div class="form-group">
							<label class="font-weight-bold">
								<i class="fas fa-asterisk text-danger"></i>
								&nbsp;Domicilio fiscal
							</label>
							<textarea
									autocomplete="off"
									class="form-control"
									name="company_address"
									placeholder="Ej. Calle Falsa 123"
									rows="5"><?php echo $company_address ?></textarea>

						</div>


					</fieldset>

					<fieldset class="form-group">
						<legend class="mb-3">
							<i class="mb-0 h2 fas fa-envelope text-muted"></i>
							<span class="mb-0 h3 ml-2">Configuración de correo</span>
						</legend>


						<div class="form-group form-check">
							<input type="checkbox"
								   class="form-check-input"
								   name="mail_is_enabled"
									<?php echo !empty($mail_is_enabled) ? 'checked' : '' ?>
								   id="toggleMailSettings"
								   value="<?php echo !empty($mail_is_enabled) ? '1' : '0' ?>"
							>
							<label class="form-check-label" for="toggleMailSettings">
								Activar el envío de pedidos y
								presupuesto por correo electrónico
								<strong>(se necesita un servidor SMTP)</strong>
							</label>
						</div>

						<div class="collapse <?php echo !empty($mail_is_enabled) ? 'show' : '' ?>" id="mailSettings">

							<div>
								<div class="form-group">
									<label class="font-weight-bold">
										<i class="fas fa-asterisk text-danger"></i>
										&nbsp;Host de correo
									</label>
									<input autocomplete="off"
										   id="mailHost"
										   type="text"
										   class="form-control"
										   name="mail_host"
										   value="<?php echo $mail_host ?>"
										   placeholder="Ej. smtp.gmail.com">
								</div>
								<div class="form-group">
									<label class="font-weight-bold">
										<i class="fas fa-asterisk text-danger"></i>
										&nbsp;Nombre de usuario
									</label>
									<input autocomplete="off"
										   type="text"
										   class="form-control"

										   id="mailUsername"
										   name="mail_username"
										   value="<?php echo $mail_username ?>"
										   placeholder="Ej. juanr@gmail.com">
								</div>
								<div class="form-group">
									<label class="font-weight-bold">
										<i class="fas fa-asterisk text-danger"></i>
										&nbsp;Contraseña
									</label>
									<input autocomplete="off"
										   type="text"
										   class="form-control"
										   id="mailPassword"
										   name="mail_password"
										   value="<?php echo $mail_password ?>"
										   placeholder="Ej. password123">
								</div>
								<div class="form-group">
									<label class="font-weight-bold">
										<i class="fas fa-asterisk text-danger"></i>
										&nbsp;Puerto
									</label>
									<input autocomplete="off"
										   type="text"
										   class="form-control"
										   id="mailPort"
										   name="mail_port"
										   value="<?php echo $mail_port ?>"
										   placeholder="Ej. 465">
								</div>

								<div class="form-group">
									<label class="font-weight-bold">Cifrado</label>
									<select
											name="mail_smtp_secure"
											class="custom-select">
										<option value="ssl" <?php echo $mail_smtp_secure === 'ssl' ? 'selected' : '' ?>>
											ssl
										</option>
										<option value="tls" <?php echo $mail_smtp_secure === 'tls' ? 'selected' : '' ?>>
											tls
										</option>
										<option value="starttls" <?php echo $mail_smtp_secure === 'starttls' ? 'selected' : '' ?>>
											starttls
										</option>
									</select>


								</div>
								<div class="form-group">
									<label class="font-weight-bold">
										<i class="fas fa-asterisk text-danger"></i>
										&nbsp;E-mail del remitente
									</label>
									<input autocomplete="off"
										   type="text"
										   class="form-control"
										   id="mailFrom"
										   name="mail_from"
										   value="<?php echo $mail_from ?>"
										   placeholder="Ej. juanr@misitio.com.mx">
								</div>
								<div class="form-group">
									<label class="font-weight-bold">
										<i class="fas fa-asterisk text-danger"></i>
										&nbsp;Nombre del remitente
									</label>
									<input autocomplete="off"
										   type="text"
										   class="form-control"
										   id="mailFromName"
										   name="mail_from_name"
										   value="<?php echo $mail_from_name ?>"
										   placeholder="Ej. Juan Rulfo">
								</div>

							</div>
						</div>


					</fieldset>


					<div class="action-buttons">
						<a href="<?php echo base_url('admin/clientes') ?>"
						   class="btn btn-lg btn-secondary cancel-btn">Cancelar</a>
						<button type="submit"
								class="btn btn-lg btn-success ok-btn">Guardar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>


</div>


<div class="modal " id="modalCrop" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<div id="croppie"></div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button id="setLogo" type="button" class="btn btn-primary">Aceptar</button>
			</div>
		</div>
	</div>
</div>


