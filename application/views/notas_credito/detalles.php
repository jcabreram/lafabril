<!-- Page Head -->
<h2><?php echo $title; ?></h2></br>

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Datos Generales</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<?php if ($this->session->flashdata('message')) : ?>
			<!-- Notification -->
			<div class="notification success png_bg">
				<!-- Close link -->
				<a href="#" class="close"><img src="<?php echo site_url('resources/images/icons/cross_grey_small.png'); ?>" title="Cerrar Notificación" alt="cerrar" /></a>
				<!-- Message -->
				<div><?php echo $this->session->flashdata('message'); ?></div>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('error')) : ?>
		<!-- Notification -->
		<div class="notification error png_bg">
			<!-- Message -->
			<div><?php echo $this->session->flashdata('error'); ?></div>
		</div>
		<?php endif; ?>
		
		<fieldset class="column-left">

			<p><b>Sucursal</b>: <?php echo $creditNote['nombre_sucursal'] ?></p>
			<p><b>Cliente</b>: <?php echo $creditNote['nombre_cliente'] ?></p>   
			<p><b>Observaciones</b>:  <?php echo $creditNote['observaciones']; ?></p>     

		</fieldset>
		
		<fieldset class="column-right">

			<p><b>Fecha</b>: <?php echo convertToHumanDate($creditNote['fecha']); ?></p>  
			<p><b>Tipo</b>: <?php echo $creditNoteType; ?></p>
			<p><b>Estatus</b>: <?php echo getStatusName($creditNote['estatus']); ?></p>    

		</fieldset>
		
		<div class="clear"></div><!-- End .clear --> 
					
	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->

<!-- Content Box -->
<div class="content-box"> 

	<!-- Content Box Header -->
	<div class="content-box-header">
		<h3>Detalles</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">

		<!-- Users Table -->
		<table>

			<thead>
				<tr>
				   <th>Factura</th>
				   <th>Fecha Factura</th>
				   <th style="text-align:right">Importe</th>
				   <th style="text-align:right">Saldo</th>
				   <th style="text-align:right">Nota Crédito</th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($creditNoteDetails as $detail) : ?>
				<tr>
					<td><?php echo getFolio($detail['prefijo'], $detail['folio']); ?></td>
					<td><?php echo convertToHumanDate($detail['fecha']); ?></td>
					<td style="text-align:right">$<?php echo getMoneyFormat($detail['importe_factura']); ?></td>
					<td style="text-align:right">$<?php echo getMoneyFormat($detail['saldo_factura']); ?></td>
					<td style="text-align:right">$<?php echo getMoneyFormat($detail['importe_nota_credito']); ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>

			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td class="textAlign-right"><strong>Total</strong></td>
					<td class="textAlign-right">$<?php echo getMoneyFormat($total); ?></td>
				</tr>
			</tfoot>

		</table>			

	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->

<div class="content-box"><!-- Start Content Box -->
				
	<div class="content-box-header">
		
		<h3>Opciones</h3>
		
	</div> <!-- End .content-box-header -->

	<div class="content-box-content">
		<p>
			<a style="margin-right: 50px; display: inline-block;" href="<?php echo site_url('notas_credito/imprimir/' . $creditNote['id']); ?>" target="_blank"><input class="button" type="button" value="Imprimir" /></a>
			<a href="<?php echo site_url('notas_credito/cancelar/' . $creditNote['id']); ?>"><input class="button" type="button" value="Cancelar" /></a>   
		</p>
		
	</div> <!-- End .content-box-content -->

</div> <!-- End .content-box -->




