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
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-12 col-xl-10">
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
        <h1 class="mb-2 h3">Nuevo cliente</h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
            obligatorios</p>
        <div class="card bg-white shadow">
            <div class="card-body p-3">
                <form id="customerForm" method="post"
                      action="<?php echo base_url('admin/customers/new_customer_validation') ?>">
                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Nombre</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="name"
                                       value="<?php echo $old['name'] ?>"
                                       placeholder="Ej. Juan">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Apellidos</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="last_name"
                                       value="<?php echo $old['last_name'] ?>"
                                       placeholder="Ej. Hernández">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Empresa</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="company"
                                       value="<?php echo $old['company'] ?>"
                                       placeholder='Ej. Papeleria "El Lapicito"'>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>RFC</label>
                                <input id="rfc"
                                       autocomplete="off"
                                       type="text"
                                       class="text-uppercase form-control"
                                       name="rfc"
                                       value="<?php echo $old['rfc'] ?>"
                                       placeholder="Ej. XAXX010101000"
                                       maxlength="13">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>Correo electrónico</label>
                                <input id="email"
                                       autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="email"
                                       value="<?php echo $old['email'] ?>"
                                       placeholder="Ej. juanh@ejemploc.com">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Teléfono</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="phone form-control"
                                       name="phone"
                                       value="<?php echo $old['phone'] ?>"
                                       placeholder="Ej. 998-123-4567">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label>Teléfono celular</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="phone form-control"
                                       name="mobile_phone"
                                       value="<?php echo $old['mobile_phone'] ?>"
                                       placeholder="Ej. 998-123-4567">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Dirección</label>
                        <textarea autocomplete="off"
                                  type="text"
                                  class="form-control"
                                  name="address"
                                  rows="3"
                                  placeholder="Avenida Siempreviva 742"><?php echo $old['address'] ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Ciudad</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="city"
                                       value="<?php echo isset($old['city']) ? $old['city'] : 'Cancún' ?>"
                                       placeholder="Ej. Cancún">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Estado</label>
                                <input autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="state"
                                       value="<?php echo isset($old['state']) ? $old['state'] : 'Q. Roo' ?>"
                                       placeholder="Ej. Q. Roo">
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>País</label>
                                <select autocomplete="off"
                                        type="text"
                                        class="select2 form-control"
                                        name="country"
                                        value="<?php echo $old['country'] ?>">
                                    <option value="Afganistán">Afganistán</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Alemania">Alemania</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Antigua y Barbuda">Antigua y Barbuda</option>
                                    <option value="Arabia Saudita">Arabia Saudita</option>
                                    <option value="Argelia">Argelia</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaiyán">Azerbaiyán</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bangladés">Bangladés</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Baréin">Baréin</option>
                                    <option value="Bélgica">Bélgica</option>
                                    <option value="Belice">Belice</option>
                                    <option value="Benín">Benín</option>
                                    <option value="Bielorrusia">Bielorrusia</option>
                                    <option value="Birmania">Birmania</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bosnia y Herzegovina">Bosnia y Herzegovina</option>
                                    <option value="Botsuana">Botsuana</option>
                                    <option value="Brasil">Brasil</option>
                                    <option value="Brunéi">Brunéi</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Bután">Bután</option>
                                    <option value="Cabo Verde">Cabo Verde</option>
                                    <option value="Camboya">Camboya</option>
                                    <option value="Camerún">Camerún</option>
                                    <option value="Canadá">Canadá</option>
                                    <option value="Catar">Catar</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Chipre">Chipre</option>
                                    <option value="Ciudad del Vaticano">Ciudad del Vaticano</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoras">Comoras</option>
                                    <option value="Corea del Norte">Corea del Norte</option>
                                    <option value="Corea del Sur">Corea del Sur</option>
                                    <option value="Costa de Marfil">Costa de Marfil</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Croacia">Croacia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Dinamarca">Dinamarca</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egipto">Egipto</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Emiratos Árabes Unidos">Emiratos Árabes Unidos</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Eslovaquia">Eslovaquia</option>
                                    <option value="Eslovenia">Eslovenia</option>
                                    <option value="España">España</option>
                                    <option value="Estados Unidos">Estados Unidos</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Etiopía">Etiopía</option>
                                    <option value="Filipinas">Filipinas</option>
                                    <option value="Finlandia">Finlandia</option>
                                    <option value="Fiyi">Fiyi</option>
                                    <option value="Francia">Francia</option>
                                    <option value="Gabón">Gabón</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Granada">Granada</option>
                                    <option value="Grecia">Grecia</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guinea ecuatorial">Guinea ecuatorial</option>
                                    <option value="Guinea-Bisáu">Guinea-Bisáu</option>
                                    <option value="Haití">Haití</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hungría">Hungría</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Irak">Irak</option>
                                    <option value="Irán">Irán</option>
                                    <option value="Irlanda">Irlanda</option>
                                    <option value="Islandia">Islandia</option>
                                    <option value="Islas Marshall">Islas Marshall</option>
                                    <option value="Islas Salomón">Islas Salomón</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italia">Italia</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japón">Japón</option>
                                    <option value="Jordania">Jordania</option>
                                    <option value="Kazajistán">Kazajistán</option>
                                    <option value="Kenia">Kenia</option>
                                    <option value="Kirguistán">Kirguistán</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Lesoto">Lesoto</option>
                                    <option value="Letonia">Letonia</option>
                                    <option value="Líbano">Líbano</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libia">Libia</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lituania">Lituania</option>
                                    <option value="Luxemburgo">Luxemburgo</option>
                                    <option value="Macedonia del Norte">Macedonia del Norte</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malasia">Malasia</option>
                                    <option value="Malaui">Malaui</option>
                                    <option value="Maldivas">Maldivas</option>
                                    <option value="Malí">Malí</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marruecos">Marruecos</option>
                                    <option value="Mauricio">Mauricio</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="México" selected>México</option>
                                    <option value="Micronesia">Micronesia</option>
                                    <option value="Moldavia">Moldavia</option>
                                    <option value="Mónaco">Mónaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Namibia">Namibia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Níger">Níger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Noruega">Noruega</option>
                                    <option value="Nueva Zelanda">Nueva Zelanda</option>
                                    <option value="Omán">Omán</option>
                                    <option value="Países Bajos">Países Bajos</option>
                                    <option value="Pakistán">Pakistán</option>
                                    <option value="Palaos">Palaos</option>
                                    <option value="Panamá">Panamá</option>
                                    <option value="Papúa Nueva Guinea">Papúa Nueva Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Perú">Perú</option>
                                    <option value="Polonia">Polonia</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Reino Unido">Reino Unido</option>
                                    <option value="República Centroafricana">República Centroafricana</option>
                                    <option value="República Checa">República Checa</option>
                                    <option value="República del Congo">República del Congo</option>
                                    <option value="República Democrática del Congo">República Democrática del Congo
                                    </option>
                                    <option value="República Dominicana">República Dominicana</option>
                                    <option value="República Sudafricana">República Sudafricana</option>
                                    <option value="Ruanda">Ruanda</option>
                                    <option value="Rumanía">Rumanía</option>
                                    <option value="Rusia">Rusia</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="San Cristóbal y Nieves">San Cristóbal y Nieves</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="San Vicente y las Granadinas">San Vicente y las Granadinas</option>
                                    <option value="Santa Lucía">Santa Lucía</option>
                                    <option value="Santo Tomé y Príncipe">Santo Tomé y Príncipe</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Serbia">Serbia</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leona">Sierra Leona</option>
                                    <option value="Singapur">Singapur</option>
                                    <option value="Siria">Siria</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Suazilandia">Suazilandia</option>
                                    <option value="Sudán">Sudán</option>
                                    <option value="Sudán del Sur">Sudán del Sur</option>
                                    <option value="Suecia">Suecia</option>
                                    <option value="Suiza">Suiza</option>
                                    <option value="Surinam">Surinam</option>
                                    <option value="Tailandia">Tailandia</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Tayikistán">Tayikistán</option>
                                    <option value="Timor Oriental">Timor Oriental</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad y Tobago">Trinidad y Tobago</option>
                                    <option value="Túnez">Túnez</option>
                                    <option value="Turkmenistán">Turkmenistán</option>
                                    <option value="Turquía">Turquía</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Ucrania">Ucrania</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="Uruguay">Uruguay</option>
                                    <option value="Uzbekistán">Uzbekistán</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Yibuti">Yibuti</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabue">Zimbabue</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label>Código postal</label>
                                <input id="postalCode"
                                       autocomplete="off"
                                       type="text"
                                       class="form-control"
                                       name="postal_code"
                                       value="<?php echo isset($old['postal_code']) ? $old['postal_code'] : '77500' ?>"
                                       placeholder="Ej. 77500">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Notas</label>
                        <textarea autocomplete="off"
                                  type="text"
                                  class="form-control"
                                  name="notes"
                                  rows="3"
                                  placeholder="Ej. Prefiere que le hablen por teléfono celular"><?php echo $old['notes'] ?></textarea>
                    </div>
                    <div class="action-buttons">
                        <a href="<?php echo base_url('admin/clientes') ?>"
                           class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
                        <button type="submit" class="btn btn-lg btn-success ok-btn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>