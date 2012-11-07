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

<?php if (count($bills) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Sucursal</th>
		   <th>Cliente</th>
		   <th>Fecha Nota de Venta</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($bills as $bill) : ?>
		<tr>
			<td><?php echo $bill['prefijo'] . str_pad($bill['folio'], 9, '0', STR_PAD_LEFT); ?></td>
			<td><?php echo $bill['nombre_sucursal']; ?></td>
			<td><?php echo $bill['nombre_cliente']; ?></td>
			<td><?php echo date('d/m/Y', strtotime($bill['fecha_nota_venta'])); ?></td>
			<td class="textAlign-center"><?php echo $bill['estatus']; ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>No existen pedidos con esas especificaciones.</p>
<?php endif; ?>