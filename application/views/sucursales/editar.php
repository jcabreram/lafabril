<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos de la Sucursal</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('sucursales/editar/' . $branch['id_sucursal']); ?>" method="post">

			<p>
				<label>Nombre *</label>
				<input class="text-input medium-input" value="<?php echo set_value('name', $branch['nombre']); ?>" type="text" name="name" />
				<?php echo form_error('name'); ?>
			</p>

			<p>
				<label>Dirección</label>
				<input class="text-input medium-input" value="<?php echo set_value('address', $branch['direccion']); ?>" type="text" name="address" />
				<?php echo form_error('address'); ?>
			</p>


			<p>
				<input class="button" type="submit" value="Registrar" />
			</p>

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->