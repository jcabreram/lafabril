<!-- Page Head -->
<h2><?php echo $title; ?></h2>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Lista de Usuarios</h3>	
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

	<?php if (count($usersData) > 0) : ?>	

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th><input class="check-all" type="checkbox" /></th>
				   <th>Nombre</th>
				   <th>Nombre de Usuario</th>
				   <th>Departamento</th>
				   <th>Estatus</th>
				   <th>Opciones</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($usersData as $userData) : ?>
				<tr>
					<td><input type="checkbox" /></td>
					<td><?php echo $userData['nombre']; ?></td>
					<td><?php echo $userData['username']; ?></td>
					<td><?php echo $userData['departamento']; ?></td>
					<!-- Active/Inactive Image  -->
					<td><?php
						if ($userData['activo'] == 1) {
							echo '<img src="' . site_url('resources/images/icons/tick_circle.png') . '" alt="Activo" />';
						} else {
							echo '<img src="' . site_url('resources/images/icons/cross_circle.png') . '" alt="Inactivo" />';
						}	
					?></td>
					<!-- End Active/Inactive Image  -->
					<td>
						<!-- Options Icons -->
						 <a href="<?php echo site_url("usuarios/editar/{$userData['id']}"); ?>" title="Editar"><img src="<?php echo site_url('resources/images/icons/pencil.png'); ?>" alt="Editar" /></a>
						 <?php
						 if ($userData['activo'] == 1) {
						 	echo '<a href="' . site_url("usuarios/desactivar/{$userData['id']}") . '" title="Desactivar"><img src="' . site_url('resources/images/icons/cross.png') . '" alt="Desactivar" /></a>';
						 } else {
						 	echo '<a href="' . site_url("usuarios/activar/{$userData['id']}") . '" title="Activar"><img src="' . site_url('resources/images/icons/tick.png') . '" alt="Activar" /></a>';
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
			<div>No pudimos encontrar a ningún usuario. Debió ocurrir un error, <a href="<?php echo site_url('usuarios'); ?>" title="Intenta de nuevo">intenta de nuevo</a>.</div>
		</div>

	<?php endif; ?> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->