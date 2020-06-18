$(document).ready(function () {
	var $uploadCrop;
	var $fileInput = $('#fileInput');
	var rulesForEnabledMail = {
		business_name: {
			required: true,
		},
		company_name: {
			required: true,
		},
		company_address: {
			required: true,
		},
		mail_host: {
			required: true,
		},
		mail_username: {
			required: true,
		},
		mail_password: {
			required: true,
		},
		mail_port: {
			required: true,
		},
		mail_smtp_secure: {
			required: true,
		},
		mail_from: {
			required: true,
		},
		mail_from_name: {
			required: true,
		},
	};
	var messagesEnabledMail = {
		business_name: {
			required: "Campo requerido",
		},
		company_name: {
			required: "Campo requerido",
		},
		company_address: {
			required: "Campo requerido",
		},

		mail_host: {
			required: "Campo requerido",
		},
		mail_username: {
			required: "Campo requerido",
		},
		mail_password: {
			required: "Campo requerido",
		},
		mail_port: {
			required: "Campo requerido",
		},
		mail_smtp_secure: {
			required: "Campo requerido",
		},
		mail_from: {
			required: "Campo requerido",
		},
		mail_from_name: {
			required: "Campo requerido",
		},
	};
	var rulesForDisabledMail = {
		business_name: {
			required: true,
		},
		company_name: {
			required: true,
		},
		company_address: {
			required: true,
		},
	};
	var messagesForDisabledMail = {
		business_name: {
			required: "Campo requerido",
		},
		company_name: {
			required: "Campo requerido",
		},
		company_address: {
			required: "Campo requerido",
		},
	};
	var rules = null;
	var messages = null;
	if ($('#toggleMailSettings').prop("checked") == true) {
		rules = rulesForEnabledMail;
		messages = messagesEnabledMail;
	} else {
		rules = rulesForDisabledMail;
		messages = messagesForDisabledMail;
	}
	$("#settingsForm").validate({
		onfocusout: function (element) {
			this.element(element);
		},
		rules: rules,
		messages: messages,
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
	function fileInputChange() {
		$("#modalCrop").modal("show");
		$uploadCrop = $('#croppie').croppie({
			viewport: {
				width: 225,
				height: 150,
			},
			boundary: {
				width: 450,
				height: 300
			},
			enableResize: true,
			enableOrientation: true,
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
		$(this).val('');
	}
	$fileInput.change(fileInputChange);
	$("#modalCrop").on("hidden.bs.modal", function () {
		$uploadCrop.croppie('destroy');
	});
	$("#setLogo").click(function (e) {
		e.preventDefault();
		$uploadCrop.croppie('result', 'base64')
			.then((base64) => {
				$('#removeLogo').prop('disabled', false);
				$('#imagePreview').attr('src', base64);
				$('#logo').val(base64);
				$('#nullLogo').val(0);
				$("#modalCrop").modal("hide");
			});
	})
	$('#removeLogo').click(function (e) {
		e.preventDefault();
		let defaultSrc = $('#imagePreview').data('src');
		$('#imagePreview').attr('src', defaultSrc);
		$('#logo').val('');
		$('#nullLogo').val(1);
		$('#removeLogo').prop('disabled', true);
	});
	$('#toggleMailSettings').click(function () {
		toggleMailSettings();
		if ($('#toggleMailSettings').prop("checked") == true) {
			addRules();
		} else {
			removeRules();
		}
	});
	function toggleMailSettings() {
		if ($('#toggleMailSettings').prop("checked") == true) {
			$('#toggleMailSettings').val(1);
			$('#toggleMailSettings').attr("checked", "checked");
			showMailSettings();
		} else if ($('#toggleMailSettings').prop("checked") == false) {
			$('#toggleMailSettings').val(0);
			$('#toggleMailSettings').removeAttr("checked");
			hideMailSettings();
		}
	}
	function showMailSettings() {
		$('#mailSettings').collapse('show');
	}
	function hideMailSettings() {
		$('#mailSettings').collapse('hide');
	}
	function addRules() {
		$("#mailHost").rules("add", {
			required: true,
			messages: {
				required: "Campo requerido"
			}
		});
		$("#mailUsername").rules("add", {
			required: true,
			messages: {
				required: "Campo requerido"
			}
		});
		$("#mailPassword").rules("add", {
			required: true,
			messages: {
				required: "Campo requerido"
			}
		});
		$("#mailPort").rules("add", {
			required: true,
			messages: {
				required: "Campo requerido"
			}
		});
		$("#mailFrom").rules("add", {
			required: true,
			messages: {
				required: "Campo requerido"
			}
		});
		$("#mailFromName").rules("add", {
			required: true,
			messages: {
				required: "Campo requerido"
			}
		});
	}
	function removeRules() {
		$('#mailHost').rules("remove");
		$("#mailUsername").rules("remove");
		$("#mailPassword").rules("remove");
		$("#mailPort").rules("remove");
		$("#mailFrom").rules("remove");
		$("#mailFromName").rules("remove");
		$("#mailHost").valid();
		$("#mailUsername").valid();
		$("#mailPassword").valid();
		$("#mailPort").valid();
		$("#mailFrom").valid();
		$("#mailFromName").valid();
	}
});
