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
    $('#dataTables').dataTable({
        "serverSide": true,
        "responsive": true,
        "ajax": "/admin/customers/get_customers_ajax",
        "columnDefs": [
            {
                "targets": 'no-sort',
                "orderable": false,
            }
        ],
        "language": spanishLang
    });
    $('#dataTables').on('click', '.delete_btn', function (e) {
        e.preventDefault();
        var form = $(this).closest("form");
        Swal.fire({
            customClass: {
                container: 'confirmation-modal',
            },
            title: 'Eliminar',
            text: "¿Estas seguro de querer eliminar este cliente?",
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