<table class="filters">
	<tr>
		<th>Departamento</th>
		<td><?php echo $department; ?></td>
	</tr>
	<tr>
		<th>Estatus</th>
		<td><?php echo $status; ?></td>
	</tr>
</table>

<?php if (count($users) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Nombre</th>
		   <th>Nombre de Usuario</th>
		   <th class="textAlign-center">Departamento</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($users as $user) : ?>
		<tr>
			<td><?php echo $user['nombre']; ?></td>
			<td><?php echo $user['username']; ?></td>
			<td class="textAlign-center"><?php echo $user['departamento']; ?></td>
			<td class="textAlign-center"><?php 
				if ($user['activo'] == 1) {
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
<p>No existen usuarios con esas especificaciones.</p>
<?php endif; ?>

</body>
</html>