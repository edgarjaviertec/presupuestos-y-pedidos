<?php
$data_company_name = !empty($company_name) ? 'data-company-name="' . $company_name . '"' : '';
$data_document_id = !empty($document_id) ? 'data-document-id="' . $document_id . '"' : '';
$data_email = !empty($email) ? 'data-email="' . $email . '"' : '';
$data_id = !empty($id) ? 'data-id="' . $id . '"' : '';
?>
<button type="button" <?php echo $data_id ?> <?php echo $data_email ?> <?php echo $data_document_id ?> <?php echo $data_company_name ?>
		class="send-by-email-btn btn btn-secondary mr-2" title="Enviar PDF por email">
	<i class="fas fa-envelope"></i>
</button>
