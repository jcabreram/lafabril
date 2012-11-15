<table class="information">
	<tr>
		<th>Cliente</th>
		<td><?php echo $creditNote['nombre_cliente']; ?></td>
		<th>Fecha</th>
		<td><?php echo convertToHumanDate($creditNote['fecha']); ?></td>
	</tr>

	<tr>
		<th>Estatus</th>
		<td><?php echo getStatusName($creditNote['estatus']); ?></td>
		<th>Concepto</th>
		<td><?php echo $creditNoteType; ?></td>
	</tr>
</table>

<?php if (count($creditNoteDetails) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Factura</th>
		   <th>Fecha Factura</th>
		   <th style="text-align:right">Importe</th>
		   <th style="text-align:right">Saldo</th>
		   <th style="text-align:right">Nota Crédito</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($creditNoteDetails as $detail) : ?>
		<tr>
			<td><?php echo getFolio($detail['prefijo'], $detail['folio']); ?></td>
			<td><?php echo convertToHumanDate($detail['fecha']); ?></td>
			<td style="text-align:right">$<?php echo getMoneyFormat($detail['importe_factura']); ?></td>
			<td style="text-align:right">$<?php echo getMoneyFormat($detail['saldo_factura']); ?></td>
			<td style="text-align:right">$<?php echo getMoneyFormat($detail['importe_nota_credito']); ?></td>
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
<p>Esta nota de crédito no tiene detalles.</p>
<?php endif; ?>