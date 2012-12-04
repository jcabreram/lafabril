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

<?php $gran_total = 0.0; ?>

<?php foreach ($wallet as $client) : ?>

<?php $total_cliente = 0.0 ?>

<h3><?php echo $client['name'] ?></h3>

<table class="catalog">
	<thead>
		<tr>
		   <th>Factura</th>
		   <th>Pago / Nota de Crédito</th>
		   <th class="textAlign-right">Fecha</th>
		   <th class="textAlign-right">Vencimiento</th>
		   <th class="textAlign-right">Importe Original</th>
		   <th class="textAlign-right">Importe</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($client['invoices'] as $invoice) : ?>
		
			<?php $total = $invoice['importe'];?>
			
				<tr>
				   <td><?php echo getFolio($invoice['prefijo'], $invoice['folio']); ?></td>
				   <td></td>
				   <td class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha']); ?></td>
				   <td class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha_vencimiento']); ?></td>
				   <td class="textAlign-right">$<?php echo getMoneyFormat($invoice['importe']); ?></td>
				   <td class="textAlign-right"></td>
				</tr>
				
				<?php foreach ($invoice['payments'] as $payment) : ?>
					<tr>
					   <td></td>
					   <td><?php echo getFolio($payment['prefijo'], $payment['folio']); ?></td>
					   <td class="textAlign-right"><?php echo convertToHumanDate($payment['fecha']); ?></td>
					   <td class="textAlign-right"></td>
					   <td class="textAlign-right"></td>
					   <td class="textAlign-right">$<?php echo getMoneyFormat($payment['importe']); ?></td>
					</tr>
					<?php $total -= $payment['importe']; ?>
				<?php endforeach; ?>
				
				<?php foreach ($invoice['credit_notes'] as $credit_note) : ?>
					<tr>
					   <td></td>
					   <td><?php echo getFolio($credit_note['prefijo'], $credit_note['folio']); ?></td>
					   <td class="textAlign-right"><?php echo convertToHumanDate($credit_note['fecha']); ?></td>
					   <td class="textAlign-right"></td>
					   <td class="textAlign-right"></td>
					   <td class="textAlign-right">$<?php echo getMoneyFormat($credit_note['importe']); ?></td>
					</tr>
					<?php $total -= $credit_note['importe']; ?>
				<?php endforeach; ?>
				
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				   <td class="textAlign-right" style="border-top: 1px solid black;">$<?php echo getMoneyFormat($total); ?></td>
				</tr>
				
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				
				<?php $total_cliente += $total; ?>
			
			<?php endforeach; ?>
		
			<tr>
			   <td class="textAlign-right" colspan="6"><b>Total <?php echo $client['name'] ?></b>: $<?php echo getMoneyFormat($total_cliente); ?></td>
			</tr>
			
			<?php $gran_total += $total_cliente; ?>
	
	</tbody>
</table>

<?php endforeach; ?>
	
<table class="catalog" style="margin-top:20px">
	<tfoot>
		<tr>
			<td class="textAlign-right"><b>Gran Total</b>: $<?php echo getMoneyFormat($gran_total); ?></td>
		</tr>
	</tfoot>
</table>

<?php endif; ?>