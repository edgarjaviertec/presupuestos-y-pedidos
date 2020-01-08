$(document).ready(function () {

	if (typeof $("#data_tables").data('flash-msg-type') !== 'undefined' && typeof $("#data_tables").data('flash-msg-title') !== 'undefined') {
		Swal.fire({
			position: 'top-end',
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
			},
		],
		"fnDrawCallback": function () {
			if ($('#data_tables tr').length < 11) {
				$('#data_tables_filter').hide();
				$('#data_tables_paginate').hide();
			}
		}
	});

	$('#data_tables').on('click', '.delete_btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		Swal.fire({
			title: 'Eliminar',
			text: "¿Estas seguro de querer eliminar al usuario?",
			icon: 'warning',
			showCancelButton: true,
			cancelButtonColor: '#858796',
			confirmButtonColor: '#4e73df',
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




