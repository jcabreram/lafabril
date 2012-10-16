<div id="filtrar" style="display: none">

	<h3>Filtrado de Pedidos</h3>

	<form action="<?php echo site_url('pedidos/filtrar'); ?>" method="post">

		<p>
			<label>Sucursal</label><?php if (!isset($filters['branch'])) { $filters['branch'] = ''; } ?>
			<select name="branch">
				<option value="" <?php echo setSelect('', $filters['branch']); ?>>Todos</option>
				<?php foreach ($branches as $branch) : ?>
				<option value="<?php echo $branch['id_sucursal']; ?>" <?php echo setSelect($branch['id_sucursal'], $filters['branch']); ?>><?php echo $branch['nombre']; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label>Cliente</label><?php if (!isset($filters['client'])) { $filters['client'] = ''; } ?>
			<select name="client">
				<option value="" <?php echo setSelect('', $filters['client']); ?>>Todos</option>
				<?php foreach ($clients as $client) : ?>
				<option value="<?php echo $client['id_cliente']; ?>" <?php echo setSelect($client['id_cliente'], $filters['client']); ?>><?php echo $client['nombre']; ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<input class="button" type="submit" value="Filtrar" />
		</p>
	</form>

</div> <!-- End #filtrar -->