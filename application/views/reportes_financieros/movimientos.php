<table class="filters">
	<tr>
		<th>Sucursal</th>
		<td><?php echo $branch; ?></td>
		<th>De cliente</th>
		<td><?php echo $from_client; ?></td>
	</tr>

	<tr>
		<th>Fecha de corte</th>
		<td><?php echo $cutDate; ?></td>
		<th>A cliente</th>
		<td><?php echo $to_client; ?></td>
</table>

<?php if (count($wallet) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th></th>
		   <th>Folio Factura</th>
		   <th>Folio Pago/NC</th>
		   <th class="textAlign-right">Fecha</th>
		   <th class="textAlign-right">Fecha Vencimiento</th>
		   <th class="textAlign-right">Importe Orig Factura</th>
		   <th class="textAlign-right">Importes</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($wallet as $client_id => $client) : ?>
		<tr>
			<td><?php echo $client['name'] ?></td>
		</tr>
		
		<?php foreach ($client[$client_id]['invoices'] as $invoice) : ?>
			<tr>
			   <th></th>
			   <th><?php echo getFolio(invoice['prefijo'], $invoice['folio']); ?></th>
			   <th></th>
			   <th class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha']); ?></th>
			   <th class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha_vencimiento']); ?></th>
			   <th class="textAlign-right">$<?php echo getMoneyFormat($invoice['importe']); ?></th>
			   <th class="textAlign-right"></th>
			</tr>
			
			<?php foreach ($invoice)
		<?php endforeach; ?>
	<?php endforeach; ?>
	</tbody>
	
	<tfoot>
		<?php foreach ($payments as $payment) : ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th class="textAlign-center"><?php echo $payment['nombre']; ?></th>
			<td class="textAlign-right">$<?php echo getMoneyFormat($payment['total']); ?></td>
		</tr>
		<?php endforeach; ?>
		
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<th class="textAlign-center">Total</th>
			<td class="textAlign-right">$<?php echo getMoneyFormat($total); ?></td>
		</tr>
	</tfoot>
</table>

<?php endif; ?>