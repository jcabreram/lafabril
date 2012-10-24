<table class="filters">
	<tr>
		<th>Estatus</th>
		<td><?php echo $status; ?></td>
	</tr>
</table>

<?php if (count($branches) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Nombre</th>
		   <th>Dirección</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($branches as $branch) : ?>
		<tr>
			<td><?php echo $branch['nombre']; ?></td>
			<td><?php echo $branch['direccion']; ?></td>
			<td class="textAlign-center"><?php 
				if ($branch['estatus'] == 1) {
					echo 'Activo';
				} else {
					echo 'Inactivo';
				}
			?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>No existen sucursales con esas especificaciones.</p>
<?php endif; ?>

</body>
</html>