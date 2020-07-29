var positiveDecimalRegex = /^\d*[.]?\d{0,2}$/;
var unitPriceRegex = /^\d*[.]?\d{0,4}$/;
var positiveIntegerRegex = /^\d*$/;
var IVA = 16;
$(document).ready(function () {
	// Calendario para seleccionar la fecha de emisión
	var picker = new Pikaday({
		field: $('#datepicker')[0],
		firstDay: 1,
		position: 'bottom center',
		format: 'D/M/YYYY',
		toString(date, format) {
			return dmyFormat(date);
		},
		parse(dateString, format) {
			return parseDmyDate(dateString);
		},
		onSelect(date) {
			$('#dueDateDropdown').trigger('change');
			$('#date').val(ymdFormat(dmyFormat(date)));
		},
		i18n: pikadaySpanishLanguage
	});
	// Lista desplegable para seleccionar el tiempo de vencimiento
	$('#dueDateDropdown').on('change', function (e) {
		e.preventDefault();
		var days = $(this).val();
		var date = picker.getDate();
		var newDate = addDays(date, days);
		var dmyDueDate = dmyFormat(newDate);
		var ymdDueDate = ymdFormat(dmyDueDate);
		$('#dueDateText').text(dmyDueDate);
		$('#dueDateInput').val(ymdDueDate);
	});
	// Lista desplegable para seleccionar un cliente
	$("#customerSelect2").select2({
		"dropdownParent": $('#customerSelect2').parent(),
		"ajax": {
			"url": "/admin/estimates/get_customers_ajax",
			"delay": 400,
			"data": function (params) {
				return {
					"start": (((params.page || 1) - 1) * 10),
					"length": 10,
					"search": params.term,
				};
			},
			"processResults": function (res, params) {
				params.page = params.page || 1;
				return {
					"results": res.data,
					"pagination": {
						"more": (params.page * 10) < res.recordsFiltered
					}
				};
			},
			"cache": true,
		},
		"templateResult": function (state) {
			if (!state.id) {
				return state.text;
			}
			var $state = $(
				'<span><i class="fas fa-user-tie select2-results__option__icon"></i>' + state.text + '</span>'
			);
			return $state;
		}
	});
	// Botón para abrir la lista desplegable para seleccionar un cliente
	$('#addCustomerBtn').click(function (e) {
		e.preventDefault();
		$("#customerSelect2").select2('open');
	});
	// Se obtienen todos los datos del cliente al seleccionar un cliente específico
	$('#customerSelect2').on('select2:select', function (e) {

		var id = e.params.data.id;
		var name = e.params.data.text;
		var rfc = e.params.data.rfc;
		var phone = e.params.data.telefono;
		var mobilePhone = e.params.data.telefono_celular;
		var fullAddressArray = [];
		var address = e.params.data.domicilio;
		var city = e.params.data.ciudad;
		var state = e.params.data.estado;
		var country = e.params.data.pais;
		var postalCode = e.params.data.codigo_postal;
		phone = (phone == '' && mobilePhone != '') ? mobilePhone : phone;
		if (address != '') {
			fullAddressArray.push(address);
		}
		if (city != '') {
			fullAddressArray.push(city);
		}
		if (state != '') {
			fullAddressArray.push(state);
		}
		if (country != '') {
			fullAddressArray.push(country);
		}
		if (postalCode != '') {
			fullAddressArray.push(postalCode);
		}
		var fullAddress = '';
		fullAddressArray.forEach(function (currentVal, index) {
			if (index != fullAddressArray.length) {
				fullAddress += currentVal + " ";
			} else {
				fullAddress += currentVal;
			}
		});
		$('#customerId').val(id);
		$('#nameInput').text(name);
		$('#addressInput').text(fullAddress);
		if ($('#rfcInput').length) {
			$('#rfcInput').text(rfc);
		}
		$('#phoneInput').text(phone);
		$('#customerInfo').removeClass('d-none');
		$('#addCustomerBtn').addClass('d-none');
		$('#customerId').valid();
	});
	// Evita que se abra el select2 cuando presionamos enter
	$('body').on('keypress', 'input', function (event) {
			if (event.keyCode == 13) {
				event.preventDefault();
			}
		}
	);
	// Botón para quitar los datos del cliente seleccionado
	$('#removeCustomerBtn').click(function (e) {
		e.preventDefault();
		$('#addCustomerBtn').removeClass('d-none');
		$('#customerInfo').addClass('d-none');
		$('#customerId').val('');
	});
	// Botón que abre un modal para crear un nuevo cliente
	$('body').on('click', '#addNewCustomerBtn', function (e) {
		e.preventDefault();
		alert('creando nuevo cliente');
	});
	// Al teclear en el campo cantidad se actualiza el importe de cada fila y el subtotal
	$('body').on('change keyup', '.item-qty', function (e) {
			e.preventDefault();
			var currentTableRow = $(this).closest('.table-row');
			updateLineTotal(currentTableRow);
			updateSummary();
		}
	);
	// Al teclear en el campo precio unitario se actualiza el importe de cada fila y el subtotal
	$('body').on('change keyup', '.item-unit-price', function (e) {
		e.preventDefault();
		var currentTableRow = $(this).closest('.table-row');
		updateLineTotal(currentTableRow);
		updateSummary();
	});
	// Botón para eliminar una fila
	$('body').on('click', '.remove-item-btn', function (e) {
		e.preventDefault();
		$(this).closest('.table-row').remove();
		updateTotalOfItems();
		addNameAttribute();
		updateSummary();
	});
	// Botón para agregar una fila
	$('#addItemBtn').click(function (e) {
		e.preventDefault();
		var $items = $('#itemsTable').find('.table-body');
		var $itemTemplate = $('#itemTemplate');
		var htmlOfItem = $itemTemplate.html();
		var $totalOfItems = $('#totalOfItems');
		$items.append(htmlOfItem);
		updateTotalOfItems();
		renderItems();
		$totalOfItems.valid();
	});

	function renderItems() {
		addAutosizeToProductDescription();
		addTypeaheadToProductName();
		addInputFilterToProductFields();
		addNameAttribute();
		addValidationRules();
	}

	// Botón que abre un modal para crear un nuevo producto
	$('body').on('click', '.tt-menu .tt-dataset .tt-footer .add-new-btn', function (e) {
		e.preventDefault();
		alert('creando nuevo producto');
	});
	// Validación de los campos del formulario usando la librería jQuery Validation
	$("#documentForm").validate({
		ignore: "",
		rules: {
			customer_id: {
				required: true,
				normalizer: function (value) {
					return $.trim(value);
				}
			},
			total_items: {
				required: true,
				greaterThanZero: true,
				normalizer: function (value) {
					return $.trim(value);
				}
			},
			discount: {
				required: true,
				lessThanOrEqualToSubtotal: true,
				normalizer: function (value) {
					value = $.trim(value);
					return value;
				}
			},
		},
		messages: {
			total_items: {
				greaterThanZero: "Debes agregar al menos un producto",
			},
			customer_id: {
				required: "Debes selecionar un cliente",
			},
			discount: {
				required: "Campo requerido",
			},
		},
		errorElement: "div",
		errorPlacement: function (error, element) {
			error.addClass("invalid-feedback");
			if (element.prop("type") === "checkbox") {
				error.insertAfter(element.next("label"));
			} else if (element.parent().hasClass('input-group')) {
				error.insertAfter(element.parent());

			} else {
				error.insertAfter(element);
			}
		},
		highlight: function (element, errorClass, validClass) {
			if ($(element).prop('type') == 'hidden' && $(element).data('type') == 'customer') {
				$(element).closest('.customer').find('.add-customer-btn').addClass("is-invalid");
			} else if ($(element).prop('type') == 'hidden' && $(element).data('type') == 'items') {
				$(element).closest('.add-item').find('.add-item-btn').addClass("is-invalid");
			} else if ($(element).parent().hasClass('input-group')) {
				$(element).closest('.input-group').addClass("is-invalid");
			} else {
				$(element).addClass("is-invalid");
			}
		},
		unhighlight: function (element, errorClass, validClass) {
			if ($(element).prop('type') == 'hidden' && $(element).data('type') == 'customer') {
				$(element).closest('.customer').find('.add-customer-btn').removeClass("is-invalid");
			} else if ($(element).prop('type') == 'hidden' && $(element).data('type') == 'items') {
				$(element).closest('.add-item').find('.add-item-btn').removeClass("is-invalid");
			} else if ($(element).parent().hasClass('input-group')) {
				$(element).closest('.input-group').removeClass("is-invalid");
			} else {
				$(element).removeClass("is-invalid");
			}
		},

		submitHandler: function (form) {
			var $amountDue = $('#amountDue');
			var amountDue = (!isEmpty($amountDue.val())) ? parseFloat($amountDue.val()) : 0;
			if (amountDue >= 0) {
				form.submit();
			} else {
				swal.fire(
					{
						customClass: {
							container: 'modal-with-icon',
						},
						type: "error",
						title: 'El total no puede ser menor que el monto pagado',
					}
				);
			}
			return false;
		}

	});
	// Método personalizado para validar si una cantidad es menor o igual que 100
	jQuery.validator.addMethod("lessThanOrEqualToSubtotal", function (value, element) {
		var $subtotal = $('#subTotal');
		var subtotal = (!isEmpty($subtotal.val())) ? parseFloat($subtotal.val()) : 0;
		var currentVal = (!isEmpty(value)) ? parseFloat(value) : 0;
		if (subtotal >= 0 && currentVal <= subtotal) {
			return true;
		} else if (subtotal == 0 && currentVal > subtotal) {
			return true;
		} else {
			return false;
		}
	}, "El descuento debe ser menor o igual al subtotal");
	// Método personalizado para validar si una cantidad es mayor que cero
	jQuery.validator.addMethod("greaterThanZero", function (value, element) {
		return this.optional(element) || (parseFloat(value) > 0);
	}, "Debe ser mayor a 0");
	checkCurrentDiscountType();
	// Aplica filtros al campo descuento de acuerdo a la opción que elija el usuario en una lista desplegable
	$('#discountTypeInputGroup').on('hide.bs.dropdown', function (e) {
		var icon = $(e.clickEvent.target).data('icon');
		var type = $(e.clickEvent.target).data('type');
		if (icon !== undefined && type !== undefined) {
			var htmlOfIcon = '<i class="' + icon + '"></i>';
			$(e.relatedTarget).find('.icon').html(htmlOfIcon);
			if (type == 'fixed') {
				addInputFilterToFixedDiscount();
				addChangeAndKeyupEventToDiscount();
				$('#discountType').val(type);
				$('#discount').val(0);
				updateSummary();
			} else {
				addInputFilterToPercentageDiscount();
				addChangeAndKeyupEventToDiscount();
				$('#discountType').val(type);
				$('#discount').val(0);
				updateSummary();
			}
		}
	});
	// Permite cambiar el alto del campo notas mientras se escribe
	autosize($('.notes textarea'));
	// Activa o desactiva el checkbox para incluir o no impuestos
	$('#taxCheckbox').click(function () {
		if ($(this).hasClass('checked')) {
			$(this).removeClass('checked');
			$('#includeTax').val(0);
			updateSummary();
		} else {
			$(this).addClass('checked');
			$(this).val(1);
			$('#includeTax').val(1);
			updateSummary();
		}
	});
	// Agregar atributos name a los campos de cantidad, nombre, descripción y precio unitario
	renderItems();
	//Si existe un mensaje flash entonces se muestra con el sweetalert
	if (typeof $("#documentForm").data('flash-msg-type') !== 'undefined' && typeof $("#documentForm").data('flash-msg-title') !== 'undefined') {
		Swal.fire({
			customClass: {
				container: 'flash-message flash-success',
			},
			position: 'top-end',
			toast: true,
			type: $("#documentForm").data('flash-msg-type'),
			title: $("#documentForm").data('flash-msg-title'),
			showConfirmButton: false,
			timer: 2000
		});
	}
});

function getAmountDue(id) {
	return $.ajax({
		url: '/admin/orders/get_amount_due_ajax',
		type: 'get',
		data: {
			id: id
		},
	}).promise();
}

function getTotalPaid(order_id, customer_id) {
	return $.ajax({
		url: '/admin/orders/get_total_paid_ajax',
		type: 'get',
		data: {
			order_id: order_id,
			customer_id: customer_id,
		},
	}).promise();
}

//Revisa el tipo de descuento actual y aplica los filtros correspondientes
function checkCurrentDiscountType() {
	var $discountType = $('#discountType');
	var discountType = (!isEmpty($discountType.val())) ? $discountType.val() : 'fixed';
	if (discountType == 'fixed') {
		addInputFilterToFixedDiscount();
		addChangeAndKeyupEventToDiscount();
	} else {
		addInputFilterToPercentageDiscount();
		addChangeAndKeyupEventToDiscount();
	}
}

// Al teclear en el campo cantidad se actualiza el importe de cada fila y el subtotal
function addChangeAndKeyupEventToDiscount() {
	$('#discount').on('change keyup', function (e) {
		e.preventDefault();
		updateSummary();
		$(this).valid();
	});
}

// Agrega filtros para permitir solo enteros positivos en los descuentos por porcentaje
function addInputFilterToPercentageDiscount() {
	$('#discount').off();
	$("#discount").inputFilter(function (value) {
		return positiveIntegerRegex.test(value) && (value === "" || parseInt(value) <= 100);
	});
}

// Aagrega filtros para permitir solo decimales positivos en los descuentos fijos
function addInputFilterToFixedDiscount() {
	$('#discount').off();
	$("#discount").inputFilter(function (value) {
		return positiveDecimalRegex.test(value) && (value === "" || parseFloat(value) <= 1000000);
	});
}

// Se agregan filtros para permitir solo decimales positivos a los campos de cantidad y precio unitario
function addInputFilterToProductFields() {
	var $itemQty = $('input.item-qty');
	var $itemUnitPrice = $('input.item-unit-price');
	$itemUnitPrice.off();
	$itemUnitPrice.inputFilter(function (value) {
		return unitPriceRegex.test(value) && (value === "" || parseFloat(value) <= 1000000);
	});
	$itemQty.off();
	$itemQty.inputFilter(function (value) {
		return positiveDecimalRegex.test(value) && (value === "" || parseFloat(value) <= 1000000);
	});
}

// Agregar atributos name a los campos de cantidad, nombre, descripción y precio unitario
function addNameAttribute() {
	$('#itemsTable').find('input.item-qty').each(function (index) {
		$(this).removeAttr('name');
		$(this).attr('name', 'items[' + index + '][qty]');
	});
	$('#itemsTable').find('input.item-name.tt-input').each(function (index) {
		$(this).removeAttr('name');
		$(this).attr('name', 'items[' + index + '][name]');
	});
	$('#itemsTable').find('input.product-id').each(function (index) {
		$(this).removeAttr('name');
		$(this).attr('name', 'items[' + index + '][product_id]');
	});
	$('#itemsTable').find('input.item-unit-price').each(function (index) {
		$(this).removeAttr('name');
		$(this).attr('name', 'items[' + index + '][unit_price]');
	});
	$('#itemsTable').find('textarea.item-description').each(function (index) {
		$(this).removeAttr('name');
		$(this).attr('name', 'items[' + index + '][description]');
	});
	$('#itemsTable').find('input.total-line-input').each(function (index) {
		$(this).removeAttr('name');
		$(this).attr('name', 'items[' + index + '][total]');
	});
}

// Agregar reglas de validación para los campos de cantidad, nombre y precio unitario
function addValidationRules() {
	var product = $('input[name^="items"]');
	product.filter('input[name$="[qty]"]').each(function () {
		$(this).rules("add",
			{
				required: true,
				greaterThanZero: true,
				normalizer: function (value) {
					value = $.trim(value);
					return value;
				},
				messages: {
					required: "Campo requerido",
				}
			}
		);
	});
	product.filter('input[name$="[unit_price]"]').each(function () {
		$(this).rules("add", {
			required: true,
			greaterThanZero: true,
			normalizer: function (value) {
				value = $.trim(value);
				return value;
			},
			messages: {
				required: "Campo requerido",
			}
		});
	});
	product.filter('input[name$="[name]"]').not('.tt-hint').each(function () {
		$(this).rules("add", {
			required: true,
			normalizer: function (value) {
				return $.trim(value);
			},
			messages: {
				required: "Campo requerido",
			}
		});
	});
}

// Actualiza el importe de cada una de las filas
function updateLineTotal($currentTableRow) {
	var $itemQty = $currentTableRow.find('.item-qty');
	var $itemUnitPrice = $currentTableRow.find('.item-unit-price');
	var $formattedNumber = $currentTableRow.find('.total-line .formatted-number');
	var $totalLineInput = $currentTableRow.find('.total-line input.total-line-input');
	var total = 0;
	var quantity = $itemQty.val();
	var unitPrice = $itemUnitPrice.val();
	if (!isEmpty(quantity) && !isEmpty(unitPrice)) {
		total = parseFloat(quantity) * parseFloat(unitPrice);
	}
	$totalLineInput.val(total);
	$formattedNumber.text(formatNumber(total));
}

// Actualiza el numero total de filas
function updateTotalOfItems() {
	var itemTotal = $('#itemsTable').find('.table-body .table-row').length;
	$('#totalOfItems').val(itemTotal);
}

// Actualiza el importe de cada fila
function updateSubtotal() {
	var $totalLine = $(".total-line-input");
	var $formattedNumber = $('#subTotal').closest('.amount').find('.formatted-number');
	var $subTotal = $('#subTotal');
	var subtotal = 0;
	$totalLine.each(function () {
		if (!isEmpty($(this).val())) {
			subtotal = parseFloat(subtotal) + parseFloat($(this).val());
		}
	});
	subtotal = subtotal.toFixed(2);
	subtotal = parseFloat(subtotal);
	$subTotal.val(subtotal);
	$formattedNumber.text(formatNumber(subtotal));
}

function updateDiscount() {
	var $discountValInput = $('#discountValInput');
	var $formattedNumber = $('#discountValInput').closest('.amount').find('.formatted-number');
	var $discountType = $('#discountType');
	var $subtotal = $('#subTotal');
	var $discount = $('#discount');
	var discountType = (!isEmpty($discountType.val())) ? $discountType.val().trim() : 'fixed';
	var discount = (!isEmpty($discount.val())) ? parseFloat($discount.val()) : 0;
	var subtotal = (!isEmpty($subtotal.val())) ? parseFloat($subtotal.val()) : 0;
	var discountVal = 0;
	var formattedNumber = '';
	if (subtotal > 0) {
		$('#discountTypeInputGroup').removeClass('d-none');
	} else {
		$('#discountTypeInputGroup').addClass('d-none');
		$('#discount').val(0);
		$('#discount').valid();
	}
	if (subtotal > 0 && discountType === 'fixed') {
		discountVal = discount;
	} else if (subtotal > 0 && discountType === 'percentage') {
		discountVal = (subtotal * discount) / 100;
		discountVal = discountVal.toFixed(2);
		discountVal = parseFloat(discountVal);
	} else {
		discountVal = 0;
	}
	$discountValInput.val(discountVal);
	formattedNumber = (discountVal > 0) ? formatNumber(-discountVal) : formatNumber(discountVal);
	$formattedNumber.text(formattedNumber);
	$discount.valid()
}

function updateTax() {
	var $subtotal = $('#subTotal');
	var $tax = $('#tax');
	var $discountValInput = $('#discountValInput');
	var $formattedNumber = $tax.closest('.amount').find('.formatted-number');
	var subTotal = (!isEmpty($subtotal.val())) ? parseFloat($subtotal.val()) : 0;
	var discount = (!isEmpty($discountValInput.val())) ? parseFloat($discountValInput.val()) : 0;
	var tax = (discount == 0) ? (subTotal * IVA) / 100 : ((subTotal - discount) * IVA) / 100;
	tax = parseInt($('#includeTax').val()) === 1 ? tax : 0;
	tax = tax.toFixed(2);
	tax = parseFloat(tax);
	$tax.val(tax);
	$formattedNumber.text(formatNumber(tax));
}

function updateTotal() {
	var $subtotal = $('#subTotal');
	var $discountValInput = $('#discountValInput');
	var $tax = $('#tax');
	var $total = $('#total');
	var $formattedNumber = $total.closest('.amount').find('.formatted-number');
	var subTotal = (!isEmpty($subtotal.val())) ? parseFloat($subtotal.val()) : 0;
	var discount = (!isEmpty($discountValInput.val())) ? parseFloat($discountValInput.val()) : 0;
	var tax = (!isEmpty($tax.val())) ? parseFloat($tax.val()) : 0;
	var total = (subTotal - discount) + tax;
	total = total.toFixed(2);
	total = parseFloat(total);
	$total.val(total);
	$formattedNumber.text(formatNumber(total));
}

function updateAmountDue() {
	if ($('#amountDue').length && $('#totalPaid').length) {
		var $total = $('#total');
		var $totalPaid = $('#totalPaid');
		var $amountDue = $('#amountDue');
		var $formattedNumber = $amountDue.closest('.amount').find('.formatted-number');
		var total = (!isEmpty($total.val())) ? parseFloat($total.val()) : 0;
		var totalPaid = (!isEmpty($totalPaid.val())) ? parseFloat($totalPaid.val()) : 0;
		var amountDue = (total - totalPaid);
		amountDue = amountDue.toFixed(2);
		amountDue = parseFloat(amountDue);
		if (amountDue <= 0) {
			$('#addNewPayment').attr('disabled', true);
		} else {
			$('#addNewPayment').attr('disabled', false);
		}
		$amountDue.val(amountDue);
		$formattedNumber.text(formatNumber(amountDue));
	}
}

// Actualiza el subtotal, descuento, IVA y total
function updateSummary() {
	updateSubtotal();
	updateDiscount();
	updateTax();
	updateTotal();
	updateAmountDue();
}

// FUNCIONES PARA INICIALIZAR LIBRERÍAS DE TERCEROS
// Agrega el autocompletado al campo del nombre de producto usando la librería typeahead.js
function addTypeaheadToProductName() {
	$("#itemsTable input.item-name").typeahead("destroy");
	$('#itemsTable input.item-name').typeahead({
		highlight: true
	}, {
		source: new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: '/admin/estimates/get_products_ajax?search=',
			remote: {
				url: '/admin/estimates/get_products_ajax?search=%QUERY',
				wildcard: '%QUERY'
			}
		}),
		limit: 5,
		display: 'nombre',
		templates: {
			suggestion: function (data) {
				return '<span><i class="tt-item-icon fas fa-shopping-basket"></i>' + data.nombre + '</span>';
			}
		}
	});
	$('#itemsTable input.item-name').on('typeahead:select', function (e, suggestion) {
		var productId = suggestion.id;
		var description = suggestion.descripcion;
		var unitPrice = suggestion.precio_unitario;
		var $productId = $(e.currentTarget).closest('.table-row').find('input.product-id');
		var $description = $(e.currentTarget).closest('.table-row').find('textarea.item-description');
		var $unitPrice = $(e.currentTarget).closest('.table-row').find('input.item-unit-price');
		$productId.val(productId).trigger('change');
		$description.val(description).trigger('change');
		$unitPrice.val(unitPrice).trigger('change');
		addAutosizeToProductDescription();
	});
	$('#itemsTable input.item-name').on('typeahead:render typeahead:open', function (ev, suggestion) {
		$(".tt-suggestion").first().addClass('tt-cursor');
		if ($(".tt-suggestion").length) {
			$(".empty-message").hide();
		}
	});
}

// Agrega el auto redimensionamiento en el campo descripción de producto usando la librería Autosize
function addAutosizeToProductDescription() {
	autosize($('.item-description'));
}

// FUNCIONES PARA FECHA
// Suma una cantidad de días a una fecha especifica (tipo date)
function addDays(date, days) {
	date.setHours(0, 0, 0, 0);
	date.setDate(date.getDate() + parseInt(days));
	return date
}

// Convierte una fecha (tipo date) a un string con el formato "01/12/2020"
function dmyFormat(date) {
	var day = ('' + date.getDate()).padStart(2, "0");
	var month = ('' + (date.getMonth() + 1)).padStart(2, "0");
	var year = date.getFullYear();
	return `${day}/${month}/${year}`;
}

// Convierte una fecha (tipo string) con el formato "01/12/2020" al formato "2020-12-01"
function ymdFormat(dateString) {
	var parts = dateString.split('/');
	var day = parts[0];
	var month = parts[1];
	var year = parts[2];
	return `${year}-${month}-${day}`;
}

// Obtiene una fecha (tipo date) a partir de un string con formato "01/12/2020"
function parseDmyDate(dateString) {
	var parts = dateString.split('/');
	var day = parseInt(parts[0], 10);
	var month = parseInt(parts[1], 10) - 1;
	var year = parseInt(parts[2], 10);
	return new Date(year, month, day);
}

// FUNCIONES PARA NÚMEROS Y CADENAS
// Comprueba si una variable es vacío
function isEmpty(mixedVar) {
	var undef;
	var key;
	var i;
	var len;
	var emptyValues = [undef, null, false, 0, '', '0'];
	for (i = 0, len = emptyValues.length; i < len; i++) {
		if (mixedVar === emptyValues[i]) {
			return true
		}
	}
	if (typeof mixedVar === 'object') {
		for (key in mixedVar) {
			if (mixedVar.hasOwnProperty(key)) {
				return false
			}
		}
		return true
	}
	return false
}

function formatNumber(number) {
	var locale = 'en-US';
	var options = {
		style: 'currency',
		currency: 'USD'
	};
	return number.toLocaleString(locale, options);
}

function getPaymentsMade() {
	var totalPaid = 0;
	var $orderId = $('#orderId');
	var $customerId = $('#customerId');
	var orderId = (!isEmpty($orderId.val())) ? $orderId.val() : 0;
	var customerId = (!isEmpty($customerId.val())) ? $customerId.val() : 0;
	var paymentsMade = [];
	$("#paymentsContainer .payment").each(function (index) {
		var $date = $(this).find('.payment-date');
		var $type = $(this).find('.payment-type');
		var $notes = $(this).find('.payment-notes');
		var $amount = $(this).find('.payment-amount');
		var date = (!isEmpty($date.val())) ? $date.val() : '';
		var type1 = (!isEmpty($type.val())) ? $type.val() : '';
		var notes = (!isEmpty($notes.val())) ? $notes.val() : '';
		var amount = (!isEmpty($amount.val())) ? $amount.val() : 0;
		totalPaid = parseFloat(totalPaid) + parseFloat(amount);
		paymentsMade.push({
			"date": date,
			"type": type1,
			"notes": notes,
			"amount": amount,
			"order_id": orderId,
			"customer_id": customerId
		});
	});
	return paymentsMade
}
