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
				   <th><?php echo getFolio($invoice['prefijo'], $invoice['folio']); ?></th>
				   <th></th>
				   <th class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha']); ?></th>
				   <th class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha_vencimiento']); ?></th>
				   <th class="textAlign-right">$<?php echo getMoneyFormat($invoice['importe']); ?></th>
				   <th class="textAlign-right"></th>
				</tr>
				
				<?php foreach ($invoice['payments'] as $payment) : ?>
					<tr>
					   <th></th>
					   <th><?php echo getFolio($payment['prefijo'], $payment['folio']); ?></th>
					   <th class="textAlign-right"><?php echo convertToHumanDate($payment['fecha']); ?></th>
					   <th class="textAlign-right"></th>
					   <th class="textAlign-right"></th>
					   <th class="textAlign-right">$<?php echo getMoneyFormat($payment['importe']); ?></th>
					</tr>
					<?php $total -= $payment['importe']; ?>
				<?php endforeach; ?>
				
				<?php foreach ($invoice['credit_notes'] as $credit_note) : ?>
					<tr>
					   <th></th>
					   <th><?php echo getFolio($credit_note['prefijo'], $credit_note['folio']); ?></th>
					   <th class="textAlign-right"><?php echo convertToHumanDate($credit_note['fecha']); ?></th>
					   <th class="textAlign-right"></th>
					   <th class="textAlign-right"></th>
					   <th class="textAlign-right">$<?php echo getMoneyFormat($credit_note['importe']); ?></th>
					</tr>
					<?php $total -= $credit_note['importe']; ?>
				<?php endforeach; ?>
				
				<tr>
				   <th></th>
				   <th></th>
				   <th></th>
				   <th></th>
				   <th></th>
				   <th class="textAlign-right" style="border-top: 1px solid black;">$<?php echo getMoneyFormat($total); ?></th>
				</tr>
				
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
				
				<?php $total_cliente += $total; ?>
			
			<?php endforeach; ?>
		
			<tr>
			   <th class="textAlign-right" colspan="5">Total <?php echo $client['name'] ?>:</th>
			   <th class="textAlign-right">$<?php echo getMoneyFormat($total_cliente); ?></th>
			</tr>
			
			<?php $gran_total += $total_cliente; ?>
	
	</tbody>
</table>

<?php endforeach; ?>
	
<table class="catalog" style="margin-top:20px">
	<tfoot>
		<tr>
			<th class="textAlign-right" style="font-size:110%">Gran Total: $<?php echo getMoneyFormat($gran_total); ?></th>
		</tr>
	</tfoot>
</table>

<?php endif; ?>