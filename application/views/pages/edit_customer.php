<?php

if (isset($customer)) {
	$customer = (array)$customer;
}

$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);

//
//'rfc' => $customer['rfc'],


if (isset($old['rfc'])) {
	$rfc = $old['rfc'];
} else if (isset($customer['rfc'])) {
	$rfc = $customer['rfc'];
} else {
	$rfc = '';
}


//'nombre' => $customer['name'],


if (isset($old['name'])) {
	$name = $old['name'];
} else if (isset($customer['nombre'])) {
	$name = $customer['nombre'];
} else {
	$name = '';
}

//'apellidos' => $customer['last_name'],


if (isset($old['last_name'])) {
	$last_name = $old['last_name'];
} else if (isset($customer['apellidos'])) {
	$last_name = $customer['apellidos'];
} else {
	$last_name = '';
}


//'empresa' => $customer['company'],


if (isset($old['company'])) {
	$company = $old['company'];
} else if (isset($customer['empresa'])) {
	$company = $customer['empresa'];
} else {
	$company = '';
}


//'correo_electronico' => $customer['email'],


if (isset($old['email'])) {
	$email = $old['email'];
} else if (isset($customer['correo_electronico'])) {
	$email = $customer['correo_electronico'];
} else {
	$email = '';
}


//'telefono' => $customer['phone'],


if (isset($old['phone'])) {
	$phone = $old['phone'];
} else if (isset($customer['telefono'])) {
	$phone = $customer['telefono'];
} else {
	$phone = '';
}


//'telefono_celular' => $customer['mobile_phone'],


if (isset($old['mobile_phone'])) {
	$mobile_phone = $old['mobile_phone'];
} else if (isset($customer['telefono_celular'])) {
	$mobile_phone = $customer['telefono_celular'];
} else {
	$mobile_phone = '';
}

//'domicilio' => $customer['address'],


if (isset($old['address'])) {
	$address = $old['address'];
} else if (isset($customer['domicilio'])) {
	$address = $customer['domicilio'];
} else {
	$address = '';
}

//'ciudad' => $customer['city'],


if (isset($old['city'])) {
	$city = $old['city'];
} else if (isset($customer['ciudad'])) {
	$city = $customer['ciudad'];
} else {
	$city = '';
}


//'estado' => $customer['state'],


if (isset($old['state'])) {
	$state = $old['state'];
} else if (isset($customer['estado'])) {
	$state = $customer['estado'];
} else {
	$state = '';
}


//'pais' => $customer['country'],


if (isset($old['country'])) {
	$country = $old['country'];
} else if (isset($customer['pais'])) {
	$country = $customer['pais'];
} else {
	$country = '';
}


//'codigo_postal' => $customer['postal_code'],


if (isset($old['postal_code'])) {
	$postal_code = $old['postal_code'];
} else if (isset($customer['codigo_postal'])) {
	$postal_code = $customer['codigo_postal'];
} else {
	$postal_code = '';
}

//'notas' => $customer['notes'],

if (isset($old['notes'])) {
	$notes = $old['notes'];
} else if (isset($customer['notas'])) {
	$notes = $customer['notas'];
} else {
	$notes = '';
}


?>



<div class="row justify-content-center">
	<div class="col-12 col-md-12 col-xl-10">

		<div class="mb-3">
			<h3 class="mb-0">
				<span>Editar usuario #<?php echo isset($customer['id']) ? $customer['id'] : '' ?></span>
			</h3>
		</div>


		<div class="card bg-white shadow">
			<div class="card-body">
				<form method="post" action="<?php echo base_url('admin/customers/edit_customer_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
					<input type="hidden" name="id" value="<?php echo isset($customer['id']) ? $customer['id'] : '' ?>">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>Nombre</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['name']) ? ' is-invalid' : '' ?>"
									name="name"
									value="<?php echo $name ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['name']) ? $errors['name'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>Apellidos</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['last_name']) ? ' is-invalid' : '' ?>"
									name="last_name"
									value="<?php echo $last_name ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['last_name']) ? $errors['last_name'] : '' ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>Empresa</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['company']) ? ' is-invalid' : '' ?>"
									name="company"
									value="<?php echo $company ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['company']) ? $errors['company'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label>RFC</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['rfc']) ? ' is-invalid' : '' ?>"
									name="rfc"
									value="<?php echo $rfc ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['rfc']) ? $errors['rfc'] : '' ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-4">
							<div class="form-group">
								<label>Correo electrónico</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['email']) ? ' is-invalid' : '' ?>"
									name="email"
									value="<?php echo $email ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['email']) ? $errors['email'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group">
								<label>Teléfono</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['phone']) ? ' is-invalid' : '' ?>"
									name="phone"
									value="<?php echo $phone ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['phone']) ? $errors['phone'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="form-group">
								<label>Teléfono celular</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['mobile_phone']) ? ' is-invalid' : '' ?>"
									name="mobile_phone"
									value="<?php echo $mobile_phone ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['mobile_phone']) ? $errors['mobile_phone'] : '' ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Dirección</label>
						<textarea
							autocomplete="off"
							type="text"
							class="form-control<?php echo isset($errors['address']) ? ' is-invalid' : '' ?>"
							name="address"
							rows="3"><?php echo $address ?></textarea>
						<div class="invalid-feedback">
							<?php echo isset($errors['address']) ? $errors['address'] : '' ?>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label>Ciudad</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['city']) ? ' is-invalid' : '' ?>"
									name="city"
									value="<?php echo $city ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['city']) ? $errors['city'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label>Estado</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['state']) ? ' is-invalid' : '' ?>"
									name="state"
									value="<?php echo $state ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['state']) ? $errors['state'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label>País</label>
								<select
									autocomplete="off"
									type="text"
									class="select2 form-control<?php echo isset($errors['country']) ? ' is-invalid' : '' ?>"
									name="country">
									<option
										value="Afganistán"<?php echo $country === 'Afganistán' ? 'selected' : '' ?>>
										Afganistán
									</option>
									<option value="Albania"<?php echo $country === 'Albania' ? 'selected' : '' ?>>
										Albania
									</option>
									<option value="Alemania"<?php echo $country === 'Alemania' ? 'selected' : '' ?>>
										Alemania
									</option>
									<option value="Andorra"<?php echo $country === 'Andorra' ? 'selected' : '' ?>>
										Andorra
									</option>
									<option value="Angola"<?php echo $country === 'Angola' ? 'selected' : '' ?>>
										Angola
									</option>
									<option
										value="Antigua y Barbuda"<?php echo $country === 'Antigua y Barbuda' ? 'selected' : '' ?>>
										Antigua y Barbuda
									</option>
									<option
										value="Arabia Saudita"<?php echo $country === 'Arabia Saudita' ? 'selected' : '' ?>>
										Arabia Saudita
									</option>
									<option value="Argelia"<?php echo $country === 'Argelia' ? 'selected' : '' ?>>
										Argelia
									</option>
									<option value="Argentina"<?php echo $country === 'Argentina' ? 'selected' : '' ?>>
										Argentina
									</option>
									<option value="Armenia"<?php echo $country === 'Armenia' ? 'selected' : '' ?>>
										Armenia
									</option>
									<option value="Australia"<?php echo $country === 'Australia' ? 'selected' : '' ?>>
										Australia
									</option>
									<option value="Austria"<?php echo $country === 'Austria' ? 'selected' : '' ?>>
										Austria
									</option>
									<option
										value="Azerbaiyán"<?php echo $country === 'Azerbaiyán' ? 'selected' : '' ?>>
										Azerbaiyán
									</option>
									<option value="Bahamas"<?php echo $country === 'Bahamas' ? 'selected' : '' ?>>
										Bahamas
									</option>
									<option value="Bangladés"<?php echo $country === 'Bangladés' ? 'selected' : '' ?>>
										Bangladés
									</option>
									<option value="Barbados"<?php echo $country === 'Barbados' ? 'selected' : '' ?>>
										Barbados
									</option>
									<option value="Baréin"<?php echo $country === 'Baréin' ? 'selected' : '' ?>>
										Baréin
									</option>
									<option value="Bélgica"<?php echo $country === 'Bélgica' ? 'selected' : '' ?>>
										Bélgica
									</option>
									<option value="Belice"<?php echo $country === 'Belice' ? 'selected' : '' ?>>
										Belice
									</option>
									<option value="Benín"<?php echo $country === 'Benín' ? 'selected' : '' ?>>
										Benín
									</option>
									<option
										value="Bielorrusia"<?php echo $country === 'Bielorrusia' ? 'selected' : '' ?>>
										Bielorrusia
									</option>
									<option value="Birmania"<?php echo $country === 'Birmania' ? 'selected' : '' ?>>
										Birmania
									</option>
									<option value="Bolivia"<?php echo $country === 'Bolivia' ? 'selected' : '' ?>>
										Bolivia
									</option>
									<option
										value="Bosnia y Herzegovina"<?php echo $country === 'Bosnia y Herzegovina' ? 'selected' : '' ?>>
										Bosnia y Herzegovina
									</option>
									<option value="Botsuana"<?php echo $country === 'Botsuana' ? 'selected' : '' ?>>
										Botsuana
									</option>
									<option value="Brasil"<?php echo $country === 'Brasil' ? 'selected' : '' ?>>
										Brasil
									</option>
									<option value="Brunéi"<?php echo $country === 'Brunéi' ? 'selected' : '' ?>>
										Brunéi
									</option>
									<option value="Bulgaria"<?php echo $country === 'Bulgaria' ? 'selected' : '' ?>>
										Bulgaria
									</option>
									<option
										value="Burkina Faso"<?php echo $country === 'Burkina Faso' ? 'selected' : '' ?>>
										Burkina Faso
									</option>
									<option value="Burundi"<?php echo $country === 'Burundi' ? 'selected' : '' ?>>
										Burundi
									</option>
									<option value="Bután"<?php echo $country === 'Bután' ? 'selected' : '' ?>>
										Bután
									</option>
									<option
										value="Cabo Verde"<?php echo $country === 'Cabo Verde' ? 'selected' : '' ?>>
										Cabo Verde
									</option>
									<option value="Camboya"<?php echo $country === 'Camboya' ? 'selected' : '' ?>>
										Camboya
									</option>
									<option value="Camerún"<?php echo $country === 'Camerún' ? 'selected' : '' ?>>
										Camerún
									</option>
									<option value="Canadá"<?php echo $country === 'Canadá' ? 'selected' : '' ?>>
										Canadá
									</option>
									<option value="Catar"<?php echo $country === 'Catar' ? 'selected' : '' ?>>
										Catar
									</option>
									<option value="Chad"<?php echo $country === 'Chad' ? 'selected' : '' ?>>
										Chad
									</option>
									<option value="Chile"<?php echo $country === 'Chile' ? 'selected' : '' ?>>
										Chile
									</option>
									<option value="China"<?php echo $country === 'China' ? 'selected' : '' ?>>
										China
									</option>
									<option value="Chipre"<?php echo $country === 'Chipre' ? 'selected' : '' ?>>
										Chipre
									</option>
									<option
										value="Ciudad del Vaticano"<?php echo $country === 'Ciudad del Vaticano' ? 'selected' : '' ?>>
										Ciudad del Vaticano
									</option>
									<option value="Colombia"<?php echo $country === 'Colombia' ? 'selected' : '' ?>>
										Colombia
									</option>
									<option value="Comoras"<?php echo $country === 'Comoras' ? 'selected' : '' ?>>
										Comoras
									</option>
									<option
										value="Corea del Norte"<?php echo $country === 'Corea del Norte' ? 'selected' : '' ?>>
										Corea del Norte
									</option>
									<option
										value="Corea del Sur"<?php echo $country === 'Corea del Sur' ? 'selected' : '' ?>>
										Corea del Sur
									</option>
									<option
										value="Costa de Marfil"<?php echo $country === 'Costa de Marfil' ? 'selected' : '' ?>>
										Costa de Marfil
									</option>
									<option
										value="Costa Rica"<?php echo $country === 'Costa Rica' ? 'selected' : '' ?>>
										Costa Rica
									</option>
									<option value="Croacia"<?php echo $country === 'Croacia' ? 'selected' : '' ?>>
										Croacia
									</option>
									<option value="Cuba"<?php echo $country === 'Cuba' ? 'selected' : '' ?>>
										Cuba
									</option>
									<option value="Dinamarca"<?php echo $country === 'Dinamarca' ? 'selected' : '' ?>>
										Dinamarca
									</option>
									<option value="Dominica"<?php echo $country === 'Dominica' ? 'selected' : '' ?>>
										Dominica
									</option>
									<option value="Ecuador"<?php echo $country === 'Ecuador' ? 'selected' : '' ?>>
										Ecuador
									</option>
									<option value="Egipto"<?php echo $country === 'Egipto' ? 'selected' : '' ?>>
										Egipto
									</option>
									<option
										value="El Salvador"<?php echo $country === 'El Salvador' ? 'selected' : '' ?>>
										El Salvador
									</option>
									<option
										value="Emiratos Árabes Unidos"<?php echo $country === 'Emiratos Árabes Unidos' ? 'selected' : '' ?>>
										Emiratos Árabes Unidos
									</option>
									<option value="Eritrea"<?php echo $country === 'Eritrea' ? 'selected' : '' ?>>
										Eritrea
									</option>
									<option
										value="Eslovaquia"<?php echo $country === 'Eslovaquia' ? 'selected' : '' ?>>
										Eslovaquia
									</option>
									<option value="Eslovenia"<?php echo $country === 'Eslovenia' ? 'selected' : '' ?>>
										Eslovenia
									</option>
									<option value="España"<?php echo $country === 'España' ? 'selected' : '' ?>>
										España
									</option>
									<option
										value="Estados Unidos"<?php echo $country === 'Estados Unidos' ? 'selected' : '' ?>>
										Estados Unidos
									</option>
									<option value="Estonia"<?php echo $country === 'Estonia' ? 'selected' : '' ?>>
										Estonia
									</option>
									<option value="Etiopía"<?php echo $country === 'Etiopía' ? 'selected' : '' ?>>
										Etiopía
									</option>
									<option value="Filipinas"<?php echo $country === 'Filipinas' ? 'selected' : '' ?>>
										Filipinas
									</option>
									<option value="Finlandia"<?php echo $country === 'Finlandia' ? 'selected' : '' ?>>
										Finlandia
									</option>
									<option value="Fiyi"<?php echo $country === 'Fiyi' ? 'selected' : '' ?>>
										Fiyi
									</option>
									<option value="Francia"<?php echo $country === 'Francia' ? 'selected' : '' ?>>
										Francia
									</option>
									<option value="Gabón"<?php echo $country === 'Gabón' ? 'selected' : '' ?>>
										Gabón
									</option>
									<option value="Gambia"<?php echo $country === 'Gambia' ? 'selected' : '' ?>>
										Gambia
									</option>
									<option value="Georgia"<?php echo $country === 'Georgia' ? 'selected' : '' ?>>
										Georgia
									</option>
									<option value="Ghana"<?php echo $country === 'Ghana' ? 'selected' : '' ?>>
										Ghana
									</option>
									<option value="Granada"<?php echo $country === 'Granada' ? 'selected' : '' ?>>
										Granada
									</option>
									<option value="Grecia"<?php echo $country === 'Grecia' ? 'selected' : '' ?>>
										Grecia
									</option>
									<option value="Guatemala"<?php echo $country === 'Guatemala' ? 'selected' : '' ?>>
										Guatemala
									</option>
									<option value="Guyana"<?php echo $country === 'Guyana' ? 'selected' : '' ?>>
										Guyana
									</option>
									<option value="Guinea"<?php echo $country === 'Guinea' ? 'selected' : '' ?>>
										Guinea
									</option>
									<option
										value="Guinea ecuatorial"<?php echo $country === 'Guinea ecuatorial' ? 'selected' : '' ?>>
										Guinea ecuatorial
									</option>
									<option
										value="Guinea-Bisáu"<?php echo $country === 'Guinea-Bisáu' ? 'selected' : '' ?>>
										Guinea-Bisáu
									</option>
									<option value="Haití"<?php echo $country === 'Haití' ? 'selected' : '' ?>>Haití
									</option>
									<option value="Honduras"<?php echo $country === 'Honduras' ? 'selected' : '' ?>>
										Honduras
									</option>
									<option value="Hungría"<?php echo $country === 'Hungría' ? 'selected' : '' ?>>
										Hungría
									</option>
									<option value="India"<?php echo $country === 'India' ? 'selected' : '' ?>>
										India
									</option>
									<option value="Indonesia"<?php echo $country === 'Indonesia' ? 'selected' : '' ?>>
										Indonesia
									</option>
									<option value="Irak"<?php echo $country === 'Irak' ? 'selected' : '' ?>>
										Irak
									</option>
									<option value="Irán"<?php echo $country === 'Irán' ? 'selected' : '' ?>>
										Irán
									</option>
									<option value="Irlanda"<?php echo $country === 'Irlanda' ? 'selected' : '' ?>>
										Irlanda
									</option>
									<option value="Islandia"<?php echo $country === 'Islandia' ? 'selected' : '' ?>>
										Islandia
									</option>
									<option
										value="Islas Marshall"<?php echo $country === 'Islas Marshall' ? 'selected' : '' ?>>
										Islas Marshall
									</option>
									<option
										value="Islas Salomón"<?php echo $country === 'Islas Salomón' ? 'selected' : '' ?>>
										Islas Salomón
									</option>
									<option value="Israel"<?php echo $country === 'Israel' ? 'selected' : '' ?>>
										Israel
									</option>
									<option value="Italia"<?php echo $country === 'Italia' ? 'selected' : '' ?>>
										Italia
									</option>
									<option value="Jamaica"<?php echo $country === 'Jamaica' ? 'selected' : '' ?>>
										Jamaica
									</option>
									<option value="Japón"<?php echo $country === 'Japón' ? 'selected' : '' ?>>Japón
									</option>
									<option value="Jordania"<?php echo $country === 'Jordania' ? 'selected' : '' ?>>
										Jordania
									</option>
									<option
										value="Kazajistán"<?php echo $country === 'Kazajistán' ? 'selected' : '' ?>>
										Kazajistán
									</option>
									<option value="Kenia"<?php echo $country === 'Kenia' ? 'selected' : '' ?>>
										Kenia
									</option>
									<option
										value="Kirguistán"<?php echo $country === 'Kirguistán' ? 'selected' : '' ?>>
										Kirguistán
									</option>
									<option value="Kiribati"<?php echo $country === 'Kiribati' ? 'selected' : '' ?>>
										Kiribati
									</option>
									<option value="Kuwait"<?php echo $country === 'Kuwait' ? 'selected' : '' ?>>
										Kuwait
									</option>
									<option value="Laos"<?php echo $country === 'Laos' ? 'selected' : '' ?>>Laos
									</option>
									<option value="Lesoto"<?php echo $country === 'Lesoto' ? 'selected' : '' ?>>
										Lesoto
									</option>
									<option value="Letonia"<?php echo $country === 'Letonia' ? 'selected' : '' ?>>
										Letonia
									</option>
									<option value="Líbano"<?php echo $country === 'Líbano' ? 'selected' : '' ?>>
										Líbano
									</option>
									<option value="Liberia"<?php echo $country === 'Liberia' ? 'selected' : '' ?>>
										Liberia
									</option>
									<option value="Libia"<?php echo $country === 'Libia' ? 'selected' : '' ?>>
										Libia
									</option>
									<option
										value="Liechtenstein"<?php echo $country === 'Liechtenstein' ? 'selected' : '' ?>>
										Liechtenstein
									</option>
									<option value="Lituania"<?php echo $country === 'Lituania' ? 'selected' : '' ?>>
										Lituania
									</option>
									<option
										value="Luxemburgo"<?php echo $country === 'Luxemburgo' ? 'selected' : '' ?>>
										Luxemburgo
									</option>
									<option
										value="Macedonia del Norte"<?php echo $country === 'Macedonia del Norte' ? 'selected' : '' ?>>
										Macedonia del Norte
									</option>
									<option
										value="Madagascar"<?php echo $country === 'Madagascar' ? 'selected' : '' ?>>
										Madagascar
									</option>
									<option value="Malasia"<?php echo $country === 'Malasia' ? 'selected' : '' ?>>
										Malasia
									</option>
									<option value="Malaui"<?php echo $country === 'Malaui' ? 'selected' : '' ?>>
										Malaui
									</option>
									<option value="Maldivas"<?php echo $country === 'Maldivas' ? 'selected' : '' ?>>
										Maldivas
									</option>
									<option value="Malí"<?php echo $country === 'Malí' ? 'selected' : '' ?>>
										Malí
									</option>
									<option value="Malta"<?php echo $country === 'Malta' ? 'selected' : '' ?>>
										Malta
									</option>
									<option value="Marruecos"<?php echo $country === 'Marruecos' ? 'selected' : '' ?>>
										Marruecos
									</option>
									<option value="Mauricio"<?php echo $country === 'Mauricio' ? 'selected' : '' ?>>
										Mauricio
									</option>
									<option
										value="Mauritania"<?php echo $country === 'Mauritania' ? 'selected' : '' ?>>
										Mauritania
									</option>
									<option value="México"<?php echo $country === 'México' ? 'selected' : '' ?>>
										México
									</option>
									<option
										value="Micronesia"<?php echo $country === 'Micronesia' ? 'selected' : '' ?>>
										Micronesia
									</option>
									<option value="Moldavia"<?php echo $country === 'Moldavia' ? 'selected' : '' ?>>
										Moldavia
									</option>
									<option value="Mónaco"<?php echo $country === 'Mónaco' ? 'selected' : '' ?>>
										Mónaco
									</option>
									<option value="Mongolia"<?php echo $country === 'Mongolia' ? 'selected' : '' ?>>
										Mongolia
									</option>
									<option
										value="Montenegro"<?php echo $country === 'Montenegro' ? 'selected' : '' ?>>
										Montenegro
									</option>
									<option
										value="Mozambique"<?php echo $country === 'Mozambique' ? 'selected' : '' ?>>
										Mozambique
									</option>
									<option value="Namibia"<?php echo $country === 'Namibia' ? 'selected' : '' ?>>
										Namibia
									</option>
									<option value="Nauru"<?php echo $country === 'Nauru' ? 'selected' : '' ?>>
										Nauru
									</option>
									<option value="Nepal"<?php echo $country === 'Nepal' ? 'selected' : '' ?>>
										Nepal
									</option>
									<option value="Nicaragua"<?php echo $country === 'Nicaragua' ? 'selected' : '' ?>>
										Nicaragua
									</option>
									<option value="Níger"<?php echo $country === 'Níger' ? 'selected' : '' ?>>
										Níger
									</option>
									<option value="Nigeria"<?php echo $country === 'Nigeria' ? 'selected' : '' ?>>
										Nigeria
									</option>
									<option value="Noruega"<?php echo $country === 'Noruega' ? 'selected' : '' ?>>
										Noruega
									</option>
									<option
										value="Nueva Zelanda"<?php echo $country === 'Nueva Zelanda' ? 'selected' : '' ?>>
										Nueva Zelanda
									</option>
									<option value="Omán"<?php echo $country === 'Omán' ? 'selected' : '' ?>>
										Omán
									</option>
									<option
										value="Países Bajos"<?php echo $country === 'Países Bajos' ? 'selected' : '' ?>>
										Países Bajos
									</option>
									<option value="Pakistán"<?php echo $country === 'Pakistán' ? 'selected' : '' ?>>
										Pakistán
									</option>
									<option value="Palaos"<?php echo $country === 'Palaos' ? 'selected' : '' ?>>
										Palaos
									</option>
									<option value="Panamá"<?php echo $country === 'Panamá' ? 'selected' : '' ?>>
										Panamá
									</option>
									<option
										value="Papúa Nueva Guinea"<?php echo $country === 'Papúa Nueva Guinea' ? 'selected' : '' ?>>
										Papúa Nueva Guinea
									</option>
									<option value="Paraguay"<?php echo $country === 'Paraguay' ? 'selected' : '' ?>>
										Paraguay
									</option>
									<option value="Perú"<?php echo $country === 'Perú' ? 'selected' : '' ?>>Perú
									</option>
									<option value="Polonia"<?php echo $country === 'Polonia' ? 'selected' : '' ?>>
										Polonia
									</option>
									<option value="Portugal"<?php echo $country === 'Portugal' ? 'selected' : '' ?>>
										Portugal
									</option>
									<option
										value="Reino Unido"<?php echo $country === 'Reino Unido' ? 'selected' : '' ?>>
										Reino Unido
									</option>
									<option
										value="República Centroafricana"<?php echo $country === 'República Centroafricana' ? 'selected' : '' ?>>
										República Centroafricana
									</option>
									<option
										value="República Checa"<?php echo $country === 'República Checa' ? 'selected' : '' ?>>
										República Checa
									</option>
									<option
										value="República del Congo"<?php echo $country === 'República del Congo' ? 'selected' : '' ?>>
										República del Congo
									</option>
									<option
										value="República Democrática del Congo"<?php echo $country === 'República Democrática del Congo' ? 'selected' : '' ?>>
										República Democrática del Congo
									</option>
									<option
										value="República Dominicana"<?php echo $country === 'República Dominicana' ? 'selected' : '' ?>>
										República Dominicana
									</option>
									<option
										value="República Sudafricana"<?php echo $country === 'República Sudafricana' ? 'selected' : '' ?>>
										República Sudafricana
									</option>
									<option value="Ruanda"<?php echo $country === 'Ruanda' ? 'selected' : '' ?>>
										Ruanda
									</option>
									<option value="Rumanía"<?php echo $country === 'Rumanía' ? 'selected' : '' ?>>
										Rumanía
									</option>
									<option value="Rusia"<?php echo $country === 'Rusia' ? 'selected' : '' ?>>
										Rusia
									</option>
									<option value="Samoa"<?php echo $country === 'Samoa' ? 'selected' : '' ?>>
										Samoa
									</option>
									<option
										value="San Cristóbal y Nieves"<?php echo $country === 'San Cristóbal y Nieves' ? 'selected' : '' ?>>
										San Cristóbal y Nieves
									</option>
									<option
										value="San Marino"<?php echo $country === 'San Marino' ? 'selected' : '' ?>>
										San Marino
									</option>
									<option
										value="San Vicente y las Granadinas"<?php echo $country === 'San Vicente y las Granadinas' ? 'selected' : '' ?>>
										San Vicente y las Granadinas
									</option>
									<option
										value="Santa Lucía"<?php echo $country === 'Santa Lucía' ? 'selected' : '' ?>>
										Santa Lucía
									</option>
									<option
										value="Santo Tomé y Príncipe"<?php echo $country === 'Santo Tomé y Príncipe' ? 'selected' : '' ?>>
										Santo Tomé y Príncipe
									</option>
									<option value="Senegal"<?php echo $country === 'Senegal' ? 'selected' : '' ?>>
										Senegal
									</option>
									<option value="Serbia"<?php echo $country === 'Serbia' ? 'selected' : '' ?>>
										Serbia
									</option>
									<option
										value="Seychelles"<?php echo $country === 'Seychelles' ? 'selected' : '' ?>>
										Seychelles
									</option>
									<option
										value="Sierra Leona"<?php echo $country === 'Sierra Leona' ? 'selected' : '' ?>>
										Sierra Leona
									</option>
									<option value="Singapur"<?php echo $country === 'Singapur' ? 'selected' : '' ?>>
										Singapur
									</option>
									<option value="Siria"<?php echo $country === 'Siria' ? 'selected' : '' ?>>Siria
									</option>
									<option value="Somalia"<?php echo $country === 'Somalia' ? 'selected' : '' ?>>
										Somalia
									</option>
									<option value="Sri Lanka"<?php echo $country === 'Sri Lanka' ? 'selected' : '' ?>>
										Sri Lanka
									</option>
									<option
										value="Suazilandia"<?php echo $country === 'Suazilandia' ? 'selected' : '' ?>>
										Suazilandia
									</option>
									<option value="Sudán"<?php echo $country === 'Sudán' ? 'selected' : '' ?>>
										Sudán
									</option>
									<option
										value="Sudán del Sur"<?php echo $country === 'Sudán del Sur' ? 'selected' : '' ?>>
										Sudán del Sur
									</option>
									<option value="Suecia"<?php echo $country === 'Suecia' ? 'selected' : '' ?>>
										Suecia
									</option>
									<option value="Suiza"<?php echo $country === 'Suiza' ? 'selected' : '' ?>>Suiza
									</option>
									<option value="Surinam"<?php echo $country === 'Surinam' ? 'selected' : '' ?>>
										Surinam
									</option>
									<option value="Tailandia"<?php echo $country === 'Tailandia' ? 'selected' : '' ?>>
										Tailandia
									</option>
									<option value="Tanzania"<?php echo $country === 'Tanzania' ? 'selected' : '' ?>>
										Tanzania
									</option>
									<option
										value="Tayikistán"<?php echo $country === 'Tayikistán' ? 'selected' : '' ?>>
										Tayikistán
									</option>
									<option
										value="Timor Oriental"<?php echo $country === 'Timor Oriental' ? 'selected' : '' ?>>
										Timor Oriental
									</option>
									<option value="Togo"<?php echo $country === 'Togo' ? 'selected' : '' ?>>
										Togo
									</option>
									<option value="Tonga"<?php echo $country === 'Tonga' ? 'selected' : '' ?>>
										Tonga
									</option>
									<option
										value="Trinidad y Tobago"<?php echo $country === 'Trinidad y Tobago' ? 'selected' : '' ?>>
										Trinidad y Tobago
									</option>
									<option value="Túnez"<?php echo $country === 'Túnez' ? 'selected' : '' ?>>Túnez
									</option>
									<option
										value="Turkmenistán"<?php echo $country === 'Turkmenistán' ? 'selected' : '' ?>>
										Turkmenistán
									</option>
									<option value="Turquía"<?php echo $country === 'Turquía' ? 'selected' : '' ?>>
										Turquía
									</option>
									<option value="Tuvalu"<?php echo $country === 'Tuvalu' ? 'selected' : '' ?>>
										Tuvalu
									</option>
									<option value="Ucrania"<?php echo $country === 'Ucrania' ? 'selected' : '' ?>>
										Ucrania
									</option>
									<option value="Uganda"<?php echo $country === 'Uganda' ? 'selected' : '' ?>>
										Uganda
									</option>
									<option value="Uruguay"<?php echo $country === 'Uruguay' ? 'selected' : '' ?>>
										Uruguay
									</option>
									<option
										value="Uzbekistán"<?php echo $country === 'Uzbekistán' ? 'selected' : '' ?>>
										Uzbekistán
									</option>
									<option value="Vanuatu"<?php echo $country === 'Vanuatu' ? 'selected' : '' ?>>
										Vanuatu
									</option>
									<option value="Venezuela"<?php echo $country === 'Venezuela' ? 'selected' : '' ?>>
										Venezuela
									</option>
									<option value="Vietnam"<?php echo $country === 'Vietnam' ? 'selected' : '' ?>>
										Vietnam
									</option>
									<option value="Yemen"<?php echo $country === 'Yemen' ? 'selected' : '' ?>>
										Yemen
									</option>
									<option value="Yibuti"<?php echo $country === 'Yibuti' ? 'selected' : '' ?>>
										Yibuti
									</option>
									<option value="Zambia"<?php echo $country === 'Zambia' ? 'selected' : '' ?>>
										Zambia
									</option>
									<option value="Zimbabue"<?php echo $country === 'Zimbabue' ? 'selected' : '' ?>>
										Zimbabue
									</option>
								</select>
								<div class="invalid-feedback">
									<?php echo isset($errors['country']) ? $errors['country'] : '' ?>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-3">
							<div class="form-group">
								<label>Código postal</label>
								<input
									autocomplete="off"
									type="text"
									class="form-control<?php echo isset($errors['postal_code']) ? ' is-invalid' : '' ?>"
									name="postal_code"
									value="<?php echo $postal_code ?>">
								<div class="invalid-feedback">
									<?php echo isset($errors['postal_code']) ? $errors['postal_code'] : '' ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label>Notas</label>
						<textarea
							autocomplete="off"
							type="text"
							class="form-control<?php echo isset($errors['notes']) ? ' is-invalid' : '' ?>"
							name="notes"
							rows="3"><?php echo $notes ?></textarea>
						<div class="invalid-feedback">
							<?php echo isset($errors['notes']) ? $errors['notes'] : '' ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<a href="<?php echo base_url('admin/clientes') ?>" class="btn btn-lg btn-secondary">Cancelar</a>
						<button type="submit" class="btn btn-lg btn-success">Guardar cambios</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
