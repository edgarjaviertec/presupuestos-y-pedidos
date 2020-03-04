<?php
$sum_of_subtotal = (isset($sum_of_subtotal)) ? $sum_of_subtotal : NULL;
$sum_of_discount = (isset($sum_of_discount)) ? $sum_of_discount : NULL;
$sum_of_tax = (isset($sum_of_tax)) ? $sum_of_tax : NULL;
$sum_of_total = (isset($sum_of_total)) ? $sum_of_total : NULL;
$month = (isset($month)) ? $month : NULL;
$year = (isset($year)) ? $year : NULL;
$estimates = (isset($estimates)) ? $estimates : NULL;
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
    <?php if (count($estimates) == 0): ?>
        <div class="empty">
            No hay registros en este mes o este año
        </div>
    <?php endif; ?>
    <?php if (count($estimates) > 0): ?>
        <tr>
            <th class="bordered">Cliente</th>
            <th class="bordered">Teléfono</th>
            <th class="bordered">RFC</th>
            <th class="bordered">Presupuestos</th>
            <th class="bordered">Total</th>
        </tr>
        <?php foreach ($estimates as $estimate): ?>
            <tr>
                <td class="bordered"><?php echo $estimate->nombre_razon_social ?></td>
                <td class="bordered"><?php echo $estimate->telefono ?></td>
                <td class="bordered"><?php echo $estimate->rfc ?></td>
                <td class="bordered"><?php echo $estimate->presupuestos ?></td>
                <td class="bordered"><?php echo text_truncate("$" . number_format($estimate->total, 2), 13) ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <th class="totals" colspan="4">Suma</th>
            <td class="bordered"><?php echo text_truncate("$" . number_format($sum_of_total, 2), 13) ?></td>
        </tr>
    <?php endif; ?>
</table>