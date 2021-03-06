<?php
if (!empty($company_settings['company_logo'])) {
	$company_logo = "/uploads/{$company_settings['company_logo']}";
} else {
	$company_logo = "/assets/img/default-logo.png";
}
if (!empty($company_settings['business_name'])) {
	$business_name = $company_settings['business_name'];
} else {
	$business_name = 'Empresa sin razón social';
}
if (!empty($company_settings['company_name'])) {
	$company_name = $company_settings['company_name'];
} else {
	$company_name = 'Empresa sin nombre';
}
if (!empty($company_settings['company_address'])) {
	$company_address = $company_settings['company_address'];
} else {
	$company_address = 'Empresa sin dirección conocida';
}
$estimate = (isset($estimate)) ? $estimate : NULL;
$lines = (isset($lines)) ? $lines : NULL;
$customer = (isset($customer)) ? $customer : NULL;
$current_date_ymd = date('Y-m-d', strtotime($estimate->fecha_presupuesto));
$current_date_dmy = date('d/m/Y', strtotime($estimate->fecha_presupuesto));
$due_date_dmy = date('d/m/Y', strtotime($estimate->fecha_vencimiento));
$due_date_ymd = date('Y-m-d', strtotime($estimate->fecha_vencimiento));
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);
$full_name = $customer->nombre_razon_social;
$phone = $customer->telefono;
$mobilePhone = $customer->telefono_celular;
$phone = ($phone == '' && $mobilePhone != '') ? $mobilePhone : $phone;
$full_address_array = [];
$address = $customer->domicilio;
$city = $customer->ciudad;
$state = $customer->estado;
$country = $customer->pais;
$postal_code = $customer->codigo_postal;
if ($address != '') {
	array_push($full_address_array, $address);
}
if ($city != '') {
	array_push($full_address_array, $city);
}
if ($state != '') {
	array_push($full_address_array, $state);
}
if ($country != '') {
	array_push($full_address_array, $country);
}
if ($postal_code != '') {
	array_push($full_address_array, $postal_code);
}
$full_address = '';
foreach ($full_address_array as $key => $val) {
	if ($key != count($full_address_array)) {
		$full_address .= $val . ' ';
	} else {
		$full_address .= $val;
	}
}
?>
<?php if( !empty($estimate->eliminado_en) ): ?>
    <span class="cancelled-watermark">Cancelado</span>
<?php endif; ?>


<table>
	<tr>
		<td class="logo">
			<img src="<?php echo $_SERVER["DOCUMENT_ROOT"] . $company_logo ?>">
		</td>
		<td class="company-info">
			<h3><?php echo $company_name ?></h3>
			<h4><?php echo $business_name ?></h4>
			<p><?php echo $company_address ?></p>
		</td>
		<td class="due-date">
			<span>FECHA DE VENCIMIENTO</span>
			<span><?php echo $due_date_dmy ?></span>
		</td>
		<td class="number-and-date">
			<table>
				<tr>
					<td>
						<table class="number">
							<tr>
								<th>Presupuesto</th>
							</tr>
							<tr>
								<td><?php echo $estimate->folio ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class="date">
							<tr>
								<th>Fecha</th>
							</tr>
							<tr>
								<td><?php echo $current_date_dmy ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>



<table class="customer-info">
	<tr>
		<th>
			<span>Nombre</span>
		</th>
		<td>
			<span><?php echo text_truncate($full_name, 105) ?></span>
		</td>
	</tr>
	<tr>
		<th>
			<span>Dirección</span>
		</th>
		<td>
			<span><?php echo text_truncate( $full_address, 420) ?></span>
		</td>
	</tr>
	<tr>
		<th>
			<span>Teléfono</span>
		</th>
		<td>
			<span><?php echo text_truncate($phone, 105) ?></span>
		</td>
	</tr>
</table>
<?php $items = 1; ?>
<?php foreach ($lines as $i=>$line): ?>
<table class="lines">
	<?php if ($i == 0): ?>
		<tr>
			<th>Cant.</th>
			<th>Descripción</th>
			<th>P. unitario</th>
			<th>Importe</th>
		</tr>
	<?php endif; ?>
	<tr>
		<td class="qty">
			<span><?php echo text_truncate($line->cantidad, 13) ?></span>
		</td>
		<td class="item">
			<span
				class="name"><?php echo text_truncate($line->nombre, 130) ?></span>
			<span class="description">
				<?php echo text_truncate($line->descripcion, 400) ?>
			</span>
		</td>
		<td class="unit-price">
			<span><?php echo text_truncate(  "$" . number_format($line->precio_unitario, 4) , 13) ?></span>
		</td>
		<td class="total">
			<span><?php echo text_truncate("$" . number_format($line->total, 2), 13) ?></span>
		</td>
	</tr>
	<?php if ($i == (count($lines) - 1)): ?>
		<tr class="notes-and-summary">
			<td class="notes" colspan="2" rowspan="4">
				<span class="notes-label">Notas</span>
				<span class="notes-text"><?php echo text_truncate($estimate->notas , 430) ?></span>
			</td>
			<th>
				<span class="summary-label">Sub-total</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate(   "$" . number_format($estimate->sub_total, 2)   , 13) ?></span>
			</td>
		</tr>
		<tr class="summary">
			<th>
				<span class="summary-label">Descuento</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate( "-$" . number_format($estimate->cantidad_descontada, 2), 13) ?></span>
			</td>
		</tr>
		<tr class="summary">
			<th>
				<span class="summary-label">IVA</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate("$" . number_format($estimate->impuesto, 2), 13) ?></span>
			</td>
		</tr>
		<tr class="summary">
			<th>
				<span class="summary-label">Total</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate("$" . number_format($estimate->total, 2), 13) ?></span>
			</td>
		</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>
