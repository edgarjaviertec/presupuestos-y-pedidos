var slugRegex = /^[a-z0-9\-\_]*$/i;
$(document).ready(function () {
	$.validator.addMethod("slugRegex", function (value, element) {
		return this.optional(element) || slugRegex.test(value);
	}, "Username must contain only letters, numbers, underscores or dashes.");
	$("#userForm").validate({
		onfocusout: function (element) {
			this.element(element);
		},
		rules: {
			username: {
				slugRegex: true,
				required: true,
			},
			email: {
				required: true,
				email: true,
			},
			password: {
				required: true,
				minlength: 8
			},
		},
		messages: {
			username: {
				slugRegex: "El nombre de usuario debe contener solo letras, números, guiones medios o guiones bajos",
				required: "Campo requerido",
			},
			email: {
				required: "Campo requerido",
				email: "Correo electrónico inválido"
			},
			password: {
				required: "Campo requerido",
				minlength: "La contraseña debe tener como mínimo 8 caracteres"
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

