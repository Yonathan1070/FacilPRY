<?php


if ($informacion['transactionState'] == 4 ) {
	$estadoTx = "Transacción aprobada";
}

else if ($informacion['transactionState'] == 6 ) {
	$estadoTx = "Transacción rechazada";
}

else if ($informacion['transactionState'] == 104 ) {
	$estadoTx = "Error";
}

else if ($informacion['transactionState'] == 7 ) {
	$estadoTx = "Transacción pendiente";
}

else {
	$estadoTx=$informacion['mensaje'];
}


if (strtoupper($informacion['firma']) == strtoupper($informacion['firmacreada'])) {
?>
	<h2>Resumen Transacción</h2>
	<table>
	<tr>
	<td>Estado de la transaccion</td>
	<td><?php echo $estadoTx; ?></td>
	</tr>
	<tr>
	<tr>
	<td>ID de la transaccion</td>
	<td><?php echo $informacion['transactionId']; ?></td>
	</tr>
	<tr>
	<td>Referencia de la venta</td>
	<td><?php echo $informacion['reference_pol']; ?></td>
	</tr>
	<tr>
	<td>Referencia de la transaccion</td>
	<td><?php echo $informacion['referenceCode']; ?></td>
	</tr>
	<tr>
	<?php
	if($informacion['pseBank'] != null) {
	?>
		<tr>
		<td>cus </td>
		<td><?php echo $informacion['cus']; ?> </td>
		</tr>
		<tr>
		<td>Banco </td>
		<td><?php echo $informacion['pseBank']; ?> </td>
		</tr>
	<?php
	}
	?>
	<tr>
	<td>Valor total</td>
	<td>$<?php echo number_format($informacion['TX_VALUE']); ?></td>
	</tr>
	<tr>
	<td>Moneda</td>
	<td><?php echo $informacion['currency']; ?></td>
	</tr>
	<tr>
	<td>Descripción</td>
	<td><?php echo ($informacion['extra1']); ?></td>
	</tr>
	<tr>
	<td>Entidad:</td>
	<td><?php echo ($informacion['lapPaymentMethod']); ?></td>
    </tr>
    <tr>
	<td>Correo Usuario:</td>
	<td><?php echo ($informacion['correo']); ?></td>
    </tr>
    <tr>
	<td>Fecha de Pago:</td>
	<td><?php echo ($informacion['fechaPago']); ?></td>
	</tr>
	</table>
<?php
}
else
{
?>
	<h1>Error validando firma digital.</h1>
<?php
}
?>