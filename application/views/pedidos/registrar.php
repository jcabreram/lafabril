<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos generales del pedido</h3>
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
				<label>Vendedor *</label>              
				<select name="salesman" class="medium-input">
					<option value="escoge" <?php echo set_select('salesman', 'escoge', true); ?>>Escoge una opción</option>
					<?php foreach ($salesmen as $salesman) : ?>
					<option value="<?php echo $salesman['id_vendedor']; ?>" <?php echo set_select('salesman', $salesman['id_vendedor']); ?>><?php echo $salesman['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('salesman'); ?>
			</p>

			<p>
				<label>Cliente *</label>              
				<select name="client" class="medium-input">
					<option value="escoge" <?php echo set_select('client', 'escoge', true); ?>>Escoge una opción</option>
					<?php foreach ($clients as $client) : ?>
					<option value="<?php echo $client['id_cliente']; ?>" <?php echo set_select('client', $client['id_cliente']); ?>><?php echo $client['nombre']; ?></option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('client'); ?>
			</p>
			
			<p>
				<input class="button" type="submit" value="Registrar" />
			</p>
		
		</fieldset>
		
		<fieldset class="column-right">

			<p>
				<label>Fecha del Pedido (aaaa-mm-dd) *</label>
				<input id="fecha" class="text-input medium-input" type="text" name="fecha_pedido" value="<?php echo set_value('fecha_pedido', date('Y-m-d')); ?>" readonly />
				<?php echo form_error('fecha_pedido'); ?>
			</p>

			<p>
				<label>Fecha de Entrega (aaaa-mm-dd) *</label>
				<input id="fecha2" class="text-input medium-input" type="text" name="fecha_entrega" value="<?php echo set_value('fecha_entrega'); ?>" readonly />
				<?php echo form_error('fecha_entrega'); ?>
			</p>

		</fieldset>
		
		<div class="clear"></div><!-- End .clear -->

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->