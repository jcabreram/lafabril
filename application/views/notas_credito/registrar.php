<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos Generales</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('notas_credito/registrar'); ?>" method="post">
		
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
				<label>Cliente *</label>              
				<select name="client" class="medium-input">
					<option value="" <?php echo set_select('client', '', true); ?>>Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>" <?php echo set_select('client', $client['id_cliente']); ?>><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('client'); ?>
			</p>
			
			<p>
				<label>Observaciones</label>
				<textarea name="observations"></textarea>
				<?php echo form_error('observations'); ?>
			</p>
		
			<p>
				<input class="button" type="submit" value="Registrar" />
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">

			<p>
				<label>Fecha *</label>
				<input class="text-input medium-input date" type="text" name="date" value="<?php echo set_value('date', date('d/m/Y')); ?>" />
				<?php echo form_error('date'); ?>
			</p>

			<p>
				<label>Tipo *</label>              
				<select name="type" class="medium-input">
					<option value="" <?php echo set_select('type', '', true); ?>>Escoge una opción</option>
					<option value="B" <?php echo set_select('type', 'B'); ?>>Bonificación</option>
					<option value="D" <?php echo set_select('type', 'D'); ?>>Devolución</option>
					<option value="C" <?php echo set_select('type', 'C'); ?>>Cancelación</option>
				</select> 
				<?php echo form_error('type'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->