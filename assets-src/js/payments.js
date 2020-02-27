var positiveDecimalRegex = /^\d*[.]?\d{0,2}$/;
var positiveIntegerRegex = /^\d*$/;
var IVA = 16;
Handlebars.registerHelper("dmyDate", function (ymdDate) {
    return ymdDate.split("-").reverse().join("/");
});
Handlebars.registerHelper("switch", function (value, options) {
    this._switch_value_ = value;
    var html = options.fn(this); // Process the body of the switch block
    delete this._switch_value_;
    return html;
});
Handlebars.registerHelper("case", function (value, options) {
    if (value == this._switch_value_) {
        return options.fn(this);
    }
});
Handlebars.registerHelper("currencyFormat", function (amount) {
    var number = amount;
    number = parseFloat(number);
    var locale = 'en-US';
    var options = {
        style: 'currency',
        currency: 'USD'
    };
    return number.toLocaleString(locale, options);
});
$(document).ready(function () {
    new Pikaday({
        field: $('#newPaymentModal input.date-picker')[0],
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
            $('#newPaymentModal input[name="date"]').val(ymdFormat(dmyFormat(date)));
        },
        i18n: pikadaySpanishLanguage
    });
    $('#newPaymentModal input.amount').off();
    $('#newPaymentModal input.amount').inputFilter(function (value) {
        return positiveDecimalRegex.test(value) && (value === "" || parseFloat(value) <= 1000000);
    });
    $('#newPaymentModal').on('show.bs.modal', function (e) {
        $('#newPaymentModal input').each(function (index) {
            $(this).removeClass('is-invalid');
        });
        $('#newPaymentModal .invalid-feedback').remove();
        var $amountDue = $('#amountDue');
        var amountDue = (!isEmpty($amountDue.val())) ? parseFloat($amountDue.val()) : '';
        $('#newPaymentModal input.amount').val(amountDue);
        $('#newPaymentModal select.payment_method').val('cash');
        $('#newPaymentModal input.date-picker').val(dmyFormat(new Date()));
        $('#newPaymentModal textarea.notes').val('');
    });
    $('body').on('click', '#newPaymentModal .submit-btn', function (e) {
        $('#newPaymentModal input').removeClass('is-invalid');
        $('#newPaymentModal .invalid-feedback').remove();
        var totalPaid = 0;
        var $orderId = $('#orderId');
        var $customerId = $('#customerId');
        var orderId = (!isEmpty($orderId.val())) ? $orderId.val() : 0;
        var customerId = (!isEmpty($customerId.val())) ? $customerId.val() : 0;
        var $newAmount = $('#newPaymentModal input.amount');
        var $newType = $('#newPaymentModal select.payment-method');
        var $newDate = $('#newPaymentModal input.date');
        var $newNotes = $('#newPaymentModal textarea.notes');
        if ($newAmount.val() == '') {
            $newAmount.addClass('is-invalid')
            $('<div class="invalid-feedback">Campo requerido</div>').insertAfter($newAmount);
            return false;
        } else if ($newAmount.val() <= 0) {
            $newAmount.addClass('is-invalid')
            $('<div class="invalid-feedback">El monto debe ser mayor a 0</div>').insertAfter($newAmount);
            return false;
        }
        var paymentsMade = getPaymentsMade();
        paymentsMade.forEach(function (payment) {
            totalPaid = parseFloat(totalPaid) + parseFloat(payment.amount);
        });
        var newDate = (!isEmpty($newDate.val())) ? $newDate.val() : 0;
        var newType = (!isEmpty($newType.val())) ? $newType.val() : 0;
        var newNotes = (!isEmpty($newNotes.val())) ? $newNotes.val() : 0;
        var newAmount = (!isEmpty($newAmount.val())) ? $newAmount.val() : 0;
        paymentsMade.push({
            "date": newDate,
            "type": newType,
            "notes": newNotes,
            "amount": newAmount,
            "order_id": orderId,
            "customer_id": customerId
        });
        totalPaid = parseFloat(totalPaid) + parseFloat(newAmount);
        var data = {
            total_paid: totalPaid
        }
        data.payments_made = paymentsMade;
        $('#totalPaid').val(totalPaid);
        updateSummary();
        if (data.payments_made.length > 0) {
            var source = $('#paymentTpl').html();
            var template = Handlebars.compile(source);
            var html = template(data);
            var output = $('#paymentsContainer');
            output.html(html);
            $('#noPayments').addClass('d-none');
            $('#paymentsContainer').removeClass('d-none');
        } else {
            var output = $('#paymentsContainer');
            output.html('');
            $('#noPayments').removeClass('d-none');
            $('#paymentsContainer').addClass('d-none');
        }
        $('#newPaymentModal').modal('hide');
    });
    $('body').on('click', '#paymentsContainer .remove-payment-btn', function (e) {
        e.preventDefault();
        $(this).closest('.payment').remove();
        var totalPaid = 0;
        var paymentsMade = getPaymentsMade();
        paymentsMade.forEach(function (payment) {
            totalPaid = parseFloat(totalPaid) + parseFloat(payment.amount);
        });
        var data = {
            total_paid: totalPaid
        }
        data.payments_made = paymentsMade;
        $('#totalPaid').val(totalPaid);
        updateSummary();
        if (data.payments_made.length > 0) {
            var source = $('#paymentTpl').html();
            var template = Handlebars.compile(source);
            var html = template(data);
            var output = $('#paymentsContainer');
            output.html(html);
            $('#noPayments').addClass('d-none');
            $('#paymentsContainer').removeClass('d-none');
        } else {
            var output = $('#paymentsContainer');
            output.html('');
            $('#noPayments').removeClass('d-none');
            $('#paymentsContainer').addClass('d-none');
        }
    });
});