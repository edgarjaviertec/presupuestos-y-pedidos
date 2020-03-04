<?php
$order = (isset($order)) ? $order : NULL;
$lines = (isset($lines)) ? $lines : NULL;
$customer = (isset($customer)) ? $customer : NULL;
$payments_made = (isset($payments_made)) ? $payments_made : NULL;
$total_paid = (isset($total_paid)) ? $total_paid : NULL;
$current_date_ymd = date('Y-m-d');
$current_date_dmy = date('d/m/Y');
$date_ymd = date('Y-m-d', strtotime($order->fecha_pedido));
$date_dmy = date('d/m/Y', strtotime($order->fecha_pedido));
$due_date_dmy = date('d/m/Y', strtotime($order->fecha_vencimiento));
$due_date_ymd = date('Y-m-d', strtotime($order->fecha_vencimiento));
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
$full_name = $customer->nombre_razon_social;
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
        <h1 class="mb-2 h3">Editar pedido</h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
            obligatorios</p>
        <div class="document-container mb-5">
            <form id="documentForm"
                  method="post"
                  action="<?php echo base_url('admin/orders/edit_order_validation') ?>"
                <?php echo $flash_msg_data_attr ?> >
                <input type="hidden" class="csrf" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                <input type="hidden" name="id" value="<?php echo isset($order->id) ? $order->id : '' ?>" id="orderId">
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
                                <div class="panel-header">Pedido</div>
                                <div class="panel-body">
                                    <input type="hidden"
                                           name="number"
                                           value="<?php echo $order->folio ?>">
                                    <span class="number"><?php echo $order->folio ?></span>
                                    <input type="hidden"
                                           name="status"
                                           value="<?php echo $order->status ?>">
                                </div>
                            </div>
                            <div class="date-panel">
                                <div class="panel-header date-header">Fecha</div>
                                <div class="panel-body text-primary">
                                    <input id="date"
                                           type="hidden"
                                           name="date"
                                           value="<?php echo $date_ymd ?>">
                                    <input class="form-control"
                                           type="text"
                                           id="datepicker"
                                           value="<?php echo $date_dmy ?>"
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
                            <option value="7" <?php echo ($order->validez_en_dias == 7) ? 'selected' : '' ?>>7 días
                            </option>
                            <option value="15" <?php echo ($order->validez_en_dias == 15) ? 'selected' : '' ?>>15
                                días
                            </option>
                            <option value="30" <?php echo ($order->validez_en_dias == 30) ? 'selected' : '' ?>>30
                                días
                            </option>
                            <option value="60" <?php echo ($order->validez_en_dias == 60) ? 'selected' : '' ?>>60
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
                               value="<?php echo $order->cliente_id ?>"
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
                                    <label class="font-weight-bold mt-1 mt-md-2 mb-1">Descripción del producto</label>
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
                                       value="<?php echo floatval($order->sub_total) ?>">
                                <span class="formatted-number text-truncate"><?php echo "$" . number_format($order->sub_total, 2); ?></span>
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
												<i class="fas <?php echo ($order->tipo_descuento == 'percentage') ? 'fa-percentage' : 'fa-dollar-sign' ?>"></i>
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
                                                   value="<?php echo $order->tipo_descuento ?>">
                                        </div>
                                    </div>
                                    <input type="text" id="discount"
                                           name="discount"
                                           class="form-control"
                                           placeholder="Ej. 10"
                                           value="<?php echo ($order->tipo_descuento == 'percentage') ? intval($order->descuento) : floatval($order->descuento) ?>"
                                           maxlength="10"
                                           autocomplete="off"
                                           tabindex="-1">
                                </div>
                            </div>
                            <div class="amount">
                                <input type="hidden"
                                       id="discountValInput"
                                       name="discount_val"
                                       value="<?php echo floatval($order->cantidad_descontada) ?>">
                                <span class="formatted-number text-truncate">
                                    <?php echo "-$" . number_format($order->cantidad_descontada, 2); ?>
                                </span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label">
                                <input type="hidden"
                                       id="includeTax"
                                       name="include_tax"
                                       value="<?php echo intval($order->incluir_impuesto) ?>">
                                <button type="button"
                                        id="taxCheckbox"
                                        class="tax-checkbox <?php echo (intval($order->incluir_impuesto) === 1) ? 'checked' : '' ?>">
                                    <i class="icon fas fa-check"></i>
                                </button>
                                <span>IVA (16%)</span>
                            </div>
                            <div class="amount">
                                <input type="hidden"
                                       id="tax"
                                       name="tax"
                                       value="<?php echo floatval($order->impuesto) ?>">
                                <span class="formatted-number text-truncate">
                                    <?php echo "$" . number_format($order->impuesto, 2); ?>
                                </span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label"><span>Total</span></div>
                            <div class="amount">
                                <input type="hidden"
                                       id="total"
                                       name="total"
                                       value="<?php echo floatval($order->total) ?>">
                                <span class="formatted-number text-truncate">
                                    <?php echo "$" . number_format($order->total, 2); ?>
                                </span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label"><span>Saldo</span></div>
                            <div class="amount">
                                <input type="hidden" id="totalPaid" value="<?php echo $total_paid ?>">
                                <input type="hidden" id="amountDue" name="amount_due"
                                       value="<?php echo floatval($order->saldo) ?>">
                                <span class="formatted-number text-truncate"><?php echo "$" . number_format($order->saldo, 2); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="notes">
						<textarea name="notes"
                                  class="form-control"
                                  placeholder="Ingrese sus notas"
                                  tabindex="-1"><?php echo $order->notas ?></textarea>
                    </div>
                </div>
                <div class="mb-4">
                    <h4 class="mb-3 d-flex align-items-center">
                        <span>Pagos</span>
                        <button type="button" id="addNewPayment" class="ml-3 btn btn-success" data-toggle="modal"
                                data-target="#newPaymentModal" <?php echo ($order->status === 'paid') ? 'disabled' : '' ?> >
                            <i class="fas fa-plus"></i>
                            <span class="ml-1 d-none d-sm-inline-block">Agregar pago</span>
                        </button>
                    </h4>
                    <div class="payments-container <?php echo (count($payments_made) > 0) ? '' : 'd-none' ?>"
                         id="paymentsContainer">
                        <?php foreach ($payments_made as $index => $payment): ?>
                            <div class="payment">
                                <input name="payments[<?php echo $index ?>][amount]"
                                       type="hidden"
                                       class="payment-amount"
                                       value="<?php echo $payment->monto ?>">
                                <div class="amount"><?php echo "$" . number_format($payment->monto, 2); ?></div>
                                <input name="payments[<?php echo $index ?>][type]"
                                       type="hidden" class="payment-type"
                                       value="<?php echo $payment->tipo ?>">
                                <div class="type">
                                    <?php if ($payment->tipo == "cash"): ?>
                                        <span>Efectivo</span>
                                    <?php elseif ($payment->tipo == "check"): ?>
                                        <span>Cheque</span>
                                    <?php elseif ($payment->tipo == "bank_deposit"): ?>
                                        <span>Deposito bancario</span>
                                    <?php elseif ($payment->tipo == "bank_transfer"): ?>
                                        <span>Transferencia bancaria</span>
                                    <?php elseif ($payment->tipo == "credit_card"): ?>
                                        <span>Tarjeta de crédito</span>
                                    <?php elseif ($payment->tipo == "debit_card"): ?>
                                        <span>Tarjeta de débito</span>
                                    <?php elseif ($payment->tipo == "other"): ?>
                                        <span>Otro</span>
                                    <?php endif; ?>
                                </div>
                                <input name="payments[<?php echo $index ?>][date]"
                                       type="hidden"
                                       class="payment-date"
                                       value="<?php echo $payment->fecha_pago ?>">
                                <div class="date"><?php echo date('d/m/Y', strtotime($payment->fecha_pago)) ?></div>
                                <?php if (!empty($payment->notas)): ?>
                                    <input name="payments[<?php echo $index ?>][notes]"
                                           type="hidden"
                                           class="payment-notes"
                                           value="<?php echo $payment->notas ?>">
                                    <div class="notes"><?php echo $payment->notas ?></div>
                                <?php endif; ?>
                                <div class="actions">
                                    <div class="actions">
                                        <button type="button" class="btn btn-secondary remove-payment-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="payments-container py-3 <?php echo (count($payments_made) > 0) ? 'd-none' : '' ?>"
                         id="noPayments">
                        <div class="d-flex flex-column align-items-center">
                            <i class="far fa-money-bill-alt no-content-icon fa-4x text-muted mb-1"></i>
                            <h3 class="h5 mb-0 text-muted">Sin pagos registrados</h3>
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
                    <a href="<?php echo base_url('admin/pedidos') ?>"
                       class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
                    <button type="submit"
                            class="btn btn-lg btn-success ok-btn">Guardar
                    </button>
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