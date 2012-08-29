<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>La Fabril - <?php echo $title; ?></title>
		
		<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/style.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/invalid.css" type="text/css" media="screen" />	
		
		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
		<link rel="stylesheet" href="resources/css/blue.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />  
	 
		-->
		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
  
		<!-- jQuery -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/jquery.wysiwyg.js"></script>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
	</head>
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><a href="/">La Fabril</a></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="<?php echo site_url(); ?>"><center><img id="logo" src="<?php echo base_url();?>resources/images/logo.png" alt="Logo de La Fabril" /></center></a>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				Bienvenido, <a href="#" title="Edita tu perfil"><?php echo $user['nombre']; ?></a>.<br />
				<br />
				<a href="#" title="Cambiar Contraseña">Cambiar Contraseña</a> | <a href="<?php echo site_url('usuarios/salir'); ?>" title="Cerrar Sesión">Salir</a>
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
				<li>
					<a href="<?php echo site_url(); ?>" class="nav-top-item no-submenu<?php if (controller_name() == 'inicio') { echo ' current'; } ?>"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
						Inicio
					</a>       
				</li>
				
				<li> 
					<a href="#" class="nav-top-item"> <!-- Add the class "current" to current menu item -->
                        Clientes
					</a>
					<ul>
						<li><a href="#">Administrar Clientes</a></li> <!-- Add class "current" to sub menu items also -->
						<li><a href="#messages" rel="modal">Agregar Cliente</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item">
						Ventas
					</a>
					<ul>
						<li><a href="#">Administrar Pedidos</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item<?php if (controller_name() == 'usuarios') { echo ' current'; } ?>">
						Usuarios
					</a>
					<ul>
						<li><a href="<?php echo site_url('usuarios/registrar'); ?>"<?php if (method_name() == 'usuarios/registrar') { echo ' class="current"'; } ?>>Registrar Usuario</a></li>
						<li><a href="#">Administrar Usuarios</a></li>
					</ul>
				</li>
				
				<li>
					<a href="#" class="nav-top-item">
						Configuración
					</a>
					<ul>
						<li><a href="#">General</a></li>
						<li><a href="#">Diseño</a></li>
						<li><a href="#">Tu Perfil</a></li>
						<li><a href="#">Usuarios y permisos</a></li>
					</ul>
				</li>      
				
			</ul> <!-- End #main-nav -->
			
			<div id="messages" style="display: none"> <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
				
				<h3>3 Messages</h3>
			 
				<p>
					<strong>17th May 2009</strong> by Admin<br />
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
			 
				<p>
					<strong>2nd May 2009</strong> by Jane Doe<br />
					Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
			 
				<p>
					<strong>25th April 2009</strong> by Admin<br />
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small>
				</p>
				
				<form action="" method="post">
				
					<h4>New Message</h4>
					
					<fieldset>
						<textarea class="textarea" name="textfield" cols="79" rows="5"></textarea>
					</fieldset>
					
					<fieldset>
					
						<select name="dropdown" class="small-input">
							<option value="option1">Send to...</option>
							<option value="option2">Everyone</option>
							<option value="option3">Admin</option>
							<option value="option4">Jane Doe</option>
						</select>
						
						<input class="button" type="submit" value="Send" />
						
					</fieldset>
					
				</form>
				
			</div> <!-- End #messages -->
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						Javascript está deshabilitado o tu navegador no lo incluye. Por favor <a href="http://browsehappy.com/" title="Actualiza a un mejor navegador">actualiza</a> tu navegador o <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Habilita Javascript en tu navegador">habilita</a> Javascript para navegar la interfaz propiamente.
					</div>
				</div>
			</noscript>