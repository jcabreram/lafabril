<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos del Pedido</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('pedidos/registrar'); ?>" method="post">

			<p>
				<label>Sucursal *</label>              
				<select name="branch" class="small-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($branches as $branch) : ?>
					<option value="<?php echo $branch['id_sucursal']; ?>"><?php echo $branch['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('branch'); ?>
			</p>

			<p>
				<label>Vendedor *</label>              
				<select name="salesman" class="small-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($salesmen as $salesman) : ?>
					<option value="<?php echo $salesman['id_vendedor']; ?>"><?php echo $salesman['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('salesman'); ?>
			</p>

			<p>
				<label>Cliente *</label>              
				<select name="client" class="small-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>"><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('client'); ?>
			</p>

			<p>
				<label>Fecha del Pedido *</label>
				<input class="text-input medium-input" type="text" name="password" />
				<?php echo form_error('password'); ?>
			</p>

			<p>
				<label>Fecha de Entrega *</label>
				<input class="text-input medium-input" type="text" name="repassword" />
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

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->