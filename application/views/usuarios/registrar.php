			<!-- Page Head -->
			<h2>Usuarios</h2></br>
			
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Agregar Usuario</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
						<form action="<?php echo site_url('usuarios/registrar'); ?>" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<p>
									<label>Nombre Completo *</label>
									<input class="text-input medium-input" value="<?php echo set_value('fullName'); ?>" type="text" id="fullName" name="fullName" />
									<?php echo form_error('fullName'); ?>
								</p>

								<p>
									<label>Nombre de Usuario *</label>
									<input class="text-input medium-input" value="<?php echo set_value('username'); ?>" type="text" id="username" name="username" />
									<?php echo form_error('username'); ?>
								</p>
								
								<p>
									<label>Contraseña *</label>
									<input class="text-input medium-input" type="password" id="password" name="password" />
									<?php echo form_error('password'); ?>
								</p>

								<p>
									<label>Repetir Contraseña *</label>
									<input class="text-input medium-input" type="password" id="repassword" name="repassword" />
									<?php echo form_error('repassword'); ?>
								</p>
								
								<p>
									<label>Departamento *</label>              
									<select name="department" class="small-input">
										<option value="">Escoge una opción</option>
										<option value="ventas" <?php echo set_select('department', 'ventas'); ?>>Ventas</option>
										<option value="cuentasxcobrar" <?php echo set_select('department', 'cuentasxcobrar'); ?>>Cuentas por Cobrar</option>
										<option value="admin" <?php echo set_select('department', 'admin'); ?>>Administración</option>
									</select> 
									<?php echo form_error('department'); ?>
								</p>
								
								<p>
									<label>Estatus *</label>
									<input type="radio" name="status" value="1" <?php echo set_radio('status', '1', true); ?> /> Activo<br />
									<input type="radio" name="status" value="0" <?php echo set_radio('status', '0'); ?> /> Inactivo
								</p>
								
								
								<p>
									<input class="button" type="submit" value="Registrar" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
											
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

