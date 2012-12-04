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

<?php if (count($payments) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Folio</th>
		   <th>Sucursal</th>
		   <th>Cliente</th>
		   <th>Fecha</th>
		   <th class="textAlign-right">Importe</th>
		   <th class="textAlign-center">Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($payments as $payment) : ?>
		<?php if ($payment['estatus'] === 'P') { continue; } ?>
		<tr>
			<td><?php echo getFolio($payment['prefijo'], $payment['folio']); ?></td>
			<td><?php echo $payment['nombre_sucursal']; ?></td>
			<td><?php echo $payment['nombre_cliente']; ?></td>
			<td><?php echo convertToHumanDate($payment['fecha']); ?></td>
			<td class="textAlign-right"><?php echo getMoneyFormat($payment['importe']); ?></td>
			<td class="textAlign-center"><?php echo getStatusName($payment['estatus']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>No existen pagos con esas especificaciones.</p>
<?php endif; ?>