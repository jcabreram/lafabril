<div id="filtrar" style="display: none">

	<h3>Filtrado de Usuarios</h3>

	<form action="<?php echo site_url('usuarios/filtrar'); ?>" method="post">

		<p>
			<label>Departamento</label>
			<select name="department"><?php if (!isset($filters['department'])) { $filters['department'] = ''; } ?>
				<option value="" <?php echo setSelect('', $filters['department']); ?>>Todos</option>
				<option value="ventas" <?php echo setSelect('ventas', $filters['department']); ?>>Ventas</option>
				<option value="cuentasxcobrar" <?php echo setSelect('cuentasxcobrar', $filters['department']); ?>>Cuentas por Cobrar</option>
				<option value="admin" <?php echo setSelect('admin', $filters['department']); ?>>Administración</option>
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

</div> <!-- End #messages -->