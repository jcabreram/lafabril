<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Parámetros</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('movimientos/crear_reporte'); ?>" method="post">
		
		<fieldset class="column-left">

			<p>
				<label>Sucursal *</label>              
				<select name="branch" class="medium-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($branches as $branch) : ?>
					<option value="<?php echo $branch['id_sucursal']; ?>"><?php echo $branch['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('branch'); ?>
			</p>
			
			<p>
				<label>Fecha de corte: (aaaa-mm-dd) *</label>
				<input id="fecha" class="text-input medium-input" type="text" name="fecha_corte" readonly />
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
					<option value="">Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>"><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('from_client'); ?>
			</p>
			
			<p>
				<label>A cliente *</label>              
				<select name="to_client" class="medium-input">
					<option value="">Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>"><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('to_client'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->