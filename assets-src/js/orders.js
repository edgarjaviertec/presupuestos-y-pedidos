$(document).ready(function () {
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
	var $table = $('#dataTables').DataTable({
		"serverSide": true,
		"responsive": true,
		"ajax": "/admin/orders/get_orders_ajax",
		"columnDefs": [
			{
				"targets": 'no-sort',
				"orderable": false,
			}
		],
		"order": [[0, "desc"]],
		"language": spanishLang
	});
	$('#dataTables').on('click', '.delete_btn', function (e) {
		e.preventDefault();
		var form = $(this).closest("form");
		Swal.fire({
			customClass: {
				container: 'confirmation-modal',
			},
			title: 'Cancelar',
			html: '<strong>¿Estas seguro de querer cancelar este pedido?</strong><br> <small>Recuerde que no podrá revertir la cancelación más adelante.</small>',
			showCancelButton: true,
			confirmButtonText: 'Sí',
			cancelButtonText: 'No',
			reverseButtons: true,
			focusCancel: true
		}).then((result) => {
			if (result.value) {
				form.submit();
			}
		})
	});


	function encodeRFC5987ValueChars(str) {
		return encodeURIComponent(str).
			// Note that although RFC3986 reserves "!", RFC5987 does not,
			// so we do not need to escape it
			replace(/['()]/g, escape). // i.e., %27 %28 %29
			replace(/\*/g, '%2A').
			// The following are not required for percent-encoding per RFC5987,
			// so we can allow for a little better readability over the wire: |`^
			replace(/%(?:7C|60|5E)/g, unescape);
	}


	$('#dataTables').on('click', '.send-by-email-btn', function (e) {
		e.preventDefault();
		let id = $(this).data('id');
		let companyName = $(this).data('company-name');
		let documentId = $(this).data('document-id');
		let email = $(this).data('email') ? $(this).data('email').trim() : '';
		Swal.fire({
			title: `Enviar pedido`,
			html: `
<div class="text-left">
<div class="form-group">
<label class="col-form-label font-weight-bold">Para</label>
  <input type="text" readonly class="form-control-plaintext" value="${email}">
</div>
<div class="form-group">
<label class="font-weight-bold">Asunto</label>
<input type="text"
id="subject"
class="form-control"
placeholder="Asunto" value="Pedido  ${documentId} -  ${companyName}" />
</div>
<div class="form-group">
<label class="font-weight-bold">Mensaje</label>
<textarea type="password"
id="message"
class="form-control"
placeholder="Mensaje"
rows="5">
Hola,

Aquí tiene el pedido ${documentId} en formato PDF.

Atentamente:
${companyName}</textarea>	
</div>
</div>
`,
			showLoaderOnConfirm: true,
			showCancelButton: true,
			confirmButtonText: 'Enviar',
			cancelButtonText: 'Cancelar',
			reverseButtons: true,
			preConfirm: () => {
				let subject = Swal.getPopup().querySelector('#subject').value
				let message = Swal.getPopup().querySelector('#message').value
				if (subject === '' || message === '') {
					Swal.showValidationMessage(`Asunto y mensaje no pueden estar vacíos`)
				}
				subject = btoa(subject);
				subject = encodeRFC5987ValueChars(subject);
				message = message.split('\n').join('<br>').split(' ').join('&nbsp;');
				message = btoa(message);
				message = encodeRFC5987ValueChars(message);
				let url = `/admin/email/send_order/${id}?to=${email}&subject=${subject}&message=${message}`;
				return fetch(url)
					.then(response => {
						if (!response.ok) {
							throw new Error(response.statusText)
						}
						return response.json()
					})
					.catch(error => {
						Swal.showValidationMessage(
							`Solicitud fallida: ${error}`
						)
					});
			},
			allowOutsideClick: () => !Swal.isLoading()
		}).then((result) => {
			if (result.value) {
				Swal.fire(
					'Pedido enviado',
					'El pedido se ha enviado correctamente al correo del cliente',
					'success'
				)
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
