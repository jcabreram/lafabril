			<!-- Page Head -->
			<h2>Usuarios</h2></br>
			
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Agregar Usuario</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
						<form action="" method="post">
							
							<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
								<p>
									<label>Nombre de usuario</label>
									<input class="text-input medium-input" type="text" id="username" name="username" />
								</p>
								
								<p>
									<label>Contraseña</label>
									<input class="text-input medium-input" type="text" id="password" name="password" />
								</p>
								
								<p>
									<label>Nombre</label>
									<input class="text-input medium-input" type="text" id="nombre" name="nombre" />
								</p>
								
								<p>
									<label>Departamento</label>              
									<select name="departamento" class="small-input">
										<option value="sistemas">Sistemas</option>
										<option value="contabilidad">Contabilidad</option>
										<option value="produccion">Producción</option>
										<option value="gerencia">Gerencia</option>
									</select> 
								</p>
								
								<p>
									<label>Activo</label>
									<input type="radio" name="activo" checked="yes" /> Sí<br />
									<input type="radio" name="activo" /> No
								</p>
								
								
								<p>
									<input class="button" type="submit" value="Enviar" />
								</p>
								
							</fieldset>
							
							<div class="clear"></div><!-- End .clear -->
							
						</form>
											
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			

