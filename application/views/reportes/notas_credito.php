<table class="filters">
	<tr>
		<th>Sucursal</th>
		<td><?php echo $branch; ?></td>
	</tr>

	<tr>
		<th>Cliente</th>
		<td><?php echo $client; ?></td>
	</tr>

	<tr>
		<th>Estatus</th>
		<td><?php echo $status; ?></td>
	</tr>
</table>

<?php if (count($creditNotes) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Sucursal</th>
		   <th>Cliente</th>
		   <th>Fecha</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($creditNotes as $creditNote) : ?>
		<?php if ($creditNote['estatus'] === 'P') { continue; } ?>
		<tr>
			<td><?php echo getFolio($creditNote['prefijo'], $creditNote['folio']); ?></td>
			<td><?php echo $creditNote['nombre_sucursal']; ?></td>
			<td><?php echo $creditNote['nombre_cliente']; ?></td>
			<td><?php echo convertToHumanDate($creditNote['fecha']); ?></td>
			<td class="textAlign-center"><?php echo getStatusName($creditNote['estatus']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>No existen notas de crédito con esas especificaciones.</p>
<?php endif; ?>