<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos de la Nota</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('pedidos/crear_nota_venta/' . $order['id_pedido']); ?>" method="post">

			<fieldset class="column-left">

				<p><strong>Pedido</strong>: <?php echo getFolio($order['prefijo'], $order['folio']); ?></p>
				<p><strong>Sucursal</strong>: <?php echo $order['nombre_sucursal']; ?></p>
				<p><strong>Cliente</strong>: <?php echo $order['nombre_cliente']; ?></p>

			</fieldset>

			<fieldset class="column-right">

				<p><strong>Fecha del Pedido</strong>: <?php echo date('d/m/Y', strtotime($order['fecha_pedido'])); ?></p>
				<p><strong>Estatus del Pedido</strong>: <?php echo getOrderStatusName($order['estatus']); ?></p>
				<?php if ($order['estatus'] === 'A') : ?>
				<p><strong>Fecha de la Nota</strong>: <input type="text" name="billDate" class="text-input small-input date" value="<?php echo isset($_POST['invoiceDate']) ? $_POST['invoiceDate'] : date('d/m/Y'); ?>" />
					<?php if (isset($errors['date'])) { echo '<span class="input-notification error png_bg">' . $errors['date'] . '</span>'; } ?></p>
				<?php endif; ?>		

			</fieldset>

			<div class="clear"></div><!-- End .clear --> 

			<br /><br />

			<h4>Productos</h4>

			<br />

			<?php if (count($order['products']) > 0) : ?>
			<table>
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
						<td><?php echo $product['cantidad']; ?> <?php echo $product['udm']; ?></td>
						<td class="textAlign-right">$<?php echo number_format($product['precio'], 2, '.', ','); ?></td>
						<td class="textAlign-right">$<?php echo number_format($product['cantidad'] * $product['precio'], 2, '.', ','); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>

				<?php if ($order['estatus'] === 'A') : ?>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Subtotal</strong>:</td>
						<td class="textAlign-right">$<?php echo getMoneyFormat($subtotal); ?></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>IVA (<?php echo $order['sucursal_iva'] * 100; ?>%)</strong>:</td>
						<td class="textAlign-right">$<?php echo getMoneyFormat($taxes); ?></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Total</strong>:</td>
						<td class="textAlign-right">$<?php echo getMoneyFormat($total); ?></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Efectivo</strong>:</td>
						<td class="textAlign-right">$ <input type="text" name="cash" class="text-input fixed-small-input" /></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Tarjeta</strong>:</td>
						<td class="textAlign-right"><a href="#ingresar_tarjeta" rel="modal" title="Pagar con tarjeta">Agregar tarjeta</a></td>
					</tr>

					<tr>
						<td></td>
						<td></td>
						<td class="textAlign-right"><strong>Cambio</strong>:</td>
						<td class="textAlign-right">$0</td>
					</tr>
				</tfoot>
				<?php endif; ?>
			</table>
			<?php else : ?>
				<p>Este pedido no contiene productos.</p>
			<?php endif; ?>

			<?php if ($order['estatus'] === 'A') : ?>
			<br />
			<p><input class="button" type="submit" value="Pagar" /></p>
			<?php endif; ?>

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->