<?php
$estimate = (isset($estimate)) ? $estimate : NULL;
$lines = (isset($lines)) ? $lines : NULL;
$customer = (isset($customer)) ? $customer : NULL;
$current_date_ymd = date('Y-m-d', strtotime($estimate->fecha_presupuesto));
$current_date_dmy = date('d/m/Y', strtotime($estimate->fecha_presupuesto));
$due_date_dmy = date('d/m/Y', strtotime($estimate->fecha_vencimiento));
$due_date_ymd = date('Y-m-d', strtotime($estimate->fecha_vencimiento));
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
$full_name = $customer->nombre . ' ' . $customer->apellidos;
$phone = $customer->telefono;
$mobilePhone = $customer->telefono_celular;
$phone = ($phone == '' && $mobilePhone != '') ? $mobilePhone : $phone;
$full_address_array = [];
$address = $customer->domicilio;
$city = $customer->ciudad;
$state = $customer->estado;
$country = $customer->pais;
$postal_code = $customer->codigo_postal;
if ($address != '') {
    array_push($full_address_array, $address);
}
if ($city != '') {
    array_push($full_address_array, $city);
}
if ($state != '') {
    array_push($full_address_array, $state);
}
if ($country != '') {
    array_push($full_address_array, $country);
}
if ($postal_code != '') {
    array_push($full_address_array, $postal_code);
}
$full_address = '';
foreach ($full_address_array as $key => $val) {
    if ($key != count($full_address_array)) {
        $full_address .= $val . ' ';
    } else {
        $full_address .= $val;
    }
}
$flash_message = $this->session->flashdata('flash_message');
$flash_msg_data_attr = '';
if (isset($flash_message["type"]) && isset($flash_message["title"])) {
    $flash_msg_data_attr = 'data-flash-msg-type="' . $flash_message["type"] . '"';
    $flash_msg_data_attr .= ' data-flash-msg-title="' . $flash_message["title"] . '"';
}
?>
<div class="row justify-content-center">
    <div class="col-12">
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
        <h1 class="mb-2 h3">Editar presupuesto</h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
            obligatorios</p>
        <div class="document-container">
            <form id="documentForm"
                  method="post"
                  action="<?php echo base_url('admin/estimates/edit_estimate_validation') ?>"
                <?php echo $flash_msg_data_attr ?> >
                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                <input type="hidden" name="id" value="<?php echo isset($estimate->id) ? $estimate->id : '' ?>">
                <div class="meta-data">
                    <div class="logo">
                        <img src="/assets/img/logo.jpg" alt="">
                    </div>
                    <div class="company-info">
                        <p>
                            Reg. 219, Mza. 27, Lte. 14,
                            a 2 cuadras de de la Av. Talleres
                            1era. entrada de la Reg. 94,
                            Cancún, Q. Roo.
                        </p>
                    </div>
                    <div class="number-and-date">
                        <div class="panel-container">
                            <div class="number-panel">
                                <div class="panel-header">Presupuesto</div>
                                <div class="panel-body">
                                    <input type="hidden"
                                           name="number"
                                           value="<?php echo $estimate->folio ?>">
                                    <span class="number"><?php echo $estimate->folio ?></span>
                                    <input type="hidden"
                                           name="status"
                                           value="<?php echo $estimate->status ?>">
                                </div>
                            </div>
                            <div class="date-panel">
                                <div class="panel-header date-header">Fecha</div>
                                <div class="panel-body text-primary">
                                    <input id="date"
                                           type="hidden"
                                           name="date"
                                           value="<?php echo $current_date_ymd ?>">
                                    <input class="form-control"
                                           type="text"
                                           id="datepicker"
                                           value="<?php echo $current_date_dmy ?>"
                                           tabindex="-1"
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="due-date">
                        <label>Valido por</label>
                        <input id="dueDateInput"
                               type="hidden"
                               name="due_date"
                               value="<?php echo $due_date_ymd ?>">
                        <select name="validity_in_days"
                                class="custom-select"
                                id="dueDateDropdown"
                                autocomplete="off"
                                tabindex="-1">
                            <option value="7" <?php echo ($estimate->validez_en_dias == 7) ? 'selected' : '' ?>>7 días
                            </option>
                            <option value="15" <?php echo ($estimate->validez_en_dias == 15) ? 'selected' : '' ?>>15
                                días
                            </option>
                            <option value="30" <?php echo ($estimate->validez_en_dias == 30) ? 'selected' : '' ?>>30
                                días
                            </option>
                            <option value="60" <?php echo ($estimate->validez_en_dias == 60) ? 'selected' : '' ?>>60
                                días
                            </option>
                        </select>
                        <small class="ml-1 text-muted font-weight-bold"
                               id="dueDateText"><?php echo $due_date_dmy ?></small>
                    </div>
                    <div class="customer">
                        <div class="customer-select2">
                            <button id="addCustomerBtn" class="add-customer-btn d-none">
                                <i class="fas fa-asterisk text-danger"></i>
                                <span class="text">Agregar un cliente</span>
                            </button>
                            <span id="customerSelect2" tabindex="-1"></span>
                        </div>
                        <input type="hidden"
                               name="customer_id"
                               id="customerId"
                               value="<?php echo $estimate->cliente_id ?>"
                               data-type="customer">
                        <div class="customer-info" id="customerInfo">
							<span class="clear-btn" id="removeCustomerBtn">
								<i class="fas fa-times-circle"></i>
							</span>
                            <div class="field">
                                <span class="label">Nombre</span>
                                <span class="text" id="nameInput"><?php echo $full_name ?></span>
                            </div>
                            <div class="field">
                                <span class="label">Dirección</span>
                                <span class="text" id="addressInput"><?php echo $full_address ?></span>
                            </div>
                            <div class="field">
                                <span class="label">Teléfono</span>
                                <span class="text" id="phoneInput"><?php echo $phone ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="items-table" id="itemsTable">
                    <div class="table-head">
                        <div class="table-row">
                            <div class="header">Productos</div>
                            <div class="header">Cantidad</div>
                            <div class="header">Descripción</div>
                            <div class="header">P. unitario</div>
                            <div class="header">Importe</div>
                        </div>
                    </div>
                    <div class="table-body">
                        <?php foreach ($lines as $line): ?>
                            <div class="table-row">
                                <button class="remove-item-btn" tabindex="-1">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                                <div class="qty">
                                    <label class="font-weight-bold mb-1">
                                        <i class="fas fa-asterisk text-danger"></i>
                                        <span>Cantidad</span>
                                    </label>
                                    <input maxlength="10"
                                           type="tel"
                                           class="item-qty form-control"
                                           placeholder="Ej. 1"
                                           tabindex="-1"
                                           value="<?php echo $line->cantidad ?>"
                                           autocomplete="off"
                                    >
                                </div>
                                <div class="item-info">
                                    <input type="hidden" class="product-id">
                                    <label class="font-weight-bold mb-1">
                                        <i class="fas fa-asterisk text-danger"></i>
                                        <span>Nombre del producto</span>
                                    </label>
                                    <input type="text"
                                           class="item-name form-control"
                                           placeholder="Ej. Lápices de Colores"
                                           value="<?php echo $line->nombre ?>">
                                    <label class="font-weight-bold mt-1 mt-md-2 mb-1">Descripción del producto</label>
                                    <textarea class="item-description form-control mt-0"
                                              rows="1"
                                              placeholder='48 lápices de colores "Arcoíris" para dibujo'
                                              tabindex="-1"
                                              autocomplete="off"
                                    ><?php echo $line->descripcion ?></textarea>
                                </div>
                                <div class="unit-price">
                                    <label class="font-weight-bold mb-1">
                                        <i class="fas fa-asterisk text-danger"></i>
                                        <span>P. unitario</span>
                                    </label>
                                    <input maxlength="10"
                                           type="tel"
                                           class="form-control item-unit-price"
                                           placeholder="Ej. 249.33"
                                           tabindex="-1"
                                           autocomplete="off"

                                           value="<?php echo $line->precio_unitario ?>">
                                </div>
                                <div class="total-line">
                                    <span class="equal-sign">=</span>
                                    <span class="formatted-number text-truncate"><?php echo "$" . number_format($line->total, 2); ?></span>
                                    <input type="hidden"
                                           class="total-line-input"
                                           value="<?php echo floatval($line->total) ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="add-item">
                    <button id="addItemBtn" class="add-item-btn">
                        <i class="fas fa-asterisk text-danger"></i>
                        <span class="text">Agregar una fila</span>
                    </button>
                    <input type="hidden"
                           name="total_items"
                           id="totalOfItems"
                           value="<?php echo count($lines) ?>"
                           data-type="items">
                </div>
                <div class="summary-and-notes">
                    <div class="summary">
                        <div class="summary-item">
                            <div class="label"><span>Sub-total</span></div>
                            <div class="amount">
                                <input type="hidden"
                                       id="subTotal"
                                       name="sub_total"
                                       value="<?php echo floatval($estimate->sub_total) ?>">
                                <span class="formatted-number text-truncate"><?php echo "$" . number_format($estimate->sub_total, 2); ?></span>
                            </div>
                        </div>
                        <div class="summary-item" id="discountTypeInputGroup">
                            <div class="label">
                                <span><i class="fas fa-asterisk text-danger"></i>&nbsp;Descuento</span>
                                <div class="input-group discount-type-input-group mt-1">
                                    <div class="input-group-prepend">
                                        <button type="button"
                                                class="btn dropdown-toggle"
                                                data-toggle="dropdown">
											<span class="icon">
												<i class="fas <?php echo ($estimate->tipo_descuento == 'percentage') ? 'fa-percentage' : 'fa-dollar-sign' ?>"></i>
											</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-lg-right">
                                            <button class="dropdown-item" type="button" data-icon="fas fa-dollar-sign"
                                                    data-type="fixed">Fijo ($)
                                            </button>
                                            <button class="dropdown-item" type="button" data-icon="fas fa-percentage"
                                                    data-type="percentage">Porcentaje (%)
                                            </button>
                                            <input type="hidden"
                                                   id="discountType"
                                                   name="discount_type"
                                                   value="<?php echo $estimate->tipo_descuento ?>">
                                        </div>
                                    </div>
                                    <input type="text" id="discount"
                                           name="discount"
                                           class="form-control"
                                           placeholder="Ej. 10"
                                           value="<?php echo ($estimate->tipo_descuento == 'percentage') ? intval($estimate->descuento) : floatval($estimate->descuento) ?>"
                                           maxlength="10"

                                           autocomplete="off"
                                           tabindex="-1">
                                </div>
                            </div>
                            <div class="amount">
                                <input type="hidden"
                                       id="discountValInput"
                                       name="discount_val"
                                       value="<?php echo floatval($estimate->cantidad_descontada) ?>">
                                <span class="formatted-number text-truncate">
                                    <?php echo "-$" . number_format($estimate->cantidad_descontada, 2); ?>
                                </span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label">
                                <input type="hidden"
                                       id="includeTax"
                                       name="include_tax"
                                       value="<?php echo intval($estimate->incluir_impuesto) ?>">
                                <button type="button"
                                        id="taxCheckbox"
                                        class="tax-checkbox <?php echo (intval($estimate->incluir_impuesto) === 1) ? 'checked' : '' ?>">
                                    <i class="icon fas fa-check"></i>
                                </button>
                                <span>IVA (16%)</span>
                            </div>
                            <div class="amount">
                                <input type="hidden"
                                       id="tax"
                                       name="tax"
                                       value="<?php echo floatval($estimate->impuesto) ?>">
                                <span class="formatted-number text-truncate">
                                    <?php echo "$" . number_format($estimate->impuesto, 2); ?>
                                </span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label"><span>Total</span></div>
                            <div class="amount">
                                <input type="hidden"
                                       id="total"
                                       name="total"
                                       value="<?php echo floatval($estimate->total) ?>">
                                <span class="formatted-number text-truncate">
                                    <?php echo "$" . number_format($estimate->total, 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="notes">
						<textarea name="notes"
                                  class="form-control"
                                  placeholder="Ingrese notas o detalles de transferencia bancaria"
                                  tabindex="-1"
                        ><?php echo $estimate->notas ?></textarea>
                    </div>
                </div>
                <div class="action-buttons">
                    <a href="<?php echo base_url('admin/presupuestos') ?>"
                       class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
                    <button type="submit" class="btn btn-lg btn-success ok-btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/html" id="itemTemplate">
    <div class="table-row">
        <button class="remove-item-btn" tabindex="-1">
            <i class="fas fa-times-circle"></i>
        </button>
        <div class="qty">
            <label class="font-weight-bold mb-1">
                <i class="fas fa-asterisk text-danger"></i>
                <span>Cantidad</span>
            </label>
            <input maxlength="10"
                   type="tel"
                   class="item-qty form-control"
                   placeholder="Ej. 1"
                   tabindex="-1"
                   value="1"
                   autocomplete="off"
            >
        </div>
        <div class="item-info">
            <input type="hidden" class="product-id">
            <label class="font-weight-bold mb-1">
                <i class="fas fa-asterisk text-danger"></i>
                <span>Nombre del producto</span>
            </label>
            <input type="text" class="item-name form-control" placeholder="Ej. Lápices de Colores">
            <label class="font-weight-bold  mt-1 mt-md-2 mb-1">Descripción del producto</label>
            <textarea class="item-description form-control mt-0"
                      rows="1"
                      placeholder='48 lápices de colores "Arcoíris" para dibujo'
                      tabindex="-1"
                      autocomplete="off"
            ></textarea>
        </div>
        <div class="unit-price">
            <label class="font-weight-bold mb-1">
                <i class="fas fa-asterisk text-danger"></i>
                <span>P. unitario</span>
            </label>
            <input maxlength="10"
                   type="tel"
                   class="form-control item-unit-price"
                   placeholder="Ej. 249.33"
                   tabindex="-1"
                   autocomplete="off"
            >
        </div>
        <div class="total-line">
            <span class="equal-sign">=</span>
            <span class="formatted-number text-truncate">$0.00</span>
            <input type="hidden" class="total-line-input">
        </div>
    </div>
</script>