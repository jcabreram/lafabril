<div id="ingresar_tarjeta" style="display: none">

	<h3>Información de la Tarjeta</h3>

	<form action="<?php echo site_url('pedidos/crear_nota_venta/' . $order['id_pedido']); ?>" method="post">

		<p>
			<label>Nombre en la Tarjeta *</label>
			<input type="text" name="cardOwnerName" class="text-input medium-input" />
		</p>

		<p>
			<label>Número de Tarjeta *</label>
			<input type="text" name="cardNumber" class="text-input medium-input" />
		</p>

		<p>
			<label>Fecha de Expiración *</label>
			<select name="expirationMonth">
				<option value="">Mes</option>
				<?php for ($i = 1; $i <= 12; $i++) : ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php endfor; ?>
			</select>

			<select name="expirationYear">
				<option value="">Año</option>
				<?php for ($j = 0; $j <= 20; $j++) : ?>
				<option value="<?php echo intval(date('Y')) + $j; ?>"><?php echo intval(date('Y')) + $j; ?></option>
				<?php endfor; ?>
			</select>
		</p>

		<p>
			<input class="button" id="addCard" type="submit" value="Agregar" />
		</p>
	</form>

</div> <!-- End #ingresar_tarjeta -->