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

<?php if (count($orders) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Sucursal</th>
		   <th>Vendedor</th>
		   <th>Cliente</th>
		   <th>Fecha de Pedido</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($orders as $order) : ?>
		<tr>
			<td><?php echo $order['prefijo'] . str_pad($order['folio'], 9, '0', STR_PAD_LEFT); ?></td>
			<td><?php echo $order['nombre_sucursal']; ?></td>
			<td><?php echo $order['nombre_vendedor']; ?></td>
			<td><?php echo $order['nombre_cliente']; ?></td>
			<td><?php echo date('d/m/Y', strtotime($order['fecha_pedido'])); ?></td>
			<td class="textAlign-center"><?php echo getStatusName($order['estatus']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>No existen pedidos con esas especificaciones.</p>
<?php endif; ?>