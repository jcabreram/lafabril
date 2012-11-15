<table class="information">
	<tr>
		<th>Cliente</th>
		<td><?php echo $payment['nombre_cliente']; ?></td>
		<th>Fecha</th>
		<td><?php echo convertToHumanDate($payment['fecha']); ?></td>
	</tr>

	<tr>
		<th>Tipo de Pago</th>
		<td><?php echo $payment['tipo_pago']; ?></td>
		<th>Estatus</th>
		<td><?php echo getStatusName($payment['estatus']); ?></td>
	</tr>
</table>

<?php if (count($paymentDetails) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Factura</th>
		   <th>Fecha Factura</th>
		   <th style="text-align:right">Importe</th>
		   <th style="text-align:right">Saldo</th>
		   <th style="text-align:right">Pago</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($paymentDetails as $detail) : ?>
		<tr>
			<td><?php echo getFolio($detail['prefijo'], $detail['folio']); ?></td>
			<td><?php echo convertToHumanDate($detail['fecha']); ?></td>
			<td style="text-align:right">$<?php echo getMoneyFormat($detail['importe_factura']); ?></td>
			<td style="text-align:right">$<?php echo getMoneyFormat($detail['saldo_factura']); ?></td>
			<td style="text-align:right">$<?php echo getMoneyFormat($detail['importe_pago']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th class="textAlign-right">Total</th>
			<td class="textAlign-right">$<?php echo getMoneyFormat($total); ?></td>
		</tr>
	</tfoot>
</table>
<?php else : ?>
<p>Este pago no tiene detalles.</p>
<?php endif; ?>