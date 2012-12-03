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
			<p><b>Disponible</b>: $<?php echo number_format($disponible, 2, '.', ','); ?></p>    

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
		<h3>Ingresar línea</h3>
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
	
		<form action="<?php echo site_url("pagos/agregar_pago_detalles/{$payment['id_pago_factura']}"); ?>" method="post">
		
			<p>
				<label>Factura *</label>              
				<select name="invoice" class="large-input">
					<option value="escoge">Escoge una opción</option>
					<?php foreach ($invoices as $invoice) : ?>
					<option value="<?php echo $invoice['id_factura']; ?>"><?php echo $invoice['prefijo'].str_pad($invoice['folio'], 9, "0", STR_PAD_LEFT).' - Importe: $'.number_format($invoice['importe'], 2, '.', ',').' - Saldo: $'.number_format($invoice['saldo'], 2, '.', ',').' - Fecha: '.strftime('%d/%b/%Y',strtotime($invoice['fecha_factura'])); ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('invoice'); ?>
			</p>
	
			<p>
				<label>Pago *</label>
				$ <input class="text-input medium-input" type="text" name="pago" />
				<?php echo form_error('pago'); ?>
			</p>
		
			<p>
				<input class="button" type="submit" value="Agregar" />
			</p>
	
		<div class="clear"></div><!-- End .clear -->
	
		</form>					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Detalle del pago de facturas</h3>
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
				   <th style="text-align:center">Opciones</th>
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
					<td style="text-align:center">
						<!-- Options Icons -->
						<?php echo '<a href="' . site_url("pagos/eliminar/{$payment['id_pago_factura']}/{$paymentData['id_pago_factura_detalle']}") . '" title="Eliminar"><img src="' . site_url('resources/images/icons/cross.png') . '" alt="Eliminar" /></a>'; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			
			<tr>
				<td></td>
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
				<td></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<p>
						<a href="<?php echo site_url('pagos'); ?>"><input class="button" type="button" value="Finalizar" /></a>
					</p>
				</td>
			</tr>
			
			</tbody>

		</table>
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->