<div id="ingresar_tarjeta" style="display: none">

	<h3>Información de la Tarjeta</h3>

	<form action="<?php echo site_url('pedidos/crear_nota_venta/' . $order['id_pedido']); ?>" method="post">

		<p>
			<label>Banco *</label>
			<select name="cardBank">
				<option value="">Escoge una opción</option>
				<option value="banamex">Banamex</option>
				<option value="bbv">BBV</option>
				<option value="hsbc">HSBC</option>
				<option value="santander">Santander</option>
				<option value="scotiabank">Scotiabank</option>
			</select>
		</p>

		<p>
			<label>Número de Tarjeta *</label>
			<input type="text" name="cardNumber" class="text-input medium-input" />
		</p>

		<p>
			<label>Cantidad *</label>
			<input type="text" name="cardPaymentAmount" class="text-input medium-input" />
		</p>

		<p>
			<input class="button addCard" type="button" value="Agregar" />
		</p>
	</form>

</div> <!-- End #ingresar_tarjeta -->