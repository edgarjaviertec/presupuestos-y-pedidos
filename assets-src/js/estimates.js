$(document).ready(function () {
	$.fn.DataTable.ext.pager.numbers_length = 6;
	if (typeof $("#dataTables").data('flash-msg-type') !== 'undefined' && typeof $("#dataTables").data('flash-msg-title') !== 'undefined') {
		Swal.fire({
			customClass: {
				container: 'flash-message flash-success',
			},
			position: 'top-end',
			toast: true,
			type: $("#dataTables").data('flash-msg-type'),
			title: $("#dataTables").data('flash-msg-title'),
			showConfirmButton: false,
			timer: 1500
		});
	}
	$('#dataTables').dataTable({
		"serverSide": true,
		"responsive": true,
		"ajax": "/admin/estimates/get_estimates_ajax",
		"columnDefs": [
			{
				"targets": 'no-sort',
				"orderable": false,
			}
		],
		"order": [[0, "desc"]],
		"language": {
			...spanishLang,
			"paginate": {
				"previous": '<i class="fa fa-step-backward"></i>',
				"next": '<i class="fa fa-step-forward"></i>'
			}
		}
	});
	$('#dataTables').on('click', '.delete_btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		Swal.fire({
			customClass: {
				container: 'confirmation-modal',
			},
			title: 'Cancelar',
			html: '<strong>¿Estas seguro de querer cancelar este presupuesto?</strong><br> <small>Recuerde que no podrá revertir la cancelación más adelante.</small>',
			showCancelButton: true,
			confirmButtonText: 'Sí',
			cancelButtonText: 'No',
			reverseButtons: true,
			focusCancel: true,
		}).then((result) => {
			if (result.value) {
				form.submit();
			}
		})
	});
	$('#dataTables').on('click', '.change-status-btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		form.submit();
	});
	$('#dataTables').on('click', '.duplicate-btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		form.submit();
	});
	$('#dataTables').on('click', '.convert-to-btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		form.submit();
	});
	$('#generateReportModal').on('show.bs.modal', function (e) {
		var year = new Date().getFullYear();
		var month = new Date().getMonth() + 1;
		year = parseInt(year);
		month = parseInt(month);
		$('#customRadio1').removeAttr('checked');
		$('#customRadio2').removeAttr('checked');
		$('#customRadio1').prop('checked', true);
		$('#generateReportModal select[name="month"]').val(month);
		$('#generateReportModal select[name="year"]').val(year);
	})
	$('#generateReportModal .submit-btn').on('click', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		form.submit();
		$('#generateReportModal').modal('hide');
	});
	// Corrige la tabla #dataTables cuando se redimensiona la ventana
	var $dataTables = $('#dataTables');
	var mediaQuery = window.matchMedia('(min-width: 576px)');
	mediaQuery.addListener(widthChange);

	function widthChange(mediaQuery) {
		if (mediaQuery.matches) {
			$dataTables.addClass('nowrap');
		} else {
			$dataTables.removeClass('nowrap');
		}
	}

	widthChange(mediaQuery);
});
