<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Datos generales del pago</h3>
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

			<p><b>Sucursal</b>: <?php echo $payment['nombre_sucursal'] ?></p>
			<p><b>Cliente</b>: <?php echo $payment['nombre_cliente'] ?></p>   
			<p><b>Importe</b>: $<?php echo number_format($payment['importe'], 2, '.', ','); ?></p>   

		</fieldset>
		
		<fieldset class="column-right">
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>

			<p><b>Fecha</b>: <?php echo strftime('%A %d de %b del %Y',strtotime($payment['fecha'])); ?></p>  
			<p><b>Tipo de pago</b>: <?php echo $payment['tipo_pago'] ?></p>  
			<p><b>Estatus</b>:
			
			<?php if ($payment['estatus'] == 'A') {
						echo 'Abierto';
					} else if ($payment['estatus'] == 'C') {
						echo 'Cerrado';
					} else if ($payment['estatus'] == 'X') {
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
		<h3>Detalle del pago</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Factura</th>
				   <th>Fecha</th>
				   <th style="text-align:right">Importe</th>
				   <th style="text-align:right">Saldo</th>
				   <th style="text-align:right">Pago</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($payment_details as $paymentData) : ?>
				<tr>
					<td><?php echo $paymentData['prefijo'].str_pad($paymentData['folio'], 9, "0", STR_PAD_LEFT); ?></td>
					<td><?php echo $paymentData['fecha']; ?></td>
					<td style="text-align:right">$<?php echo number_format($paymentData['importe_factura'], 2, '.', ','); ?></td>
					<td style="text-align:right">$<?php echo number_format($paymentData['saldo_factura'], 2, '.', ','); ?></td>
					<td style="text-align:right">$<?php echo number_format($paymentData['importe_pago'], 2, '.', ','); ?></td>
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
				<td style="text-align:right"><b>Total:</b></td>
				<td style="text-align:right">$<?php echo number_format($total, 2, '.', ','); ?></td>
			</tr>
			
			</tbody>

		</table>
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->

<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Opciones...</h3>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
					<div class="tab-content default-tab">
						<table>
							<thead>
								<tr>
									<th style="text-align:center">
										<a href="<?php echo site_url('pagos/cancelar/' . $payment['id_pago_factura']); ?>" target="_blank"><input class="button" type="button" value="Cancelar" /></a>
									</th>
								</tr>
							</tbody>
						</table>
					</div> <!-- End #tab3 -->        
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
			<div class="clear"></div>