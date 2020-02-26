<?php

?>


<h2 class="page-heading">
	<span>Presupuesto</span>
	<button id="printBtn" type="button" class="ml-3 btn btn-lg btn-success">
		<i class="fas fa-print"></i>
		<span class="ml-1 d-none d-sm-inline-block">Imprimir</span>
	</button>

	<a href="<?php echo base_url('admin/presupuestos/nuevo') ?>" class="ml-3 btn btn-lg btn-success">
		<i class="fas fa-edit"></i>
		<span class="ml-1 d-none d-sm-inline-block">Editar</span>
	</a>


</h2>


<style>


	.iframe-container{
		display: flex;
		flex-direction: column;
		height: 75vh;
		min-height: 0;
		overflow: hidden;
	}
	.iframe {
		flex: 1 1 auto;
		border: 1px solid #b9c1d1;
		border-radius: 7px;
	}
</style>
<div class="iframe-container">
	<iframe onreadystatechange="idPdf_onreadystatechange()" id="iframePrint" class="iframe" src="/admin/estimates/get_pdf#page=1&view=fith"" class="frame-style"></iframe>
</div>




