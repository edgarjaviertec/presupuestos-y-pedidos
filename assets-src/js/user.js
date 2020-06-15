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

	var $uploadCrop;
	var $fileInput = $('#fileInput');

	function fileInputChange() {
		$("#modalCrop").modal("show");
		$uploadCrop = $('#croppie').croppie({
			viewport: {
				width: 250,
				height: 250,
				type: 'circle'
			},
			boundary: {
				width: 250,
				height: 250
			},
			enableOrientation: true
		});
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$uploadCrop.croppie('bind', {
					url: e.target.result
				}).then(function () {
					console.log('jQuery bind complete');
				});
			}
			reader.readAsDataURL(this.files[0]);
		}
		console.log("se resetea");
		$(this).val('');
	}

	$fileInput.change(fileInputChange);
	$("#modalCrop").on("hidden.bs.modal", function () {
		$uploadCrop.croppie('destroy');
	});

	$("#setAvatar").click(function (e) {
		e.preventDefault();
		$uploadCrop.croppie('result', 'base64')
			.then((base64) => {
				$('#removeAvatar').prop('disabled', false);
				$('#imagePreview').attr('src', base64);
				$('#avatar').val(base64);
				$('#nullAvatar').val(0);
				$("#modalCrop").modal("hide");
			});
	})

	$('#removeAvatar').click(function (e) {
		e.preventDefault();
		let defaultSrc = $('#imagePreview').data('src');
		$('#imagePreview').attr('src', defaultSrc);
		$('#avatar').val('');
		$('#nullAvatar').val(1);
		$('#removeAvatar').prop('disabled', true);
	});

});
