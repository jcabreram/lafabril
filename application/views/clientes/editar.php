<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<div class="content-box"> <!-- Start Content Box -->

	<div class="content-box-header">
		<h3>Datos del Cliente</h3>
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>

		<form action="<?php echo site_url('clientes/editar/' . $client['id_cliente']); ?>" method="post">

			<p>
				<label>Nombre *</label>
				<input class="text-input medium-input" value="<?php echo set_value('nombre', $client['nombre']); ?>" type="text" name="nombre" />
				<?php echo form_error('nombre'); ?>
			</p>
			
			<p>
				<label>Razón social *</label>
				<input class="text-input medium-input" value="<?php echo set_value('razon_social', $client['razon_social']); ?>" type="text" name="razon_social" />
				<?php echo form_error('razon_social'); ?>
			</p>
			
			<p>
				<label>Calle *</label>
				<input class="text-input medium-input" value="<?php echo set_value('calle', $client['calle']); ?>" type="text" name="calle" />
				<?php echo form_error('calle'); ?>
			</p>
			
			<p>
				<label>Número exterior *</label>
				<input class="text-input small-input" value="<?php echo set_value('num_ext', $client['numero_exterior']); ?>" type="text" name="num_ext" />
				<?php echo form_error('num_ext'); ?>
			</p>
			
			<p>
				<label>Número interior</label>
				<input class="text-input medium-input" value="<?php echo set_value('num_int', $client['numero_interior']); ?>" type="text" name="num_int" />
				<?php echo form_error('num_int'); ?>
			</p>
			
			<p>
				<label>Colonia</label>
				<input class="text-input medium-input" value="<?php echo set_value('colonia', $client['colonia']); ?>" type="text" name="colonia" />
				<?php echo form_error('colonia'); ?>
			</p>
			
			<p>
				<label>Ciudad *</label>
				<input class="text-input medium-input" value="<?php echo set_value('ciudad', $client['ciudad']); ?>" type="text" name="ciudad" />
				<?php echo form_error('ciudad'); ?>
			</p>
			
			<p>
				<label>Municipio *</label>
				<input class="text-input medium-input" value="<?php echo set_value('municipio', $client['municipio']); ?>" type="text" name="municipio" />
				<?php echo form_error('municipio'); ?>
			</p>
			
			<p>
				<label>Estado *</label>
				<input class="text-input medium-input" value="<?php echo set_value('estado', $client['estado']); ?>" type="text" name="estado" />
				<?php echo form_error('estado'); ?>
			</p>
			
			<p>
				<label>País *</label>
				<input class="text-input medium-input" value="<?php echo set_value('pais', $client['pais']); ?>" type="text" name="pais" />
				<?php echo form_error('pais'); ?>
			</p>
			
			<p>
				<label>Código postal *</label>
				<input class="text-input medium-input" value="<?php echo set_value('cp', $client['codigo_postal']); ?>" type="text" name="cp" />
				<?php echo form_error('cp'); ?>
			</p>
			
			<p>
				<label>RFC *</label>
				<input class="text-input medium-input" value="<?php echo set_value('rfc', $client['rfc']); ?>" type="text" name="rfc" />
				<?php echo form_error('rfc'); ?>
			</p>
			
			<p>
				<label>Tipo de contribuyente *</label>
				<input type="radio" name="tipo" value="M" <?php echo set_radio('tipo', 'M', (!$_POST & ($client['tipo_contribuyente'] == 'M')) ? true : false); ?> /> Persona Moral<br />
				<input type="radio" name="tipo" value="F" <?php echo set_radio('tipo', 'F', (!$_POST & ($client['tipo_contribuyente'] == 'F')) ? true : false); ?> /> Persona Física
			</p>
			
			<p>
				<label>Contacto</label>
				<input class="text-input medium-input" value="<?php echo set_value('contacto', $client['contacto']); ?>" type="text" name="contacto" />
				<?php echo form_error('contacto'); ?>
			</p>
			
			<p>
				<label>Límite de crédito *</label>
				<input class="text-input medium-input" value="<?php echo set_value('lim_credito', $client['limite_credito']); ?>" type="text" name="lim_credito" />
				<?php echo form_error('lim_credito'); ?>
			</p>
			
			<p>
				<label>Días de crédito *</label>
				<input class="text-input medium-input" value="<?php echo set_value('dias_credito', $client['dias_credito']); ?>" type="text" name="dias_credito" />
				<?php echo form_error('dias_credito'); ?>
			</p>


			<p>
				<label>Estatus *</label>
				<input type="radio" name="status" value="1" <?php echo set_radio('status', '1', (!$_POST & ($client['activo'] == 1)) ? true : false); ?> /> Activo<br />
				<input type="radio" name="status" value="0" <?php echo set_radio('status', '0', (!$_POST & ($client['activo'] == 0)) ? true : false); ?> /> Inactivo
			</p>

			<p>
				<input class="button" type="submit" value="Actualizar" />
			</p>

		</form>

	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->