<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos del Usuario</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<!-- Notification -->
		<div class="notification attention png_bg">
			<!-- Close link -->
			<a href="#" class="close"><img src="<?php echo site_url('resources/images/icons/cross_grey_small.png'); ?>" title="Cerrar Notificación" alt="cerrar" /></a>
			<!-- Message -->
			<div>Una vez creados, los usuarios no pueden ser eliminados.</div>
		</div>

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('usuarios/registrar'); ?>" method="post">
		
		<fieldset class="column-left">

			<p>
				<label>Nombre Completo *</label>
				<input class="text-input medium-input" value="<?php echo set_value('fullName'); ?>" type="text" name="fullName" />
				<?php echo form_error('fullName'); ?>
			</p>

			<p>
				<label>Nombre de Usuario *</label>
				<input class="text-input medium-input" value="<?php echo set_value('username'); ?>" type="text" name="username" />
				<?php echo form_error('username'); ?>
			</p>

			<p>
				<label>Contraseña *</label>
				<input class="text-input medium-input" type="password" name="password" />
				<?php echo form_error('password'); ?>
			</p>

			<p>
				<label>Repetir Contraseña *</label>
				<input class="text-input medium-input" type="password" name="repassword" />
				<?php echo form_error('repassword'); ?>
			</p>

			<p>
				<label>Departamento *</label>              
				<select name="department" class="small-input">
					<option value="">Escoge una opción</option>
					<option value="ventas" <?php echo set_select('department', 'ventas'); ?>>Ventas</option>
					<option value="cuentasxcobrar" <?php echo set_select('department', 'cuentasxcobrar'); ?>>Cuentas por Cobrar</option>
					<option value="admin" <?php echo set_select('department', 'admin'); ?>>Administración</option>
				</select> 
				<?php echo form_error('department'); ?>
			</p>

			<p>
				<label>Estatus *</label>
				<input type="radio" name="status" value="1" <?php echo set_radio('status', '1', true); ?> /> Activo<br />
				<input type="radio" name="status" value="0" <?php echo set_radio('status', '0'); ?> /> Inactivo
			</p>

			<p>
				<input class="button" type="submit" value="Registrar" />
			</p>
			
			</fieldset>
			
			<fieldset class="column-right">
			
			<p><label>Sucursales</label></p>
			
				<?php foreach ($branchesData as $branch) : ?>
					<p>
						<input type="checkbox" name="sucursales[]" value=<?php echo '"' . $branch['id_sucursal'].'" ' . "/>";
						echo $branch['nombre']; ?>
					</p>
				<?php endforeach; ?>
			
			</fieldset>
			
			<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->