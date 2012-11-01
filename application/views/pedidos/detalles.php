<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Datos generales del pedido</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>
		
		<fieldset class="column-left">

			<p><b>Sucursal</b>: <?php echo $order['nombre_sucursal'] ?></p>
			<p><b>Vendedor</b>: <?php echo $order['nombre_vendedor'] ?></p>  
			<p><b>Cliente</b>: <?php echo $order['nombre_cliente'] ?></p>        

		</fieldset>
		
		<fieldset class="column-right">
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>

			<p><b>Fecha de pedido</b>: <?php echo strftime('%A %d de %b del %Y',strtotime($order['fecha_pedido'])); ?></p>  
			<p><b>Fecha de entrega</b>: <?php echo strftime('%A %d de %b del %Y',strtotime($order['fecha_entrega'])); ?></p>   
			<p><b>Estatus</b>:
			
			<?php if ($order['estatus'] == 'A') {
						echo 'Abierto';
					} else if ($order['estatus'] == 'C') {
						echo 'Cerrado';
					} else if ($order['estatus'] == 'X') {
						echo 'Cancelado';
					}
			?></p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear --> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Detalle de pedido</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Producto</th>
				   <th>Cantidad pedida</th>
				   <th>Cantidad surtida</th>
				   <th style="text-align:right">Precio unitario</th>
				   <th style="text-align:right">Importe</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($order_details as $orderData) : ?>
				<tr>
					<td><?php echo $orderData['nombre']; ?></td>
					<td><?php echo $orderData['cantidad'].' '.$orderData['udm']; ?></td>
					<td><?php echo $orderData['cantidad_surtida'].' '.$orderData['udm']; ?></td>
					<td style="text-align:right">$<?php echo number_format($orderData['precio'], 2, '.', ','); ?></td>
					<td style="text-align:right">$<?php echo number_format($orderData['cantidad']*$orderData['precio'], 2, '.', ',');?></td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align:right"><b>Subtotal:</b></td>
				<td style="text-align:right">$<?php echo number_format($subtotal, 2, '.', ','); ?></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td style="text-align:right"><b>IVA (<?php echo $order['sucursal_iva']*100; ?>%)</b></td>
				<td style="text-align:right">$<?php echo number_format($order['sucursal_iva']*$subtotal, 2, '.', ','); ?></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>	
				<td></td>
				<td style="text-align:right"><b>Total:</b></td>
				<td style="text-align:right">$<?php echo number_format($total, 2, '.', ','); ?></td>
			</tr>
			
			</tbody>

		</table>
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->

<div class="content-box column-left"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Opciones...</h3>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab">
						<table>
							<thead>
								<tr>
									<?php if ($order['estatus'] != 'C') : ?>
									<th style="text-align:center">
										<a href="<?php echo site_url('pedidos/facturar/' . $order_id); ?>"><input class="button" type="button" value="Factura" /></a>
									</th>
									<th style="text-align:center">
										<a href="<?php echo site_url('pedidos/crear_nota_venta/' . $order_id); ?>"><input class="button" type="button" value="Nota de venta" /></a>
									</th>
									<?php endif; ?>
									<th style="text-align:center">
										<a href="<?php echo site_url('pedidos/imprimir/' . $order_id); ?>" target="_blank"><input class="button" type="button" value="Imprimir" /></a>
									</th>
									<th style="text-align:center">
										<a href="<?php echo site_url('pedidos/cancelar/' . $order_id); ?>"><input class="button" type="button" value="Cancelar" /></a>
									</th>
								</tr>
							</tbody>
						</table>
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			<div class="clear"></div>