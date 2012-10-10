<h2><?php echo $title; ?></h2>

<table class="filters">
	<tr>
		<th>Tipo de Persona</th>
		<td><?php echo $typeOfPerson; ?></td>
	</tr>
	<tr>
		<th>Estatus</th>
		<td><?php echo $status; ?></td>
	</tr>
</table>

<?php if (count($clients) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Nombre</th>
		   <th>RFC</th>
		   <th>Tipo</th>
		   <th class="textAlign-right">Límite de Crédito</th>
		   <th class="textAlign-center">Días de Crédito</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($clients as $client) : ?>
		<tr>
			<td><?php echo $client['nombre']; ?></td>
			<td><?php echo $client['rfc']; ?></td>
			<td><?php if ($client['tipo_contribuyente'] == 'F') {
				echo 'Persona Física';
			} elseif ($client['tipo_contribuyente'] == 'M') {
				echo 'Persona Moral';
			} else {
				echo 'Desconocido';
			} ?></td>
			<td class="textAlign-right">$<?php echo number_format($client['limite_credito']); ?></td>
			<td class="textAlign-center"><?php echo $client['dias_credito']; ?></td>
			<td class="textAlign-center"><?php 
				if ($client['activo'] == 1) {
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
<p>No existen clientes con esas especificaciones.</p>
<?php endif; ?>

</body>
</html>