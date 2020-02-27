var positiveIntegerRegex = /^\d*$/i;
var alphanumericRegex = /^[a-z0-9]*$/i;
$(document).ready(function () {
    $('#rfc').blur(function () {
        this.value = this.value.toUpperCase();
    });
    $('#rfc').inputFilter(function (value) {
        return alphanumericRegex.test(value);
    });
    $('#postalCode').inputFilter(function (value) {
        return positiveIntegerRegex.test(value);
    });
    $('.select2').select2();
    $('.phone').each(function (index) {
        new Cleave($(this)[0], {
            phone: true,
            delimiter: '-',
            phoneRegionCode: 'MX'
        });
    });
    $("#customerForm").validate({
        onfocusout: function (element) {
            this.element(element);
        },
        rules: {
            name: "required",
            last_name: "required",
            email: {
                email: true
            },
            phone: "required",
        },
        messages: {
            name: "Campo requerido",
            last_name: "Campo requerido",
            email: "Correo electrónico inválido",
            phone: "Campo requerido"
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        }
    });
});