<?php
$current_date_ymd = date('Y-m-d');
$due_date_dmy = date('d/m/Y', strtotime($current_date_ymd . ' +30 day'));
$due_date_ymd = date('Y-m-d', strtotime($current_date_ymd . ' +30 day'));
$current_date_dmy = date('d/m/Y');
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
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

        <h1 class="mb-2 h3">Nuevo presupuesto</h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
            obligatorios</p>


        <div class="document-container">
            <form id="documentForm" method="post"
                  action="<?php echo base_url('admin/estimates/new_estimate_validation') ?>">
                <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
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
                                           value="<?php echo(isset($next_estimate_number) ? $next_estimate_number : '') ?>">
                                    <span class="number"><?php echo(isset($next_estimate_number) ? $next_estimate_number : '') ?></span>
                                    <input type="hidden"
                                           name="status"
                                           value="draft">
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
                            <button id="addCustomerBtn" class="add-customer-btn">
                                <i class="fas fa-asterisk text-danger"></i>
                                <span class="text">Agregar un cliente</span>
                            </button>
                            <span id="customerSelect2" tabindex="-1"></span>
                        </div>
                        <input type="hidden"
                               name="customer_id"
                               id="customerId"
                               value=""
                               data-type="customer">
                        <div class="customer-info d-none" id="customerInfo">
							<span class="clear-btn" id="removeCustomerBtn">
								<i class="fas fa-times-circle"></i>
							</span>
                            <div class="field">
                                <span class="label">Nombre</span>
                                <span class="text" id="nameInput"></span>
                            </div>
                            <div class="field">
                                <span class="label">Dirección</span>
                                <span class="text" id="addressInput"></span>
                            </div>
                            <div class="field">
                                <span class="label">Teléfono</span>
                                <span class="text" id="phoneInput"></span>
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
                    <div class="table-body"></div>
                </div>

                <div class="add-item">
                    <button id="addItemBtn" class="add-item-btn">
                        <i class="fas fa-asterisk text-danger"></i>
                        <span class="text">Agregar una fila</span>
                    </button>
                    <input type="hidden" name="total_items" id="totalOfItems" value="0" data-type="items">
                </div>
                <div class="summary-and-notes">
                    <div class="summary">
                        <div class="summary-item">
                            <div class="label"><span>Sub-total</span></div>
                            <div class="amount">
                                <input type="hidden" id="subTotal" name="sub_total">
                                <span class="formatted-number text-truncate">$0.00</span>
                            </div>
                        </div>
                        <div class="summary-item d-none" id="discountTypeInputGroup">
                            <div class="label">
                                <span><i class="fas fa-asterisk text-danger"></i>&nbsp;Descuento</span>
                                <div class="input-group discount-type-input-group mt-1">
                                    <div class="input-group-prepend">
                                        <button type="button"
                                                class="btn dropdown-toggle"
                                                data-toggle="dropdown">
											<span class="icon">
												<i class="fas fa-dollar-sign"></i>
											</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-lg-right">
                                            <button class="dropdown-item" type="button" data-icon="fas fa-dollar-sign"
                                                    data-type="fixed">Fijo ($)
                                            </button>
                                            <button class="dropdown-item" type="button" data-icon="fas fa-percentage"
                                                    data-type="percentage">Porcentaje (%)
                                            </button>
                                            <input type="hidden" id="discountType" name="discount_type" value="fixed">
                                        </div>
                                    </div>
                                    <input type="text" id="discount"
                                           name="discount"
                                           class="form-control"
                                           placeholder="Ej. 10"
                                           value="0"
                                           maxlength="10"

                                           autocomplete="off"
                                           tabindex="-1">
                                </div>
                            </div>
                            <div class="amount">
                                <input type="hidden" id="discountValInput" name="discount_val" value="fixed">
                                <span class="formatted-number text-truncate">$0.00</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label">

                                <input type="hidden" id="includeTax" name="include_tax" value="1">
                                <button type="button" id="taxCheckbox" class="tax-checkbox checked">
                                    <i class="icon fas fa-check"></i>
                                </button>
                                <span>IVA (16%)</span>


                            </div>
                            <div class="amount">
                                <input type="hidden" id="tax" name="tax">
                                <span class="formatted-number text-truncate">$0.00</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="label"><span>Total</span></div>
                            <div class="amount">
                                <input type="hidden" id="total" name="total">
                                <span class="formatted-number text-truncate">$0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="notes">
						<textarea name="notes"
                                  class="form-control"
                                  placeholder="Ingrese notas o detalles de transferencia bancaria"
                                  tabindex="-1"
                        ></textarea>
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
            <input type="text" class="item-name form-control"  placeholder="Ej. Lápices de Colores">
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