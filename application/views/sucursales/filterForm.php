<div id="filtrar" style="display: none">

	<h3>Filtrado de Sucursales</h3>

	<form action="<?php echo site_url('sucursales/filtrar'); ?>" method="post">

		<p>
			<label>Estatus</label><?php if (!isset($filters['status'])) { $filters['status'] = ''; } ?>
			<input type="radio" name="status" value="" <?php echo setRadio('', $filters['status']); ?> /> Todos<br />
			<input type="radio" name="status" value="1" <?php echo setRadio('1', $filters['status']); ?> /> Activo<br />
			<input type="radio" name="status" value="0" <?php echo setRadio('0', $filters['status']); ?> /> Inactivo<br />
		</p>

		<p>
			<input class="button" type="submit" value="Filtrar" />
		</p>
	</form>

</div> <!-- End #messages -->