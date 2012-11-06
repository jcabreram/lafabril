<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Datos generales de la nota de venta</h3>
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

			<p><b>Folio</b>: <?php echo $bill['prefijo'].str_pad($bill['folio'], 9, "0", STR_PAD_LEFT); ?></p> 
			<p><b>Folio del pedido</b>: <a href="<?php echo site_url('pedidos/detalles/' . $bill['id_pedido']); ?>"><?php echo $order['prefijo'].str_pad($bill['folio'], 9, "0", STR_PAD_LEFT); ?></a></p>
			<p><b>Cliente</b>: <?php echo $$bill['nombre_cliente'] ?></p>     
			<p><b>RFC</b>: <?php echo $bill['rfc'] ?></p>        

		</fieldset>
		
		<fieldset class="column-right">
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>

			<p><b>Sucursal</b>: <?php echo $bill['nombre_sucursal'] ?></p>
			<p><b>Fecha de factura</b>: <?php echo strftime('%A %d de %b del %Y',strtotime($bill['fecha_factura'])); ?></p>   
			<p><b>Estatus</b>: 
			<?php if ($bill['estatus'] == 'A') {
						echo 'Abierta';
					} else if ($bill['estatus'] == 'C') {
						echo 'Cerrada';
					} else if ($bill['estatus'] == 'X') {
						echo 'Cancelada';
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
		<h3>Detalle de la factura</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Producto</th>
				   <th>Cantidad</th>
				   <th style="text-align:right">Precio unitario</th>
				   <th style="text-align:right">Importe</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($bill_details as $detail) : ?>
				<tr>
					<td><?php echo $detail['nombre_producto']; ?></td>
					<td><?php echo $detail['cantidad'].' '.$detail['udm_producto']; ?></td>
					<td style="text-align:right">$<?php echo number_format($detail['precio_producto'], 2, '.', ','); ?></td>
					<td style="text-align:right">$<?php echo number_format($detail['cantidad']*$detail['precio_producto'], 2, '.', ',');?></td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
				<td style="text-align:right"><b>Subtotal:</b></td>
				<td style="text-align:right">$<?php echo number_format($subtotal, 2, '.', ','); ?></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
				<td style="text-align:right"><b>IVA (<?php echo $bill['iva']*100; ?>%)</b></td>
				<td style="text-align:right">$<?php echo number_format($bill['iva']*$bill, 2, '.', ','); ?></td>
			</tr>
			
			<tr>
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
									<th style="text-align:center">
										<a href="<?php echo site_url('notas_venta/imprimir/' . $bill['id_nota_venta']); ?>" target="_blank"><input class="button" type="button" value="Imprimir" /></a>
									</th>
									<th style="text-align:center">
										<a href="<?php echo site_url('notas_venta/cancelar/' . $bill['id_nota_venta']); ?>" target="_blank"><input class="button" type="button" value="Cancelar" /></a>
									</th>
								</tr>
							</tbody>
						</table>
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			<div class="clear"></div>