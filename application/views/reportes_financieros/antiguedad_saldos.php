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

<?php if (count($clients) > 0) : ?>

<?php foreach ($clients as $client) : ?>

<h3><?php echo $client['nombre_cliente'] ?></h3>

<?php $totals = array(
	'balances' => 0.0,
	'fallDue' => 0.0,
	'1-30' => 0.0,
	'31-60' => 0.0,
	'61-90' => 0.0,
	'91-120' => 0.0,
	'+120' => 0.0
); ?>

<table class="catalog">
	<thead>
		<tr>
		   <th>Factura</th>
		   <th class="textAlign-right">Fecha</th>
		   <th class="textAlign-right">Vencimiento</th>
		   <th class="textAlign-right">Saldo</th>
		   <th class="textAlign-right">Vencido</th>
		   <th class="textAlign-right">1-30</th>
		   <th class="textAlign-right">31-60</th>
		   <th class="textAlign-right">61-90</th>
		   <th class="textAlign-right">91-120</th>
		   <th class="textAlign-right">+120</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ($client['invoices'] as $invoice) : ?>
			
				<tr>
				   <td><?php echo getFolio($invoice['prefijo'], $invoice['folio']); ?></td>
				   <td class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha']); ?></td>
				   <td class="textAlign-right"><?php echo convertToHumanDate($invoice['fecha_vencimiento']); ?></td>
				   <td class="textAlign-right">$<?php echo getMoneyFormat($invoice['saldo']); $totals['balances'] += $invoice['saldo']; ?></td>
				   <td class="textAlign-right"><?php if ($invoice['daysToFallDue'] === 0) { echo '$'.getMoneyFormat($invoice['saldo']); $totals['fallDue'] += $invoice['saldo']; } ?></td>
				   <td class="textAlign-right"><?php if ($invoice['daysToFallDue'] >= 1 && $invoice['daysToFallDue'] <= 30) { echo '$'.getMoneyFormat($invoice['saldo']); $totals['1-30'] += $invoice['saldo']; } ?></td>
				   <td class="textAlign-right"><?php if ($invoice['daysToFallDue'] >= 31 && $invoice['daysToFallDue'] <= 60) { echo '$'.getMoneyFormat($invoice['saldo']); $totals['31-60'] += $invoice['saldo']; } ?></td>
				   <td class="textAlign-right"><?php if ($invoice['daysToFallDue'] >= 61 && $invoice['daysToFallDue'] <= 90) { echo '$'.getMoneyFormat($invoice['saldo']); $totals['61-90'] += $invoice['saldo']; } ?></td>
				   <td class="textAlign-right"><?php if ($invoice['daysToFallDue'] >= 91 && $invoice['daysToFallDue'] <= 120) { echo '$'.getMoneyFormat($invoice['saldo']); $totals['91-120'] += $invoice['saldo']; } ?></td>
				   <td class="textAlign-right"><?php if ($invoice['daysToFallDue'] > 120 ) { echo $invoice['saldo']; $totals['+120'] += $invoice['saldo']; } ?></td>
				</tr>
			
			<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['balances']); ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['fallDue']); ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['1-30']); ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['31-60']); ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['61-90']); ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['91-120']); ?></td>
			<td class="textAlign-right">$<?php echo getMoneyFormat($totals['+120']); ?></td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="textAlign-right"><?php echo roundMoney(($totals['fallDue'] * 100) / $totals['balances']); ?>%</td>
			<td class="textAlign-right"><?php echo roundMoney(($totals['1-30'] * 100) / $totals['balances']); ?>%</td>
			<td class="textAlign-right"><?php echo roundMoney(($totals['31-60'] * 100) / $totals['balances']); ?>%</td>
			<td class="textAlign-right"><?php echo roundMoney(($totals['61-90'] * 100) / $totals['balances']); ?>%</td>
			<td class="textAlign-right"><?php echo roundMoney(($totals['91-120'] * 100) / $totals['balances']); ?>%</td>
			<td class="textAlign-right"><?php echo roundMoney(($totals['+120'] * 100) / $totals['balances']); ?>%</td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tfoot>	
</table>

<?php endforeach; ?>

<?php endif; ?>