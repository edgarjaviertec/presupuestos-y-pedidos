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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/estilos.css') ?>" type="text/css"/>
</head>
<body>


<main class="container py-5">


	<div class="grid-top-controls">
		<div class="grid-pager-row-count-picker">
			<label class="grid-items-per-page-label">Filas por página</label>
			<select id="rowCountPicker" class="custom-select grid-items-per-page-select">
				<option value="10" selected>10</option>
				<option value="25">25</option>
				<option value="50">50</option>
			</select>
		</div>


		<div class="input-group grid-filter">
			<input type="text" id="filterInput" class="form-control" placeholder="Buscar...">

			<div class="input-group-append">
				<span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
			</div>
		</div>
	</div>

	<div class="ajax-table">
		<table class="table table-bordered table-hover" data-current-page="1" id="serverSideTable">
			<input type="hidden" id="orderBy">
			<thead>
			<tr>
				<th class="sort-header" data-column-name="actor_id">
					<span>#</span>
					<i class="sort-icon"></i>
				</th>
				<th class="sort-header" data-column-name="first_name">
					<span>Nombre</span>
					<i class="sort-icon"></i>
				</th>
				<th class="sort-header" data-column-name="last_name">
					<span>Apellido</span>
					<i class="sort-icon"></i>
				</th>
			</tr>
			</thead>
			<tbody id="tableRows"></tbody>
		</table>
		<div id="dtsLoading" class="dts-loading">Loading...</div>
	</div>
	<div class="grid-bottom-controls">
		<div class="grid-pager-control">
			<button id="paginationFirstButton" type="submit" class="btn grid-pager-first" disabled>
				<i class="fas fa-step-backward"></i>
			</button>
			<button id="paginationPrevButton" type="submit" class="btn grid-pager-prev" disabled>
				<i class="fas fa-play fa-flip-horizontal"></i>
			</button>
			<input id="currentPageInput" type="number" class="form-control grid-pager-control-input" value="1" min=0
				   max="10" disabled>
			<span class="grid-pager-max-pages-number" id="maximunPagesNumberLabel">/ &infin;</span>


			<button id="paginationNextButton" type="submit" class="btn grid-pager-next" disabled>
				<i class="fas fa-play"></i>
			</button>
			<button id="paginationLastButton" type="submit" class="btn grid-pager-last" disabled>
				<i class="fas fa-step-forward"></i>
			</button>


		</div>
		<div class="paginator-range-container">
			<span id="paginatorRangeLabel">1 – 10 de &infin;</span></span>
		</div>
	</div>


</main>

<script id="tableRow" type="text/x-handlebars-template">
	{{#each data}}
	<tr>
		<td>{{actor_id}}</td>
		<td>{{first_name}}</td>
		<td>{{last_name}}</td>
	</tr>
	{{/each}}
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>


<script>


    function buildQuery() {
        var result = "";
        var query = $('#filterInput').val().trim();


        var page = $('#currentPageInput').val().trim();
        var limit = $('#rowCountPicker option:selected').val().trim();

        var orderBy = $('#serverSideTable').attr('data-order-by');
        var ascending = $('#serverSideTable').attr('data-sort-direction');


        if (page !== null && page !== '') {
            result += `?page=${page}`;
        }

        if (limit !== null && limit !== '') {
            result += `&limit=${limit}`;
        }


        if (query !== null && query !== '') {
            result += `&query=${query}`;
        }

        if (typeof orderBy !== typeof undefined && orderBy !== false) {
            result += `&orderBy=${orderBy}`;
        }

        if (typeof ascending !== typeof undefined && ascending !== false) {
            result += `&ascending=${ascending}`;
        }

        console.log(result);
        return result
    }


    // validity.valid

    $('#currentPageInput').keyup(function (e) {

        var $currentPage = $('#serverSideTable').attr('data-current-page');
        var currentPage = (typeof $currentPage !== typeof undefined && $currentPage !== false) ? $currentPage : 1;

        if (e.keyCode == 13) {
            if (!e.target.validity.valid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: e.target.validationMessage,
                });

                $('#currentPageInput').val(currentPage);

            } else {
                setCurrentPage($('#currentPageInput').val());
                updateTable();
            }
        }
    });


    $('#currentPageInput').focus(function (e) {
        e.preventDefault();
        $(this).val('');
    });

    function setCurrentPage(currentPage) {
        $('#serverSideTable').attr('data-current-page', currentPage);
        $('#currentPageInput').val(currentPage);

    }


    window.onload = function (e) {
        e.preventDefault();
        updateTable();
    };

    $('#rowCountPicker').on('change', function () {
        setCurrentPage(1);
        updateTable();
    });

    function updatePagination(recordsTotal) {


        //var currentPage = $("#currentPageInput").val().trim();
        var $currentPage = $('#serverSideTable').attr('data-current-page');
        var currentPage = (typeof $currentPage !== typeof undefined && $currentPage !== false) ? $currentPage : 1;
        var recordsTotal = recordsTotal;
        var itemsPerPage = $('#rowCountPicker option:selected').val().trim();
        var maximunPagesNumber = Math.ceil(recordsTotal / itemsPerPage);
        var start = ((currentPage - 1) * itemsPerPage) + 1;
        var end = ((currentPage - 1) * itemsPerPage) + $("#tableRows tr").length;
        var range = `${start} – ${end} de ${recordsTotal}`;

        $('#serverSideTable').attr("data-last-page", maximunPagesNumber);


        $('#currentPageInput').prop('disabled', false);
        $('#currentPageInput').attr('max', maximunPagesNumber);

        console.log(`currentPage: ${currentPage}, maximunPagesNumber: ${maximunPagesNumber}`);


        if (currentPage == 1 && maximunPagesNumber == 1) {
            $('#paginationFirstButton').prop('disabled', true);
            $('#paginationPrevButton').prop('disabled', true);
            $('#paginationNextButton').prop('disabled', true);
            $('#paginationLastButton').prop('disabled', true);
        } else if (currentPage == 1) {
            $('#paginationFirstButton').prop('disabled', true);
            $('#paginationPrevButton').prop('disabled', true);
            $('#paginationNextButton').prop('disabled', false);
            $('#paginationLastButton').prop('disabled', false);
        } else if (currentPage == maximunPagesNumber) {
            $('#paginationFirstButton').prop('disabled', false);
            $('#paginationPrevButton').prop('disabled', false);
            $('#paginationNextButton').prop('disabled', true);
            $('#paginationLastButton').prop('disabled', true);
        } else {
            $('#paginationFirstButton').prop('disabled', false);
            $('#paginationPrevButton').prop('disabled', false);
            $('#paginationNextButton').prop('disabled', false);
            $('#paginationLastButton').prop('disabled', false);
        }


        $("#maximunPagesNumberLabel").text(`/ ${maximunPagesNumber}`);
        $("#paginatorRangeLabel").text(range);
    }


    function updateTable() {
        $('#dtsLoading').removeClass('d-none');
        loadData().then(function (res) {
            console.log("total de filas", res.count);
            var source = $('#tableRow').html();
            var template = Handlebars.compile(source);
            var html = template(res);
            var output = $('#tableRows');
            output.html(html);
            $('#dtsLoading').addClass('d-none');

            updatePagination(res.count)

        }).catch(function (e) {
            console.log(e);
        });
    }


    function loadData() {
        var url = "<?php echo base_url('actor/ajaxtable') ?>";
        url += buildQuery();
        return $.ajax({
            'url': url,
            'method': 'GET'
        }).promise();
    }


    $('.sort-header').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('asc')) {
            $('.sort-header').removeClass('asc');
            $('.sort-header').removeClass('desc');
            $(this).addClass('desc');
            $('#serverSideTable').attr("data-order-by", $(this).attr("data-column-name"));
            $('#serverSideTable').attr("data-sort-direction", 0);
        } else if ($(this).hasClass('desc')) {
            $('.sort-header').removeClass('asc');
            $('.sort-header').removeClass('desc');
            $(this).addClass('asc');
            $('#serverSideTable').attr("data-order-by", $(this).attr("data-column-name"));
            $('#serverSideTable').attr("data-sort-direction", 1);
        } else {
            $('.sort-header').removeClass('asc');
            $('.sort-header').removeClass('desc');
            $(this).addClass('asc');
            $('#serverSideTable').attr("data-order-by", $(this).attr("data-column-name"));
            $('#serverSideTable').attr("data-sort-direction", 1);
        }
        setCurrentPage(1);
        updateTable();
    });


    $('#paginationFirstButton').on('click', function (e) {
        e.preventDefault();
        setCurrentPage(1);
        updateTable();
    });


    $('#paginationPrevButton').on('click', function (e) {
        e.preventDefault();
        var currentPage = $('#serverSideTable').attr('data-current-page');
        if (typeof currentPage !== typeof undefined && currentPage !== false) {
            setCurrentPage(parseInt(currentPage) - 1);
            updateTable();
        }
    });


    $('#paginationNextButton').on('click', function (e) {
        e.preventDefault();
        var currentPage = $('#serverSideTable').attr('data-current-page');
        if (typeof currentPage !== typeof undefined && currentPage !== false) {
            setCurrentPage(parseInt(currentPage) + 1);
            updateTable();
        }
    });

    $('#paginationLastButton').on('click', function (e) {
        e.preventDefault();

        var lastPage = $('#serverSideTable').attr('data-last-page');
        if (typeof lastPage !== typeof undefined && lastPage !== false) {
            setCurrentPage(lastPage);
            updateTable();
        }
    });


</script>
</body>
</html>
