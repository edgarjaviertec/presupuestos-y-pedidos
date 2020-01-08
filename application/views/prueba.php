<?php
defined('BASEPATH') OR exit('No direct script access allowed');
get_instance()->load->helper('url');
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Lista de actores</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/estilos.css') ?>" type="text/css"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
</head>
<body>
<main class="container py-5">

	<div class="p-5">

		<?php echo $hola ?>

		<select id='s2' style='width: 200px;'>
			<option value='0'>Selecione un actor</option>
		</select>
	</div>


	<table border="0" class="display" id="example" width="100%">
		<thead>
		<tr>
			<th width="20%">actor_id</th>
			<th width="40%">first_name</th>
			<th width="40%">last_name</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>loading...</td>
		</tr>
		</tbody>
	</table>


</main>
<!--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>-->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>-->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $("#s2").select2({
            "ajax": {
                "url": "<?php echo base_url('ajax/select2') ?>",
                "delay": 400,
                "data": function (params) {
                    return {
                        "draw": 1,
                        "columns": [
                            {
                                "data": "actor_id",
                                "searchable": true,
                                "orderable": true
                            },
                            {
                                "data": "first_name",
                                "searchable": true,
                                "orderable": true
                            },
                            {
                                "data": "last_name",
                                "searchable": true,
                                "orderable": true
                            }
                        ],
                        "order": [
                            {
                                "column": 1,
                                "dir": 'asc'
                            }
                        ],
                        "start": (((params.page || 1) - 1) * 10),
                        "length": 10,
                        "search": {
                            "value": params.term
                        },
                    };
                },
                "processResults": function (res, params) {
                    params.page = params.page || 1;
                    return {
                        "results": $.map(res.data, function (obj) {
                            obj.id = obj.id || obj.actor_id; // replace pk with your identifier
                            obj.text = obj.text || (obj.first_name + " " + obj.last_name); // replace name with the property used for the text
                            return obj;
                        }),
                        "pagination": {
                            "more": (params.page * 10) < res.recordsFiltered
                        }
                    };
                },
                "cache": true
            }
        });

        $('#example').dataTable({
            "serverSide": true,
            "responsive": true,
            "ajax": "<?php echo base_url('ajax/dt') ?>"
        });
    });


    $('#s2').on('select2:select', function (e) {
        console.log(e.params.data);
    });


</script>
</body>
</html>
