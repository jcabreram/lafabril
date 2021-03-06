<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos de la Factura</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('pedidos/facturar/' . $order['id_pedido']); ?>" method="post">

			<fieldset class="column-left">

				<p><strong>Pedido</strong>: <?php echo $order['prefijo'] . str_pad($order['folio'], 9, '0', STR_PAD_LEFT); ?></p>
				<p><strong>Sucursal</strong>: <?php echo $order['nombre_sucursal']; ?></p>
				<p><strong>Cliente</strong>: <?php echo $order['nombre_cliente']; ?></p>

			</fieldset>

			<fieldset class="column-right">

				<p><strong>Fecha del Pedido</strong>: <?php echo date('d/m/Y', strtotime($order['fecha_pedido'])); ?></p>
				<p><strong>Estatus del Pedido</strong>: <?php echo getStatusName($order['estatus']); ?></p>
				<p><strong>Fecha de la Factura</strong>: <input type="text" name="invoiceDate" class="text-input small-input date" value="<?php echo isset($_POST['invoiceDate']) ? $_POST['invoiceDate'] : date('d/m/Y'); ?>" />
					<?php if (isset($errors['date'])) { echo '<span class="input-notification error png_bg">' . $errors['date'] . '</span>'; } ?></p>

			</fieldset>

			<div class="clear"></div><!-- End .clear --> 

			<br /><br />

			<h4>Productos</h4>

			<br />

			<?php if (count($order['products']) > 0) : ?>
			<table id="invoiceProducts">
				<thead>
					<tr>
						<th>Producto</th>
						<th>Cantidad Ordenada</th>
						<th>Cantidad Surtida</th>
						<th>Cantidad Deseada</th>
						<th class="textAlign-right">Precio</th>
						<th class="textAlign-right">Importe</th>
					</tr>
				</thead>

				<tbody>
				<?php foreach ($order['products'] as $product) : ?>
					<tr>
						<td><?php echo $product['nombre']; ?></td>
						<td><?php echo $product['cantidad']; ?> <?php echo $product['udm']; ?><input type="hidden" value="<?php echo $product['cantidad']; ?>" class="amountOrdered" /></td>
						<td><?php echo $product['cantidad_surtida']; ?> <?php echo $product['udm']; ?><input type="hidden" value="<?php echo $product['cantidad_surtida']; ?>" class="amountDelivered" /></td>
						<td><input type="text" name="products[<?php echo $product['id_producto']; ?>]" class="text-input fixed-small-input userAmount" value="<?php echo isset($_POST['products'][$product['id_producto']]) ? $_POST['products'][$product['id_producto']] : roundMoney($product['cantidad'] - $product['cantidad_surtida']); ?>" />
							<?php echo $product['udm']; ?>
							<?php if (isset($errors['products'][$product['id_producto']])) : ?>
							<span class="input-notification error png_bg"><?php echo $errors['products'][$product['id_producto']]; ?></span>
							<?php endif; ?>
						</td>
						<td class="textAlign-right">$<?php echo getMoneyFormat($product['precio']); ?><input type="hidden" value="<?php echo $product['precio']; ?>" class="productPrice" /></td>
						<td class="textAlign-right"></td>
					</tr>
				<?php endforeach; ?>
				</tbody>

				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Subtotal</strong>:</td>
						<td class="textAlign-right invoiceSubtotal"></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="textAlign-right"><input type="hidden" value="<?php echo $order['sucursal_iva']; ?>" class="invoiceVAT" /><strong>IVA (<?php echo $order['sucursal_iva'] * 100; ?>%)</strong>:</td>
						<td class="textAlign-right invoiceTax"></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Total</strong>:</td>
						<td class="textAlign-right invoiceTotal"></td>
					</tr>
				</tfoot>
			</table>
			<?php else : ?>
				<p>Este pedido no contiene productos.</p>
			<?php endif; ?>

			<br />
			<p><input class="button" type="submit" value="Facturar" /></p>

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->