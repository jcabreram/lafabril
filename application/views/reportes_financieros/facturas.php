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

<?php if (count($invoices) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Fecha de Factura</th>
		   <th>Cliente</th>
		   <th class="textAlign-center">Estatus</th>
		   <th class="textAlign-right">Importe</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($invoices as $invoice) : ?>
		<tr>
			<td><?php echo getFolio($invoice['prefijo'], $invoice['folio']); ?></td>
			<td><?php echo convertToHumanDate($invoice['fecha_factura']); ?></td>
			<td><?php echo $invoice['nombre_cliente']; ?></td>
			<td class="textAlign-center"><?php echo $invoice['estatus']; ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($invoice['importe']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	
	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th class="textAlign-center">Total</th>
			<td class="textAlign-right">$<?php echo getMoneyFormat($total); ?></td>
		</tr>
	</tfoot>
</table>
<?php else : ?>
<p>No existen pedidos con esas especificaciones.</p>
<?php endif; ?>