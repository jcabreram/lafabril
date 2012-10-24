<!-- Page Head -->
<h2><?php echo $title; ?></h2>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Lista de Pedidos</h3>

		<ul class="content-box-tabs">
			<li><a href="#" class="current">Tabla</a></li>
			<li><a href="#filtrar" rel="modal">Filtrar</a></li>
			<li><a href="<?php echo site_url('pedidos/exportar' . getParameters()); ?>" target="_blank">Exportar a PDF</a></li>
		</ul>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

	<?php if ($this->session->flashdata('message')) : ?>
		<!-- Notification -->
		<div class="notification success png_bg">
			<!-- Close link -->
			<a href="#" class="close"><img src="<?php echo site_url('resources/images/icons/cross_grey_small.png'); ?>" title="Cerrar Notificación" alt="cerrar" /></a>
			<!-- Message -->
			<div><?php echo $this->session->flashdata('message'); ?></div>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Close link -->
			<a href="#" class="close"><img src="<?php echo site_url('resources/images/icons/cross_grey_small.png'); ?>" title="Cerrar Notificación" alt="cerrar" /></a>
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
	<?php endif; ?>

	<?php if (count($ordersData) > 0) : ?>	

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Folio</th>
				   <th>Sucursal</th>
				   <th>Vendedor</th>
				   <th>Cliente</th>
				   <th>Fecha de pedido</th>
				   <th>Estatus</th>
				</tr>
			</thead>
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>
			
			<tbody>
			<?php foreach ($ordersData as $orderData) : ?>
				<tr>
					<td><a href="<?php echo site_url("pedidos/detalles/{$orderData['id_pedido']}"); ?>" ><?php echo $orderData['prefijo'].str_pad($orderData['folio'], 9, "0", STR_PAD_LEFT); ?></a></td>
					<td><?php echo $orderData['nombre_sucursal']; ?></td>
					<td><?php echo $orderData['nombre_vendedor']; ?></td>
					<td><?php echo $orderData['nombre_cliente']; ?></td>
					<td><?php echo strftime('%d/%b/%Y',strtotime($orderData['fecha_pedido'])); ?></td>
					<td><?php echo $orderData['estatus']; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>

		</table>

	<?php else : ?>

		<!-- Notification (error type) -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div>No pudimos encontrar ningún pedido con las especificaciones indicadas.</div>
		</div>

	<?php endif; ?> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->