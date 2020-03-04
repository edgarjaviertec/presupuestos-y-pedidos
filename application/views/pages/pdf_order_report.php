<?php
$title = (isset($title)) ? $title : NULL;
$sum_of_subtotal = (isset($sum_of_subtotal)) ? $sum_of_subtotal : NULL;
$sum_of_discount = (isset($sum_of_discount)) ? $sum_of_discount : NULL;
$sum_of_tax = (isset($sum_of_tax)) ? $sum_of_tax : NULL;
$sum_of_total = (isset($sum_of_total)) ? $sum_of_total : NULL;
$month = (isset($month)) ? $month : NULL;
$year = (isset($year)) ? $year : NULL;
$orders = (isset($orders)) ? $orders : NULL;
function fechaCastellano($fecha)
{
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
}

$fecha_ES = fechaCastellano(date("Ymd"));
?>
<div class="header">
    <h1><?php echo strtoupper($title) ?></h1>
    <p><strong>Fecha del reporte:</strong> <?php echo $fecha_ES ?></p>
</div>
<table class="table">
    <?php if (count($orders) == 0): ?>
        <div class="empty">
            No hay registros en este mes o este año
        </div>
    <?php endif; ?>
    <?php if (count($orders) > 0): ?>
        <tr>
            <th class="bordered">Folio</th>
            <th class="bordered">Fecha</th>
            <th class="bordered">Cliente</th>
            <th class="bordered">Teléfono</th>
            <th class="bordered">RFC</th>
            <th class="bordered">Estado</th>
            <th class="bordered">Total</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td class="bordered"><?php echo $order->folio ?></td>
                <td class="bordered"><?php echo date('d/m/Y', strtotime($order->fecha_pedido)); ?></td>
                <td class="bordered"><?php echo $order->nombre_razon_social ?></td>
                <td class="bordered"><?php echo $order->telefono ?></td>
                <td class="bordered"><?php echo $order->rfc ?></td>
                <?php
                switch ($order->status) {
                    case 'paid':
                        $status_text = 'Pagado';
                        break;
                    case 'partially_paid':
                        $status_text = 'Parcialmente pagado';
                        break;
                    default:
                        $status_text = 'No pagado';
                }
                ?>
                <td class="bordered"><?php echo $status_text ?></td>
                <td class="bordered"><?php echo text_truncate("$" . number_format($order->total, 2), 13) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th class="totals" colspan="6">Suma</th>
            <td class="bordered"><?php echo text_truncate("$" . number_format($sum_of_total, 2), 13) ?></td>
        </tr>
    <?php endif; ?>
</table>