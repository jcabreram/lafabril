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

		<form action="<?php echo site_url('facturas/crearReporte'); ?>" method="post">
		
		<fieldset class="column-left">

			<p>
				<label>Sucursal *</label>              
				<select name="branch" class="medium-input">
					<option value="escoge">Escoge una opción</option>
					<?php foreach ($branches as $branch) : ?>
					<option value="<?php echo $branch['id_sucursal']; ?>"><?php echo $branch['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('branch'); ?>
			</p>

			<p>
				<label>Cliente *</label>              
				<select name="client" class="medium-input">
					<option value="0">Todos</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>"><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('client'); ?>
			</p>
			
			<p>
				<input class="button" type="submit" value="Crear reporte" />
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">

			<p>
				<label>De fecha: (aaaa-mm-dd) *</label>
				<input id="fecha" class="text-input medium-input" type="text" name="fecha_inicio" readonly />
				<?php echo form_error('fecha_inicio'); ?>
			</p>

			<p>
				<label>A fecha: (aaaa-mm-dd) *</label>
				<input id="fecha2" class="text-input medium-input" type="text" name="fecha_final" readonly />
				<?php echo form_error('fecha_final'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->