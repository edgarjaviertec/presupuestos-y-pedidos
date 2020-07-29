<?php
$company_logo = !empty($company_settings['company_logo']) ? "/uploads/{$company_settings['company_logo']}" : "/assets/img/default-logo.png";
$business_name = !empty($company_settings['business_name']) ? $company_settings['business_name'] : 'Empresa sin razón social';
$company_name = !empty($company_settings['company_name']) ? $company_settings['company_name'] : 'Empresa sin nombre';
$company_address = !empty($company_settings['company_address']) ? $company_settings['company_address'] : 'Empresa sin dirección conocida';
$current_date_ymd = date('Y-m-d');
$due_date_dmy = date('d/m/Y', strtotime($current_date_ymd . ' +30 day'));
$due_date_ymd = date('Y-m-d', strtotime($current_date_ymd . ' +30 day'));
$current_date_dmy = date('d/m/Y');
$csrf = [
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
];
$subtotal = 0;
$discount_type = 'fixed';
$discount = 0;
$discount_val = 0;
$has_taxes = true;
$taxes = 0;
$total = 0;
$notes = '';
$customer_id = '';
$full_name = '';
$rfc = '';
$phone_or_mobile_phone = '';
$full_address = '';
if (!empty($order)) {
	$customer_id = $order->cliente_id;
	$subtotal = $order->sub_total;
	$discount_type = $order->tipo_descuento;
	$discount = $order->descuento;
	$discount_val = $order->cantidad_descontada;
	$has_taxes = $order->incluir_impuesto;
	$taxes = $order->impuesto;
	$total = $order->total;
	$notes = $order->notas;
}
if (!empty($customer)) {
	$full_name = $customer->nombre_razon_social;
	$rfc = $customer->rfc;
	$phone = $customer->telefono;
	$mobile_phone = $customer->telefono_celular;
	$phone_or_mobile_phone = (empty($phone) && !empty($mobile_phone)) ? $mobile_phone : $phone;
	$addres_fields = ['domicilio', 'ciudad', 'estado', 'pais', 'codigo_postal'];
	$full_address_array = [];
	foreach ($addres_fields as $val) {
		if (!empty($customer->$val)) {
			array_push($full_address_array, $customer->$val);
		}
	}
	$full_address = join(" ", $full_address_array);
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
		<h1 class="mb-2 h3">Nuevo pedido</h1>
		<p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
			obligatorios</p>
		<div class="document-container">
			<form id="documentForm" method="post"
				  action="<?php echo base_url('admin/orders/new_order_validation') ?>">
				<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
				<!--                <div class="meta-data">-->
				<!--					<div class="logo">-->
				<!--						<img src="--><?php //echo $company_logo ?><!--" alt="">-->
				<!--					</div>-->
				<!--					<div class="company-info">-->
				<!--						<div class="company-info-wrapper">-->
				<!--							<span class="company-name">-->
				<?php //echo $company_name ?><!--</span>-->
				<!--							<span class="business-name ">-->
				<?php //echo $business_name ?><!--</span>-->
				<!--							<span class="company-address">-->
				<?php //echo $company_address ?><!--</span>-->
				<!--						</div>-->
				<!--					</div>-->
				<!--                    <div class="number-and-date">-->
				<!--                        <div class="panel-container">-->
				<!--                            <div class="number-panel">-->
				<!--                                <div class="panel-header">Pedido</div>-->
				<!--                                <div class="panel-body">-->
				<!--                                    <input type="hidden"-->
				<!--                                           name="number"-->
				<!--                                           value="-->
				<?php //echo(isset($next_order_number) ? $next_order_number : '') ?><!--">-->
				<!--                                    <span class="number">-->
				<?php //echo(isset($next_order_number) ? $next_order_number : '') ?><!--</span>-->
				<!--                                    <input type="hidden"-->
				<!--                                           name="status"-->
				<!--                                           value="unpaid">-->
				<!--                                </div>-->
				<!--                            </div>-->
				<!--                            <div class="date-panel">-->
				<!--                                <div class="panel-header date-header">Fecha</div>-->
				<!--                                <div class="panel-body text-primary">-->
				<!--                                    <input id="date"-->
				<!--                                           type="hidden"-->
				<!--                                           name="date"-->
				<!--                                           value="--><?php //echo $current_date_ymd ?><!--">-->
				<!--                                    <input class="form-control"-->
				<!--                                           type="text"-->
				<!--                                           id="datepicker"-->
				<!--                                           value="--><?php //echo $current_date_dmy ?><!--"-->
				<!--                                           tabindex="-1"-->
				<!--                                           readonly>-->
				<!--                                </div>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                    </div>-->
				<!--                    <div class="due-date">-->
				<!--                        <label>Valido por</label>-->
				<!--                        <input id="dueDateInput" type="hidden" name="due_date" value="-->
				<?php //echo $due_date_ymd ?><!--">-->
				<!--                        <select name="validity_in_days"-->
				<!--                                class="custom-select"-->
				<!--                                id="dueDateDropdown"-->
				<!--                                autocomplete="off"-->
				<!--                                tabindex="-1">-->
				<!--                            <option value="7">7 días</option>-->
				<!--                            <option value="15">15 días</option>-->
				<!--                            <option value="30" selected>30 días</option>-->
				<!--                            <option value="60">60 días</option>-->
				<!--                        </select>-->
				<!--                        <small class="ml-1 text-muted font-weight-bold"-->
				<!--                               id="dueDateText">--><?php //echo $due_date_dmy ?><!--</small>-->
				<!--                    </div>-->
				<!--                    <div class="customer">-->
				<!--                        <div class="customer-select2">-->
				<!--                            <button id="addCustomerBtn" class="add-customer-btn">-->
				<!--                                <i class="fas fa-asterisk text-danger"></i>-->
				<!--                                <span class="text">Agregar un cliente</span>-->
				<!--                            </button>-->
				<!--                            <span id="customerSelect2" tabindex="-1"></span>-->
				<!--                        </div>-->
				<!--                        <input type="hidden"-->
				<!--                               name="customer_id"-->
				<!--                               id="customerId"-->
				<!--                               value=""-->
				<!--                               data-type="customer">-->
				<!--                        <div class="customer-info d-none" id="customerInfo">-->
				<!--							<span class="clear-btn" id="removeCustomerBtn">-->
				<!--								<i class="fas fa-times-circle"></i>-->
				<!--							</span>-->
				<!--                            <div class="field">-->
				<!--                                <span class="label">Nombre</span>-->
				<!--                                <span class="text" id="nameInput"></span>-->
				<!--                            </div>-->
				<!--                            <div class="field">-->
				<!--                                <span class="label">Dirección</span>-->
				<!--                                <span class="text" id="addressInput"></span>-->
				<!--                            </div>-->
				<!--                            <div class="field">-->
				<!--                                <span class="label">Teléfono</span>-->
				<!--                                <span class="text" id="phoneInput"></span>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                    </div>-->
				<!--                </div>-->
				<!--                <div class="items-table" id="itemsTable">-->
				<!--                    <div class="table-head">-->
				<!--                        <div class="table-row">-->
				<!--                            <div class="header">Productos</div>-->
				<!--                            <div class="header">Cantidad</div>-->
				<!--                            <div class="header">Descripción</div>-->
				<!--                            <div class="header">P. unitario</div>-->
				<!--                            <div class="header">Importe</div>-->
				<!--                        </div>-->
				<!--                    </div>-->
				<!--                    <div class="table-body"></div>-->
				<!--                </div>-->
				<!--                <div class="add-item">-->
				<!--                    <button id="addItemBtn" class="add-item-btn">-->
				<!--                        <i class="fas fa-asterisk text-danger"></i>-->
				<!--                        <span class="text">Agregar una fila</span>-->
				<!--                    </button>-->
				<!--                    <input type="hidden" name="total_items" id="totalOfItems" value="0" data-type="items">-->
				<!--                </div>-->
				<!--                <div class="summary-and-notes">-->
				<!--                    <div class="summary">-->
				<!--                        <div class="summary-item">-->
				<!--                            <div class="label"><span>Sub-total</span></div>-->
				<!--                            <div class="amount">-->
				<!--                                <input type="hidden" id="subTotal" name="sub_total">-->
				<!--                                <span class="formatted-number text-truncate">$0.00</span>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                        <div class="summary-item d-none" id="discountTypeInputGroup">-->
				<!--                            <div class="label">-->
				<!--                                <span><i class="fas fa-asterisk text-danger"></i>&nbsp;Descuento</span>-->
				<!--                                <div class="input-group discount-type-input-group mt-1">-->
				<!--                                    <div class="input-group-prepend">-->
				<!--                                        <button type="button"-->
				<!--                                                class="btn dropdown-toggle"-->
				<!--                                                data-toggle="dropdown">-->
				<!--											<span class="icon">-->
				<!--												<i class="fas fa-dollar-sign"></i>-->
				<!--											</span>-->
				<!--                                        </button>-->
				<!--                                        <div class="dropdown-menu dropdown-menu-lg-right">-->
				<!--                                            <button class="dropdown-item" type="button" data-icon="fas fa-dollar-sign"-->
				<!--                                                    data-type="fixed">Fijo ($)-->
				<!--                                            </button>-->
				<!--                                            <button class="dropdown-item" type="button" data-icon="fas fa-percentage"-->
				<!--                                                    data-type="percentage">Porcentaje (%)-->
				<!--                                            </button>-->
				<!--                                            <input type="hidden" id="discountType" name="discount_type" value="fixed">-->
				<!--                                        </div>-->
				<!--                                    </div>-->
				<!--                                    <input type="text" id="discount"-->
				<!--                                           name="discount"-->
				<!--                                           class="form-control"-->
				<!--                                           placeholder="Ej. 10"-->
				<!--                                           value="0"-->
				<!--                                           maxlength="10"-->
				<!--                                           autocomplete="off"-->
				<!--                                           tabindex="-1">-->
				<!--                                </div>-->
				<!--                            </div>-->
				<!--                            <div class="amount">-->
				<!--                                <input type="hidden" id="discountValInput" name="discount_val" value="fixed">-->
				<!--                                <span class="formatted-number text-truncate">$0.00</span>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                        <div class="summary-item">-->
				<!--                            <div class="label">-->
				<!--                                <input type="hidden" id="includeTax" name="include_tax" value="1">-->
				<!--                                <button type="button" id="taxCheckbox" class="tax-checkbox checked">-->
				<!--                                    <i class="icon fas fa-check"></i>-->
				<!--                                </button>-->
				<!--                                <span>IVA (16%)</span>-->
				<!--                            </div>-->
				<!--                            <div class="amount">-->
				<!--                                <input type="hidden" id="tax" name="tax">-->
				<!--                                <span class="formatted-number text-truncate">$0.00</span>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                        <div class="summary-item">-->
				<!--                            <div class="label"><span>Total</span></div>-->
				<!--                            <div class="amount">-->
				<!--                                <input type="hidden" id="total" name="total">-->
				<!--                                <span class="formatted-number text-truncate">$0.00</span>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                        <div class="summary-item">-->
				<!--                            <div class="label"><span>Saldo</span></div>-->
				<!--                            <div class="amount">-->
				<!--                                <input type="hidden" id="amountDue" name="amount_due" value="0">-->
				<!--                                <input type="hidden" id="totalPaid" value="0">-->
				<!--                                <span class="formatted-number text-truncate">$0.00</span>-->
				<!--                            </div>-->
				<!--                        </div>-->
				<!--                    </div>-->
				<!--                    <div class="notes">-->
				<!--						<textarea name="notes"-->
				<!--                                  class="form-control"-->
				<!--                                  placeholder="Ingrese sus notas"-->
				<!--                                  tabindex="-1"></textarea>-->
				<!--                    </div>-->
				<!--                </div>-->


				<div class="meta-data">
					<div class="logo">
						<img src="<?php echo $company_logo ?>" alt="">
					</div>
					<div class="company-info">
						<div class="company-info-wrapper">
							<span class="company-name"><?php echo $company_name ?></span>
							<span class="business-name "><?php echo $business_name ?></span>
							<span class="company-address"><?php echo $company_address ?></span>
						</div>
					</div>
					<div class="number-and-date">
						<div class="panel-container">

							<div class="number-panel">
								<div class="panel-header">Pedido</div>
								<div class="panel-body">
									<input type="hidden"
										   name="number"
										   value="<?php echo(isset($next_order_number) ? $next_order_number : '') ?>">
									<span class="number"><?php echo(isset($next_order_number) ? $next_order_number : '') ?></span>
									<input type="hidden"
										   name="status"
										   value="unpaid">
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
						<input id="dueDateInput" type="hidden" name="due_date" value="<?php echo $due_date_ymd ?>">
						<select name="validity_in_days"
								class="custom-select"
								id="dueDateDropdown"
								autocomplete="off"
								tabindex="-1">
							<option value="7">7 días</option>
							<option value="15">15 días</option>
							<option value="30" selected>30 días</option>
							<option value="60">60 días</option>
						</select>
						<small class="ml-1 text-muted font-weight-bold"
							   id="dueDateText"><?php echo $due_date_dmy ?></small>
					</div>
					<div class="customer">
						<div class="customer-select2">
							<button id="addCustomerBtn"
									class="add-customer-btn <?php echo !empty($customer) ? 'd-none' : '' ?>">
								<i class="fas fa-asterisk text-danger"></i>
								<span class="text">Agregar un cliente</span>
							</button>
							<span id="customerSelect2" tabindex="-1"></span>
						</div>
						<input type="hidden"
							   name="customer_id"
							   id="customerId"
							   value="<?php echo $customer_id ?>"
							   data-type="customer">
						<div class="customer-info <?php echo empty($customer) ? 'd-none' : '' ?>" id="customerInfo">
							<span class="clear-btn" id="removeCustomerBtn">
								<i class="fas fa-times-circle"></i>
							</span>
							<div class="field">
								<span class="label">Nombre</span>
								<span class="text" id="nameInput"><?php echo $full_name ?></span>
							</div>
							<div class="field">
								<span class="label">RFC</span>
								<span class="text" id="rfcInput"><?php echo $rfc ?></span>
							</div>
							<div class="field">
								<span class="label">Dirección</span>
								<span class="text" id="addressInput"><?php echo $full_address ?></span>
							</div>
							<div class="field">
								<span class="label">Teléfono</span>
								<span class="text" id="phoneInput"><?php echo $phone_or_mobile_phone ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="items-table" id="itemsTable">
					<div class="table-head">
						<div class="table-row">
							<div class="header"> Productos</div>
							<div class="header"> Cantidad</div>
							<div class="header"> Descripción</div>
							<div class="header"> P. unitario</div>
							<div class="header">Importe</div>
						</div>
					</div>
					<div class="table-body">
						<?php if (!empty($lines)): ?>
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
											   autocomplete="off">
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
										<label class="font-weight-bold mt-1 mt-md-2 mb-1">Descripción del
											producto</label>
										<textarea class="item-description form-control mt-0"
												  rows="1"
												  placeholder='48 lápices de colores "Arcoíris" para dibujo'
												  tabindex="-1"
												  autocomplete="off"><?php echo $line->descripcion ?></textarea>
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
						<?php endif; ?>
					</div>
				</div>
				<div class="add-item">
					<button id="addItemBtn" class="add-item-btn">
						<i class="fas fa-asterisk text-danger"></i>
						<span class="text">Agregar una fila</span>
					</button>
					<input type="hidden" name="total_items" id="totalOfItems"
						   value="<?php echo !empty($lines) ? count($lines) : 0 ?>" data-type="items">
				</div>
				<div class="summary-and-notes">
					<div class="summary">
						<div class="summary-item">
							<div class="label"><span>Sub-total</span></div>
							<div class="amount">
								<input type="hidden" id="subTotal" name="sub_total"
									   value="<?php echo floatval($subtotal) ?>">
								<span class="formatted-number text-truncate"><?php echo "$" . number_format($subtotal, 2); ?></span>
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
												<i class="fas <?php echo ($discount_type == 'percentage') ? 'fa-percentage' : 'fa-dollar-sign' ?>"></i>
											</span>
										</button>
										<div class="dropdown-menu dropdown-menu-lg-right">
											<button class="dropdown-item" type="button" data-icon="fas fa-dollar-sign"
													data-type="fixed">Fijo ($)
											</button>
											<button class="dropdown-item" type="button" data-icon="fas fa-percentage"
													data-type="percentage">Porcentaje (%)
											</button>
											<input type="hidden" id="discountType" name="discount_type"
												   value="<?php echo $discount_type ?>">
										</div>
									</div>
									<input type="text" id="discount"
										   name="discount"
										   class="form-control"
										   placeholder="Ej. 10"
										   value="<?php echo ($discount_type == 'percentage') ? intval($discount) : floatval($discount) ?>"
										   maxlength="10"
										   autocomplete="off"
										   tabindex="-1">
								</div>
							</div>
							<div class="amount">
								<input type="hidden" id="discountValInput" name="discount_val"
									   value="<?php echo floatval($discount_val) ?>">
								<span class="formatted-number text-truncate"><?php echo "-$" . number_format($discount_val, 2); ?></span>
							</div>
						</div>
						<div class="summary-item">
							<div class="label">
								<input type="hidden" id="includeTax" name="include_tax"
									   value="<?php echo intval($has_taxes) ?>">
								<button type="button" id="taxCheckbox"
										class="tax-checkbox <?php echo (intval($has_taxes) === 1) ? 'checked' : '' ?>">
									<i class="icon fas fa-check"></i>
								</button>
								<span>IVA (16%)</span>
							</div>
							<div class="amount">
								<input type="hidden" id="tax" name="tax" value="<?php echo floatval($taxes) ?>">
								<span class="formatted-number text-truncate"><?php echo "$" . number_format($taxes, 2); ?></span>
							</div>
						</div>
						<div class="summary-item">
							<div class="label"><span>Total</span></div>
							<div class="amount">
								<input type="hidden" id="total" name="total" value="<?php echo floatval($total) ?>">
								<span class="formatted-number text-truncate"><?php echo "$" . number_format($total, 2); ?></span>
							</div>
						</div>


						<div class="summary-item">
							<div class="label"><span>Saldo</span></div>
							<div class="amount">
								<input type="hidden" id="totalPaid" value="0">
								<input type="hidden" id="amountDue" name="amount_due"
									   value="<?php echo floatval($total) ?>">
								<span class="formatted-number text-truncate"><?php echo "$" . number_format($total, 2); ?></span>
							</div>
						</div>

					</div>
					<div class="notes">
						<textarea name="notes"
								  class="form-control"
								  placeholder="Ingrese sus notas"
								  tabindex="-1"><?php echo $notes ?></textarea>
					</div>
				</div>


				<div class="mb-4">
					<h4 class="mb-3 d-flex align-items-center">
						<span>Anticipos</span>
						<button type="button" id="addNewPayment" class="ml-3 btn btn-success" data-toggle="modal"
								data-target="#newPaymentModal" <?php echo empty($lines) ? 'disabled' : '' ?>>
							<i class="fas fa-plus"></i>
							<span class="ml-1 d-none d-sm-inline-block">Agregar anticipo</span>
						</button>
					</h4>
					<div class="payments-container d-none" id="paymentsContainer"></div>
					<div class="payments-container py-3"
						 id="noPayments">
						<div class="d-flex flex-column align-items-center">
							<i class="far fa-money-bill-alt no-content-icon fa-4x text-muted mb-1"></i>
							<h3 class="h5 mb-0 text-muted">Sin anticipos registrados</h3>
						</div>
					</div>
				</div>
				<div class="modal fade" id="newPaymentModal" tabindex="-1">
					<div class="modal-dialog modal-dialog-scrollable">
						<div class="modal-content">
							<div id="newPaymentForm">
								<div class="modal-header">
									<h5 class="modal-title">Nuevo pago</h5>
									<button type="button" class="close" data-dismiss="modal">
										<span>&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<label>Cantidad</label>
										<input type="tel" class="form-control amount" autocomplete="off"
											   placeholder="Ej. 350.33">
									</div>
									<div class="form-group">
										<label>Método de pago</label>
										<select class="custom-select payment-method">
											<option value="cash" selected>Efectivo</option>
											<option value="check">Cheque</option>
											<option value="bank_deposit">Deposito bancario</option>
											<option value="bank_transfer">Transferencia bancaria</option>
											<option value="credit_card">Tarjeta de crédito</option>
											<option value="debit_card">Tarjeta de débito</option>
											<option value="other">Otro</option>
										</select>
									</div>
									<div class="form-group">
										<label>Fecha</label>
										<input type="text" class="form-control date-picker"
											   value="<?php echo $current_date_dmy ?>"
											   readonly>
										<input type="hidden" class="date"
											   value="<?php echo $current_date_ymd ?>">
									</div>
									<div class="form-group">
										<label>Notas</label>
										<textarea class="form-control notes"
												  placeholder="Ingrese notas o detalles del pago"
												  autocomplete="off"></textarea>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Regresar
									</button>
									<button type="button" class="submit-btn btn btn-success">Agregar</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="action-buttons">
					<a href="<?php echo base_url('admin/pedidos') ?>" class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
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
				   autocomplete="off">
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
					  autocomplete="off"></textarea>
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
				   autocomplete="off">
		</div>
		<div class="total-line">
			<span class="equal-sign">=</span>
			<span class="formatted-number text-truncate">$0.00</span>
			<input type="hidden" class="total-line-input">
		</div>
	</div>
</script>
<script type="text/x-handlebars-template" id="paymentTpl">
	{{#each payments_made}}
	<div class="payment">
		<input name="payments[{{@index}}][amount]" type="hidden" class="payment-amount" value="{{amount}}">
		<div class="amount">{{currencyFormat amount}}</div>
		<input name="payments[{{@index}}][type]" type="hidden" class="payment-type" value="{{type}}">
		<div class="type">
			{{#switch type}}
			{{#case "cash"}}
			<span>Efectivo</span>
			{{/case}}
			{{#case "check"}}
			<span>Cheque</span>
			{{/case}}
			{{#case "bank_deposit"}}
			<span>Deposito bancario</span>
			{{/case}}
			{{#case "bank_transfer"}}
			<span>Transferencia bancaria</span>
			{{/case}}
			{{#case "credit_card"}}
			<span>Tarjeta de crédito</span>
			{{/case}}
			{{#case "debit_card"}}
			<span>Tarjeta de débito</span>
			{{/case}}
			{{#case "other"}}
			<span>Otro</span>
			{{/case}}
			{{/switch}}
		</div>
		<input name="payments[{{@index}}][date]" type="hidden" class="payment-date" value="{{date}}">
		<div class="date">{{dmyDate date}}</div>
		{{#if notes}}
		<input name="payments[{{@index}}][notes]" type="hidden" class="payment-notes" value="{{notes}}">
		<div class="notes">{{notes}}</div>
		{{/if}}
		<div class="actions">
			<button type="button" class="btn btn-secondary remove-payment-btn">
				<i class="fas fa-trash"></i>
			</button>
		</div>
	</div>
	{{/each}}
</script>
