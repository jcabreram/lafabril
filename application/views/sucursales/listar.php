<!-- Page Head -->
<h2><?php echo $title; ?></h2>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Lista de Sucursales</h3>

		<ul class="content-box-tabs">
			<li><a href="#" class="current">Tabla</a></li>
			<li><a href="#filtrar" rel="modal">Filtrar</a></li>
			<li><a href="<?php echo site_url('sucursales/exportar' . getParameters()); ?>" target="_blank">Exportar a PDF</a></li>
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

	<?php if (count($branches) > 0) : ?>	

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Nombre</th>
				   <th>Dirección</th>
				   <th class="textAlign-center">Estatus</th>
				   <th class="textAlign-center">Opciones</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($branches as $branch) : ?>
				<tr>
					<td><?php echo $branch['nombre']; ?></td>
					<td><?php echo $branch['direccion']; ?></td>
					<td class="textAlign-center"><?php
						if ($branch['estatus'] == 1) {
							echo '<img src="' . site_url('resources/images/icons/tick_circle.png') . '" alt="Activo" />';
						} else {
							echo '<img src="' . site_url('resources/images/icons/cross_circle.png') . '" alt="Inactivo" />';
						}	
					?></td>
					<td class="textAlign-center">
						<!-- Options Icons -->
						 <a href="<?php echo site_url("sucursales/editar/{$branch['id_sucursal']}"); ?>" title="Editar"><img src="<?php echo site_url('resources/images/icons/pencil.png'); ?>" alt="Editar" /></a>
						 <?php
						 if ($branch['estatus'] == 1) {
						 	echo '<a href="' . site_url("sucursales/desactivar/{$branch['id_sucursal']}") . '" title="Desactivar"><img src="' . site_url('resources/images/icons/cross.png') . '" alt="Desactivar" /></a>';
						 } else {
						 	echo '<a href="' . site_url("sucursales/activar/{$branch['id_sucursal']}") . '" title="Activar"><img src="' . site_url('resources/images/icons/tick.png') . '" alt="Activar" /></a>';
						 } ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>

		</table>

	<?php else : ?>

		<!-- Notification (error type) -->
		<div class="notification error png_bg">
			<!-- Close link -->
			<a href="#" class="close"><img src="<?php echo site_url('resources/images/icons/cross_grey_small.png'); ?>" title="Cerrar Notificación" alt="cerrar" /></a>
			<!-- Message -->
			<div>No pudimos encontrar ninguna sucursal. Debió ocurrir un error, <a href="<?php echo site_url('sucursales'); ?>" title="Intenta de nuevo">intenta de nuevo</a>.</div>
		</div>

	<?php endif; ?> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->