<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos generales del pedido</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>
		
		<fieldset class="column-left">

			<p><b>Sucursal</b>: <?php echo $order['sucursal'] ?></p>
			<p><b>Vendedor</b>: <?php echo $order['vendedor'] ?></p>  
			<p><b>Cliente</b>: <?php echo $order['cliente'] ?></p>        

		</fieldset>
		
		<fieldset class="column-right">

			<p><b>Fecha de pedido</b>: <?php echo $order['fecha_pedido'] ?></p>  
			<p><b>Fecha de entrega</b>: <?php echo $order['fecha_entrega'] ?></p>   
			<p><b>Estatus</b>: <?php echo $order['estatus'] ?></p>   

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Ingresar detalle</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('pedidos/registrar_detalles'); ?>" method="post">
		
		<fieldset class="column-left">

			<p>
				<label>Producto *</label>              
				<select name="branch" class="medium-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($products as $product) : ?>
					<option value="<?php echo $product['id_producto']; ?>"><?php echo $product['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('producto'); ?>
			</p>

			<p>
				<label>Cantidad *</label>
				<input class="text-input medium-input" type="text" name="cantidad" />
				<?php echo form_error('cantidad'); ?>
			</p>
						
			<p>
				<input class="button" type="submit" value="Agregar" />
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">

			<p>
				<label>Precio *</label>
				<input class="text-input medium-input" type="text" name="precio" />
				<?php echo form_error('precio'); ?>
			</p>

			<p>
				<label>Cantidad surtida *</label>
				<input class="text-input medium-input" type="text" name="cantidad_surtida" />
				<?php echo form_error('cantidad_surtida'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Lista de Usuarios</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Producto</th>
				   <th>Cantidad</th>
				   <th>Precio</th>
				   <th>Cantidad Surtida</th>
				   <th>Opciones</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($usersData as $userData) : ?>
				<tr>
					<td><?php echo $orderData['producto']; ?></td>
					<td><?php echo $orderData['cantidad']; ?></td>
					<td>$<?php echo $orderData['precio']; ?></td>
					<td><?php echo $orderData['cantidad_surtida']; ?></td>
					<td>
						<!-- Options Icons -->
						<?php echo '<a href="' . site_url("usuarios/eliminar/{$orderData['id_pedido_detalle']}") . '" title="Eliminar"><img src="' . site_url('resources/images/icons/cross.png') . '" alt="Eliminar" /></a>'; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<p>
						<a href="<?php echo site_url("pedidos/listar"); ?>"><input class="button" type="submit" value="Agregar" /></a>
					</p>
				</td>
			</tr>
			</tbody>

		</table>
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->