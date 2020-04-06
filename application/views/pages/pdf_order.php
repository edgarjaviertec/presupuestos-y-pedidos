<?php
$order = (isset($order)) ? $order : NULL;
$lines = (isset($lines)) ? $lines : NULL;
$customer = (isset($customer)) ? $customer : NULL;
$current_date_ymd = date('Y-m-d', strtotime($order->fecha_pedido));
$current_date_dmy = date('d/m/Y', strtotime($order->fecha_pedido));
$due_date_dmy = date('d/m/Y', strtotime($order->fecha_vencimiento));
$due_date_ymd = date('Y-m-d', strtotime($order->fecha_vencimiento));
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
<?php if( !empty($order->eliminado_en) ): ?>
    <span class="cancelled-watermark">Cancelado</span>
<?php endif; ?>
<img class="logo" src="<?php echo $_SERVER["DOCUMENT_ROOT"] . '/assets/img/logo.jpg'; ?>">
<div class="company-info">
	<h3>MARÍA ELENA COCOM CHAY</h3>
	<p>REG. 219, MZA. 27, LTE. 14, A 2 CUADRAS DE LA AV. TALLERES, 1ERA. ENTRADA DE LA REG. 94, CANCÚN, Q. ROO.</p>
</div>
<div class="due-date">
	<span>FECHA DE VENCIMIENTO</span>
	<span><?php echo $due_date_dmy ?></span>
</div>
<table class="number-and-date">
	<tr>
		<td>
			<table class="number">
				<tr>
					<th>Pedido</th>
				</tr>
				<tr>
					<td><?php echo $order->folio ?></td>
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
			<td class="notes" colspan="2" rowspan="5">
				<span class="notes-label">Notas</span>
				<span class="notes-text"><?php echo text_truncate($order->notas , 430) ?></span>
			</td>
			<th>
				<span class="summary-label">Sub-total</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate(   "$" . number_format($order->sub_total, 2)   , 13) ?></span>
			</td>
		</tr>
		<tr class="summary">
			<th>
				<span class="summary-label">Descuento</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate( "-$" . number_format($order->cantidad_descontada, 2), 13) ?></span>
			</td>
		</tr>
		<tr class="summary">
			<th>
				<span class="summary-label">IVA</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate("$" . number_format($order->impuesto, 2), 13) ?></span>
			</td>
		</tr>
		<tr class="summary">
			<th>
				<span class="summary-label">Total</span>
			</th>
			<td>
				<span class="summary-text"><?php echo text_truncate("$" . number_format($order->total, 2), 13) ?></span>
			</td>
		</tr>
        <tr class="summary">
            <th>
                <span class="summary-label">Saldo</span>
            </th>
            <td>
                <span class="summary-text"><?php echo text_truncate("$" . number_format($order->saldo, 2), 13) ?></span>
            </td>
        </tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>