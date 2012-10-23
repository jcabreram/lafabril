<table class="information">
	<tr>
		<th>Cliente</th>
		<td><?php echo $order['nombre_cliente']; ?></td>
		<th>Vendedor</th>
		<td><?php echo $order['nombre_vendedor']; ?></td>
	</tr>

	<tr>
		<th>Dirección</th>
		<td><?php echo $clientAddress; ?></td>
		<th>Fecha de Entrega</th>
		<td><?php echo date('d/m/Y', strtotime($order['fecha_entrega'])); ?></td>
	</tr>
</table>

<?php if (count($order['products']) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Producto</th>
		   <th>Cantidad</th>
		   <th class="textAlign-right">Precio Unitario</th>
		   <th class="textAlign-right">Importe</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($order['products'] as $product) : ?>
		<tr>
			<td><?php echo $product['nombre']; ?></td>
			<td><?php echo $product['cantidad'] . ' ' . $product['udm']; ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['precio'], 2, '.', ','); ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['cantidad'] * $product['precio'], 2, '.', ','); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<th class="textAlign-right">Subtotal</th>
			<td class="textAlign-right">$<?php echo number_format($subtotal, 2, '.', ','); ?></td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<th class="textAlign-right">IVA (<?php echo $order['sucursal_iva'] * 100; ?>%)</th>
			<td class="textAlign-right">$<?php echo number_format($iva, 2, '.', ','); ?></td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<th class="textAlign-right">Total</th>
			<td class="textAlign-right">$<?php echo number_format($total, 2, '.', ','); ?></td>
		</tr>
	</tfoot>
</table>
<?php else : ?>
<p>El pedido no contiene productos.</p>
<?php endif; ?>