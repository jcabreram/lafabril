<table class="information">
	<tr>
		<th>Razón Social</th>
		<td><?php echo $bill['razon_social']; ?></td>
		<th>Fecha Nota Venta</th>
		<td><?php echo date('d/m/Y', strtotime($bill['fecha_nota_venta'])); ?></td>
	</tr>

	<tr>
		<th>Dirección</th>
		<td><?php echo $clientAddress; ?></td>
		<th>Pedido</th>
		<td><?php echo $orderFolio; ?></td>
	</tr>

	<tr>
		<th>RFC</th>
		<td><?php echo $bill['rfc']; ?></td>
		<th></th>
		<td></td>
	</tr>
</table>

<?php if (count($bill['products']) > 0) : ?>
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
	<?php foreach ($bill['products'] as $product) : ?>
		<tr>
			<td><?php echo $product['nombre_producto']; ?></td>
			<td><?php echo $product['cantidad'] . ' ' . $product['udm_producto']; ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['precio_producto'], 2, '.', ','); ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['cantidad'] * $product['precio_producto'], 2, '.', ','); ?></td>
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
			<th class="textAlign-right">IVA (<?php echo $bill['sucursal_iva'] * 100; ?>%)</th>
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
<p>Esta nota de venta no contiene productos.</p>
<?php endif; ?>