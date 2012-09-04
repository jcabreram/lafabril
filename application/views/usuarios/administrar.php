			<!-- Page Head -->
			<h2>Usuarios</h2></br>
			
			<div class="clear"></div> <!-- End .clear -->

<div class="content-box"><!-- Start Content Box -->
				
				<div class="content-box-header">
					
					<h3>Administrar</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
			   
					<div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
					
						<div class="notification attention png_bg">
							<a href="#" class="close"><img src="<?php echo base_url();?>resources/images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
							<div>
								Aviso.
							</div>
						</div>
						
						<table>
							
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <th>Código</th>
								   <th>Nombre</th>
								   <th>Departamento</th>
								   <th>Activo</th>
								   <th>Herramientas</th>
								</tr>
								
							</thead>
						 
							<tfoot>
								<tr>
									<td colspan="6">
										<div class="bulk-actions align-left">
											<select name="dropdown">
												<option value="option1">Escoge una acción...</option>
												<option value="option2">Editar</option>
												<option value="option3">Eliminar</option>
											</select>
											<a class="button" href="#">Aplicar a los seleccionados</a>
										</div>
										
										<div class="pagination">
											<a href="#" title="First Page">&laquo; Primera</a><a href="#" title="Previous Page">&laquo; Anterior</a>
											<a href="#" class="number" title="1">1</a>
											<a href="#" class="number" title="2">2</a>
											<a href="#" class="number current" title="3">3</a>
											<a href="#" class="number" title="4">4</a>
											<a href="#" title="Next Page">Siguiente &raquo;</a><a href="#" title="Last Page">Última &raquo;</a>
										</div> <!-- End .pagination -->
										<div class="clear"></div>
									</td>
								</tr>
							</tfoot>
						 
							<tbody>
							
								<?php foreach ($users as $users_item): ?>
									<tr>
										<td><input type="checkbox" /></td>
										<td><?php echo $users_item['username'] ?></td>
										<td><?php echo $users_item['nombre'] ?></td>
										<td><?php echo $users_item['departamento'] ?></td>
										<td><?php if ($users_item['activo'] == 1) {
											echo '<img src="'.base_url().'resources/images/icons/tick_circle.png" alt="Edit" />';
											} else {
												echo '<img src="'.base_url().'resources/images/icons/cross_circle.png" alt="Edit" />';
											}	
								?></td>
										<td>
											<!-- Icons -->
											 <a href="#" title="Edit"><img src="<?php echo base_url();?>resources/images/icons/pencil.png" alt="Edit" /></a>
											 <a href="#" title="Delete"><img src="<?php echo base_url();?>resources/images/icons/cross.png" alt="Delete" /></a> 
										</td>
									</tr>
								<?php endforeach ?>
							</tbody>
							
						</table>
						
					</div> <!-- End #tab1 -->      
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->