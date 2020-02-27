$(document).ready(function () {
    $("#changePasswordForm").validate({
        onfocusout: function (element) {
            this.element(element);
        },
        rules: {
            password: {
                required: true,
                minlength: 8
            },
            confirm_password: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
        },
        messages: {
            password: {
                required: "Campo requerido",
                minlength: "La contraseña debe tener como mínimo 8 caracteres",
            },
            confirm_password: {
                required: "Campo requerido",
                minlength: "La contraseña debe tener como mínimo 8 caracteres",
                equalTo: "Ingrese la misma contraseña, para la verificación"
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
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        }
    });
});