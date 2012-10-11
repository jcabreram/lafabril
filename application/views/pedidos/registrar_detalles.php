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

			<p><b>Sucursal</b>: <?php echo $sucursal['nombre'] ?></p>
			<p><b>Vendedor</b>: <?php echo $vendedor['nombre'] ?></p>  
			<p><b>Cliente</b>: <?php echo $cliente['nombre'] ?></p>        

		</fieldset>
		
		<fieldset class="column-right">
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>

			<p><b>Fecha de pedido</b>: <?php echo strftime('%A %d de %b del %Y',strtotime($order['fecha_pedido'])); ?></p>  
			<p><b>Fecha de entrega</b>: <?php echo strftime('%A %d de %b del %Y',strtotime($order['fecha_entrega'])); ?></p>   
			<p><b>Estatus</b>: <?php if ($order['estatus'] == 'A') {
				echo 'Abierto'; }
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
	
		<form action="<?php echo site_url("pedidos/registrar_detalles/$order_id"); ?>" method="post">
		
		<fieldset class="column-left">
	
			<p>
				<label>Producto *</label>              
				<select name="id_producto" class="large-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($products as $product) : ?>
					<option value="<?php echo $product['id_producto']; ?>"><?php echo $product['nombre'].' - '.$product['udm']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('producto'); ?>
			</p>
	
			<p>
				<label>Cantidad *</label>
				<input class="text-input medium-input" type="text" name="cantidad" />
				<?php echo form_error('cantidad'); ?>
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">
	
			<p>
				<label>Precio unitario *</label>
				$ <input class="text-input medium-input" type="text" name="precio" />
				<?php echo form_error('precio'); ?>
			</p>
			
			<br /><br />
			<p>
				<input class="button" type="submit" value="Agregar" />
			</p>
	
		</fieldset>
		
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
		<h3>Detalle de pedido</h3>
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
				   <th style="text-align:center">Opciones</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($order_details as $orderData) : ?>
				<tr>
					<td><?php echo $orderData['nombre']; ?></td>
					<td><?php echo $orderData['cantidad'].' '.$orderData['udm']; ?></td>
					<td style="text-align:right">$<?php echo number_format($orderData['precio'], 2, '.', ','); ?></td>
					<td style="text-align:right">$<?php echo number_format($orderData['cantidad']*$orderData['precio'], 2, '.', ',');?></td>
					<td style="text-align:center">
						<!-- Options Icons -->
						<?php echo '<a href="' . site_url("pedidos/eliminar/{$order['id_pedido']}/{$orderData['id_pedido_detalle']}") . '" title="Eliminar"><img src="' . site_url('resources/images/icons/cross.png') . '" alt="Eliminar" /></a>'; ?>
					</td>
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
				<td style="text-align:right"><b>Subtotal:</b></td>
				<td style="text-align:right">$<?php echo number_format($subtotal, 2, '.', ','); ?></td>
				<td></td>
			</tr>
			
			<tr>
				<td></td>
				<td></td>
				<td style="text-align:right"><b>IVA (<?php echo $sucursal['iva']*100; ?>%):</b></td>
				<td style="text-align:right">$<?php echo number_format($sucursal['iva']*$subtotal, 2, '.', ','); ?></td>
				<td></td>
			</tr>
			
			<tr>
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
				<td>
					<p>
						<a href="<?php echo site_url("pedidos"); ?>"><input class="button" type="submit" value="Finalizar" /></a>
					</p>
				</td>
			</tr>
			
			</tbody>

		</table>
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->