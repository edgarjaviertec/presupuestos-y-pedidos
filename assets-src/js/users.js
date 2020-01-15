$(document).ready(function () {

	if (typeof $("#data_tables").data('flash-msg-type') !== 'undefined' && typeof $("#data_tables").data('flash-msg-title') !== 'undefined') {
		Swal.fire({
			customClass: {
				container: 'flash-message flash-success',
			},
			position: 'top-end',
			toast: true,
			type: $("#data_tables").data('flash-msg-type'),
			title: $("#data_tables").data('flash-msg-title'),
			showConfirmButton: false,
			timer: 1500
		});
	}

	$('#data_tables').dataTable({
		"serverSide": true,
		"responsive": true,
		"ajax": "/admin/users/get_users_ajax",
		"columnDefs": [
			{
				"targets": 'no-sort',
				"orderable": false,
			}
		],
		"language": spanishLang
	});

	$('#data_tables').on('click', '.delete_btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		Swal.fire({
			customClass: {
				container: 'confirmation-modal',
			},
			title: 'Eliminar',
			text: "¿Estas seguro de querer eliminar este usuario?",
			showCancelButton: true,
			confirmButtonText: 'Aceptar',
			cancelButtonText: 'Cancelar',
			reverseButtons: true,
			focusCancel: true
		}).then((result) => {
			if (result.value) {
				form.submit();
			}
		})
	});

});




