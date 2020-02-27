$(document).ready(function () {
    $("#loginForm").validate({
        onfocusout: function (element) {
            this.element(element);
        },
        rules: {
            username: "required",
            password: "required",
        },
        messages: {
            username: "Campo requerido",
            password: "Campo requerido",
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
            $(element).not('.form-validation').addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).not('.form-validation').removeClass("is-invalid");
        }
    });
});