<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos generales del pago</h3>
	</div> <!-- End .content-box-header -->

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
		
		<?php if ($this->session->flashdata('attention')) : ?>
			<!-- Notification -->
			<div class="notification attention png_bg">
				<!-- Close link -->
				<a href="#" class="close"><img src="<?php echo site_url('resources/images/icons/cross_grey_small.png'); ?>" title="Cerrar Notificación" alt="cerrar" /></a>
				<!-- Message -->
				<div><?php echo $this->session->flashdata('attention'); ?></div>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('pagos/agregar_pago'); ?>" method="post">
		
		<fieldset class="column-left">

			<p>
				<label>Sucursal *</label>              
				<select name="branch" class="medium-input">
					<option value="escoge" <?php echo set_select('branch', 'escoge', true); ?>>Escoge una opción</option>
					<?php foreach ($branches as $branch) : ?>
					<option value="<?php echo $branch['id_sucursal']; ?>" <?php echo set_select('branch', $branch['id_sucursal']); ?>><?php echo $branch['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('branch'); ?>
			</p>

			<p>
				<label>Cliente *</label>              
				<select name="client" class="medium-input">
					<option value="escoge" <?php echo set_select('client', 'escoge', true); ?>>Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>" <?php echo set_select('branch', $client['id_cliente']); ?>><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('client'); ?>
			</p>
			
			<p>
				<label>Importe *</label>
				$ <input class="text-input medium-input" type="text" name="importe" value="<?php echo set_value('importe'); ?>" />
				<?php echo form_error('importe'); ?>
			</p>
			
			<p>
				<input class="button" type="submit" value="Registrar" />
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">

			<p>
				<label>Fecha *</label>
				<input id="fecha" class="text-input medium-input" type="text" name="fecha" value="<?php echo set_value('fecha', date('Y-m-d')); ?>" readonly />
				<?php echo form_error('fecha'); ?>
			</p>

			<p>
				<label>Tipo de pago *</label>              
				<select name="tipo_pago" class="medium-input">
					<option value="escoge" <?php echo set_select('tipo_pago', 'escoge', true); ?>>Escoge una opción</option>
					<?php foreach ($payment_types as $type) : ?>
					<option value="<?php echo $type['id_pago_tipo']; ?>" <?php echo set_select('tipo_pago', $type['id_pago_tipo']); ?>><?php echo $type['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('tipo_pago'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->