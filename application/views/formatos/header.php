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
	<p>Sucursal <?php echo $branch['nombre']; ?></p>
	<p><?php echo $branch['direccion']; ?></p>
	<h2><?php echo $title; ?></h2>
</div> <!-- end .header -->

<script type="text/php">
	if (isset($pdf)) {
		$font = Font_Metrics::get_font("helvetica", "normal");
		$pdf->page_text(500, 35, 'Fecha: ' . date('d/m/Y'), $font, 9, array(0,0,0));
		$pdf->page_text(500, 50, 'Hora: ' . date('H:i'), $font, 9, array(0,0,0));
		$pdf->page_text(500, 65, utf8_encode('Página {PAGE_NUM}/{PAGE_COUNT}'), $font, 9, array(0,0,0));
	}
</script>