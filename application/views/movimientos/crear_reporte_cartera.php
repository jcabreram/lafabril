<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Parámetros</h3>
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

		<form action="<?php echo site_url('movimientos/crear_reporte_cartera'); ?>" method="post">
		
		<fieldset class="column-left">

			<p>
				<label>Sucursal *</label>              
				<select name="branch" class="medium-input">
					<option value="" <?php echo set_select('branch', '', true); ?>>Escoge una opción</option>
					<?php foreach ($branches as $branch) : ?>
					<option value="<?php echo $branch['id_sucursal']; ?>" <?php echo set_select('branch', $branch['id_sucursal']); ?>><?php echo $branch['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('branch'); ?>
			</p>
			
			<p>
				<label>Fecha de corte: (aaaa-mm-dd) *</label>
				<input id="fecha" class="text-input medium-input" value="<?php echo set_value('fecha_corte', date('Y-m-d')); ?>" type="text" name="fecha_corte" readonly />
				<?php echo form_error('fecha_corte'); ?>
			</p>
			
			<p>
				<input class="button" type="submit" value="Crear reporte" />
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">
		
			<p>
				<label>De cliente *</label>              
				<select name="from_client" class="medium-input">
					<option value="" <?php echo set_select('from_client', '', true); ?>>Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['nombre']; ?>" <?php echo set_select('from_client', $client['nombre']); ?>><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('from_client'); ?>
			</p>
			
			<p>
				<label>A cliente *</label>              
				<select name="to_client" class="medium-input">
					<option value="" <?php echo set_select('to_client', '', true); ?>>Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['nombre']; ?>" <?php echo set_select('to_client', $client['nombre']); ?>><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('to_client'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->