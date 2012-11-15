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
		<h3>Ingresar detalle</h3>
	</div>
	
	<!-- Content Box Content -->			
	<div class="content-box-content">
	
		<form action="<?php echo site_url("notas_credito/registrar_detalles/{$creditNote['id']}"); ?>" method="post">
		
			<p>
				<label>Factura *</label>              
				<select name="invoice">
					<option value="" <?php echo set_select('invoice', '', true); ?>>Escoge una opción</option>
					<?php foreach ($invoices as $invoice) : ?>
					<option value="<?php echo $invoice['id_factura']; ?>" <?php echo set_select('invoice', $invoice['id_factura']); ?>>
						<?php echo getFolio($invoice['prefijo'], $invoice['folio'])
						. ' - Importe: $' . getMoneyFormat($invoice['importe'])
						. ' - Saldo: $'. getMoneyFormat($invoice['saldo'])
						. ' - Fecha: ' . convertToHumanDate($invoice['fecha_factura']); ?>
					</option>
					<?php endforeach; ?>
				</select> 
				<?php echo form_error('invoice'); ?>
			</p>
	
			<p>
				<label>Importe *</label>
				$ <input class="text-input medium-input" type="text" name="amount" />
				<?php echo form_error('amount'); ?>
			</p>
		
			<p>
				<input class="button" type="submit" value="Agregar" />
			</p>
	
		<div class="clear"></div><!-- End .clear -->
	
		</form>					
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
				   <th style="text-align:center">Opciones</th>
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
					<td style="text-align:center">
						<!-- Options Icons -->
						<?php echo '<a href="' . site_url("notas_credito/eliminar/{$creditNote['id']}/{$detail['id_nota_credito_detalle']}") . '" title="Eliminar"><img src="' . site_url('resources/images/icons/cross.png') . '" alt="Eliminar" /></a>'; ?>
					</td>
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
					<td></td>
				</tr>
			</tfoot>

		</table>
	
	<p>
		<a href="<?php echo site_url('notas_credito/finalizar/' . $creditNote['id']); ?>"><input class="button" type="button" value="Finalizar" /></a>
	</p>				

	</div>
	<!-- End Content Box Content -->
				
</div>
<!-- End Content Box -->




