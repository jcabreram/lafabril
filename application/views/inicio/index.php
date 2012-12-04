<!-- Page Head -->
			<h2>Bienvenido <?php echo $user['nombre']; ?></h2>
			<p id="page-intro">¿Qué te gustaría hacer?</p>
			
			<ul class="shortcut-buttons-set">
				
				<li><a class="shortcut-button new-article" href="<?php echo site_url('clientes/registrar'); ?>"><span class="png_bg">
					Agregar cliente
				</span></a></li>
				
				<li><a class="shortcut-button new-page" href="<?php echo site_url('pedidos/registrar'); ?>"><span class="png_bg">
					Crear un pedido
				</span></a></li>
				
				<li><a class="shortcut-button upload-image" href="<?php echo site_url('pedidos'); ?>"><span class="png_bg">
					Ver pedidos
				</span></a></li>
				
				<li><a class="shortcut-button upload-image" href="<?php echo site_url('facturas'); ?>"><span class="png_bg">
					Ver facturas
				</span></a></li>
				
				<li><a class="shortcut-button add-event" href="<?php echo site_url('movimientos/crear_reporte_cartera'); ?>"><span class="png_bg">
					Reporte de cartera
				</span></a></li>
				
			</ul><!-- End .shortcut-buttons-set -->
			
			<div class="clear"></div> <!-- End .clear -->