<div id="filtrar" style="display: none">

	<h3>Filtrado de Clientes</h3>

	<form action="<?php echo site_url('clientes/filtrar'); ?>" method="post">

		<p>
			<label>Tipo de Persona</label><?php if (!isset($filters['typeOfPerson'])) { $filters['typeOfPerson'] = ''; } ?>
			<select name="typeOfPerson">
				<option value="" <?php echo setSelect('', $filters['typeOfPerson']); ?>>Todos</option>
				<option value="F" <?php echo setSelect('F', $filters['typeOfPerson']); ?>>Persona Física</option>
				<option value="M" <?php echo setSelect('M', $filters['typeOfPerson']); ?>>Persona Moral</option>
			</select>
		</p>

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

</div> <!-- End #filtrar -->