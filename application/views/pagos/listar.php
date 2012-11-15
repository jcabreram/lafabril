<!-- Page Head -->
<h2><?php echo $title; ?></h2>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Lista de Pagos</h3>

		<ul class="content-box-tabs">
			<li><a href="#" class="current">Tabla</a></li>
			<li><a href="#filtrar" rel="modal">Filtrar</a></li>
			<li><a href="<?php echo site_url('pagos/exportar' . getParameters()); ?>" target="_blank">Exportar a PDF</a></li>
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

	<?php if (count($paymentsData) > 0) : ?>	

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Folio</th>
				   <th>Sucursal</th>
				   <th>Cliente</th>
				   <th>Fecha</th>
				   <th class="textAlign-right">Importe</th>
				   <th class="textAlign-center">Estatus</th>
				</tr>
			</thead>
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>
			
			<tbody>
			<?php foreach ($paymentsData as $paymentData) : ?>
				<?php if ($paymentData['estatus'] != 'P'): ?>
				<tr>
					<td><a href="<?php echo site_url("pagos/detalles/{$paymentData['id_pago_factura']}"); ?>" ><?php echo $paymentData['prefijo'].str_pad($paymentData['folio'], 9, "0", STR_PAD_LEFT); ?></a></td>
					<td><?php echo $paymentData['nombre_sucursal']; ?></td>
					<td><?php echo $paymentData['nombre_cliente']; ?></td>
					<td><?php echo strftime('%d/%b/%Y',strtotime($paymentData['fecha'])); ?></td>
					<td class="textAlign-right">$<?php echo number_format($paymentData['importe'], 2, '.', ','); ?></td>
					<td class="textAlign-center"><?php echo $paymentData['estatus']; ?></td>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			</tbody>

		</table>

	<?php else : ?>

		<!-- Notification (error type) -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div>No pudimos encontrar ningún pago con las especificaciones indicadas.</div>
		</div>

	<?php endif; ?> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->