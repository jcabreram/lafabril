<table class="filters">
	<tr>
		<th>Sucursal</th>
		<td><?php echo $branch; ?></td>
	</tr>

	<tr>
		<th>Cliente</th>
		<td><?php echo $client; ?></td>
	</tr>

	<tr>
		<th>Estatus</th>
		<td><?php echo $status; ?></td>
	</tr>
</table>

<?php if (count($invoices) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Sucursal</th>
		   <th>Cliente</th>
		   <th>Fecha de Factura</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($invoices as $invoice) : ?>
		<tr>
			<td><?php echo $invoice['prefijo'] . str_pad($invoice['folio'], 9, '0', STR_PAD_LEFT); ?></td>
			<td><?php echo $invoice['nombre_sucursal']; ?></td>
			<td><?php echo $invoice['nombre_cliente']; ?></td>
			<td><?php echo date('d/m/Y', strtotime($invoice['fecha_factura'])); ?></td>
			<td class="textAlign-center"><?php echo $invoice['estatus']; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>No existen pedidos con esas especificaciones.</p>
<?php endif; ?>