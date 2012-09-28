<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos de la Sucursal</h3>
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

		<form action="<?php echo site_url('sucursales/registrar'); ?>" method="post">

			<p>
				<label>Nombre *</label>
				<input class="text-input medium-input" value="<?php echo set_value('name'); ?>" type="text" name="name" />
				<?php echo form_error('name'); ?>
			</p>

			<p>
				<label>Dirección</label>
				<input class="text-input medium-input" value="<?php echo set_value('address'); ?>" type="text" name="address" />
				<?php echo form_error('address'); ?>
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