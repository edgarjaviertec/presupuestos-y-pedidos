$(document).ready(function () {
    var positiveDecimalRegex = /^\d*[.]?\d{0,2}$/;
    $("#unitPrice").inputFilter(function (value) {
        return positiveDecimalRegex.test(value) && (value === "" || parseFloat(value) <= 1000000);
    });
    $("#productForm").validate({
        onfocusout: function (element) {
            this.element(element);
        },
        rules: {
            name: {
                required: true,
            },
            unit_price: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Campo requerido",
            },
            unit_price: {
                required: "Campo requerido",
            },
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
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        }
    });
});