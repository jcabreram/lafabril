<table class="filters">
	<tr>
		<th>Sucursal</th>
		<td><?php echo $branch; ?></td>
		<th>De fecha</th>
		<td><?php echo $since; ?></td>
	</tr>

	<tr>
		<th>Cliente</th>
		<td><?php echo $client; ?></td>
		<th>A fecha</th>
		<td><?php echo $until; ?></td>
</table>

<?php if (count($bills) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Fecha Nota Venta</th>
		   <th>Cliente</th>
		   <th class="textAlign-center">Estatus</th>
		   <th class="textAlign-right">Importe</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($bills as $bill) : ?>
		<tr>
			<td><?php echo getFolio($bill['prefijo'], $bill['folio']); ?></td>
			<td><?php echo convertToHumanDate($bill['fecha_nota_venta']); ?></td>
			<td><?php echo $bill['nombre_cliente']; ?></td>
			<td class="textAlign-center"><?php echo $bill['estatus']; ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($bill['importe']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	
	<tfoot>
		<?php foreach ($payments as $payment) : ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th class="textAlign-center"><?php echo $payment['nombre']; ?></th>
			<td class="textAlign-right">$<?php echo getMoneyFormat($payment['total']); ?></td>
		</tr>
		<?php endforeach; ?>
		
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th class="textAlign-center">Total</th>
			<td class="textAlign-right">$<?php echo getMoneyFormat($total); ?></td>
		</tr>
	</tfoot>
</table>

<?php endif; ?>