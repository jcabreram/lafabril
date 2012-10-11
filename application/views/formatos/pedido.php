<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>La Fabril</title>

	<link rel="stylesheet" type="text/css" href="<?php echo site_url("resources/css/reset.css"); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("resources/css/formats.css"); ?>" />
</head>

<body>

<div class="header">
	<h1>La Fabril, S.A.</h1>
	<p>Sucursal <?php echo $order['sucursal_nombre']; ?></p>
	<p><?php echo $order['sucursal_direccion']; ?></p>
</div> <!-- end .header -->

<script type="text/php">
	if (isset($pdf)) {
		$font = Font_Metrics::get_font("helvetica", "normal");
		$pdf->page_text(500, 35, 'Fecha: ' . date('d/m/Y'), $font, 9, array(0,0,0));
		$pdf->page_text(500, 50, 'Hora: ' . date('H:i'), $font, 9, array(0,0,0));
		$pdf->page_text(500, 65, utf8_encode('Página {PAGE_NUM}/{PAGE_COUNT}'), $font, 9, array(0,0,0));
	}
</script>

<h2>Pedido</h2>

<p>Cliente: <?php echo $order['cliente_nombre']; ?></p>
<p>Vendedor: <?php echo $order['vendedor']; ?></p>
<p>Dirección: <?php echo $clientAddress; ?></p>
<p>Fecha de Entrega: <?php echo date('d/m/Y', strtotime($order['fecha_entrega'])); ?></p>

<?php if (count($order['products']) > 0) : ?>
<table class="catalog">
	<thead>
		<tr>
		   <th>Producto</th>
		   <th>Cantidad</th>
		   <th class="textAlign-right">Precio Unitario</th>
		   <th class="textAlign-right">Importe</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($order['products'] as $product) : ?>
		<tr>
			<td><?php echo $product['nombre']; ?></td>
			<td><?php echo $product['cantidad'] . ' ' . $product['udm']; ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['precio']); ?></td>
			<td class="textAlign-right">$<?php echo number_format($product['cantidad'] * $product['precio']); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php else : ?>
<p>El pedido no contiene productos.</p>
<?php endif; ?>

</body>
</html>