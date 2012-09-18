<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos del Usuario</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('usuarios/editar/' . $userData['id']); ?>" method="post">

			<p>
				<label>Nombre Completo *</label>
				<input class="text-input medium-input" value="<?php echo set_value('fullName', $userData['nombre']); ?>" type="text" name="fullName" />
				<?php echo form_error('fullName'); ?>
			</p>

			<p>
				<label>Nombre de Usuario *</label>
				<input class="text-input medium-input" value="<?php echo set_value('username', $userData['username']); ?>" type="text" name="username" />
				<input value="<?php echo $userData['username']; ?>" type="hidden" name="originalUsername" />
				<?php echo form_error('username'); ?>
			</p>
			
			<p>
				<label>Contraseña</label>
				<input class="text-input medium-input" type="password" name="password" />
				<?php echo form_error('password'); ?>
			</p>

			<p>
				<label>Repetir Contraseña</label>
				<input class="text-input medium-input" type="password" name="repassword" />
				<?php echo form_error('repassword'); ?>
			</p>
			
			<p>
				<label>Departamento *</label>              
				<select name="department" class="small-input">
					<option value="">Escoge una opción</option>
					<option value="ventas" <?php echo set_select('department', 'ventas', (!$_POST & ($userData['departamento'] == 'ventas')) ? true : false); ?>>Ventas</option>
					<option value="cuentasxcobrar" <?php echo set_select('department', 'cuentasxcobrar', (!$_POST & ($userData['departamento'] == 'cuentasxcobrar')) ? true : false); ?>>Cuentas por Cobrar</option>
					<option value="admin" <?php echo set_select('department', 'admin', (!$_POST & ($userData['departamento'] == 'admin')) ? true : false); ?>>Administración</option>
				</select> 
				<?php echo form_error('department'); ?>
			</p>
			
			<p>
				<label>Estatus *</label>
				<input type="radio" name="status" value="1" <?php echo set_radio('status', '1', (!$_POST & ($userData['activo'] == '1')) ? true : false); ?> /> Activo<br />
				<input type="radio" name="status" value="0" <?php echo set_radio('status', '0', (!$_POST & ($userData['activo'] == '0')) ? true : false); ?> /> Inactivo
			</p>
			
			
			<p>
				<input class="button" type="submit" value="Editar" />
			</p>

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->