<table class="information">
	<tr>
		<th>Cliente</th>
		<td><?php echo $order['cliente_nombre']; ?></td>
		<th>Vendedor</th>
		<td><?php echo $order['vendedor_nombre']; ?></td>
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
			<td class="textAlign-right">$<?php echo number_format($product['precio']); ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['cantidad'] * $product['precio']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<th class="textAlign-right">Subtotal</th>
			<td class="textAlign-right">$<?php echo number_format($subtotal); ?></td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<th class="textAlign-right">IVA (<?php echo $order['sucursal_iva'] * 100; ?>%)</th>
			<td class="textAlign-right">$<?php echo number_format($iva); ?></td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<th class="textAlign-right">Total</th>
			<td class="textAlign-right">$<?php echo number_format($total); ?></td>
		</tr>
	</tfoot>
</table>
<?php else : ?>
<p>El pedido no contiene productos.</p>
<?php endif; ?>