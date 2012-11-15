<!-- Page Head -->
<h2><?php echo $title; ?></h2>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Lista de Notas de Crédito</h3>
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

	<?php if (count($creditNotes) > 0) : ?>	

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Folio</th>
				   <th>Sucursal</th>
				   <th>Cliente</th>
				   <th>Fecha</th>
				   <th class="textAlign-center">Estatus</th>
				</tr>
			</thead>
			
			<?php
			// Putting setlocale function here, I don't know if it could be autoloaded.
			setlocale(LC_ALL, 'es_ES');
			?>
			
			<tbody>
			<?php foreach ($creditNotes as $creditNote) : ?>
				<?php if ($creditNote['estatus'] != 'P'): ?>
				<tr>
					<td><a href="<?php echo site_url("notas_credito/detalles/{$creditNote['id_nota_credito']}"); ?>" ><?php echo getFolio($creditNote['prefijo'], $creditNote['folio']); ?></a></td>
					<td><?php echo $creditNote['nombre_sucursal']; ?></td>
					<td><?php echo $creditNote['nombre_cliente']; ?></td>
					<td><?php echo strftime('%d/%b/%Y',strtotime($creditNote['fecha'])); ?></td>
					<td class="textAlign-center"><?php echo getStatusName($creditNote['estatus']); ?></td>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
			</tbody>

		</table>

	<?php else : ?>

		<!-- Notification (error type) -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div>No pudimos encontrar ningúna nota de crédito pago con las especificaciones indicadas.</div>
		</div>

	<?php endif; ?> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->