<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<title>La Fabril - <?php echo $title; ?></title>

	<link rel="stylesheet" type="text/css" href="<?php echo site_url("resources/css/reset.css"); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("resources/css/reports.css"); ?>" />
</head>

<body>

<div class="header">
<h1>La Fabril, S.A.</h1>

<h2><?php echo $title; ?></h2>
</div> <!-- end .header -->

<script type="text/php">
if ( isset($pdf) ) {
	$font = Font_Metrics::get_font("helvetica", "normal");
	$pdf->page_text(38, 60, 'Fecha: ' . date('d/m/Y'), $font, 9, array(0,0,0));
	$pdf->page_text(38, 75, 'Hora: ' . date('H:i'), $font, 9, array(0,0,0));
	$pdf->page_text(38, 90, utf8_encode('Página {PAGE_NUM}/{PAGE_COUNT}'), $font, 9, array(0,0,0));
}
</script>

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
		   <th>Departamento</th>
		   <th>Estatus</th>
		</tr>
	</thead>

	<tbody>
	<?php foreach ($users as $user) : ?>
		<tr>
			<td><?php echo $user['nombre']; ?></td>
			<td><?php echo $user['username']; ?></td>
			<td><?php echo $user['departamento']; ?></td>
			<td><?php 
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