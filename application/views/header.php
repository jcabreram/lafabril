<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>La Fabril - <?php echo $title; ?></title>
		
		<link rel="icon" href="<?php echo base_url(); ?>resources/images/favicon.png" type="image/png">
		
		<!-- CSS -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/style.css" type="text/css" media="screen" />

		<!-- Facebox Stylesheet -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/facebox.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/invalid.css" type="text/css" media="screen" />	
		
		<link rel="stylesheet" href="<?php echo base_url();?>resources/css/jquery-ui-1.9.0.custom.css" type="text/css" media="screen" />	

		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
		<link rel="stylesheet" href="resources/css/blue.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />  
	 
		-->
		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!-- Javascripts -->
  
		<!-- jQuery -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/jquery-1.8.1.min.js"></script>

		<!-- jQuery UI -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/jquery-ui-1.9.0.custom.js"></script>	
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/jquery.wysiwyg.js"></script>

		<!-- Custom Functions -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/functions.js"></script>

		<!-- Custom Scripts -->
		<script type="text/javascript" src="<?php echo base_url();?>resources/scripts/functionality.js"></script>
		
		
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
				<a href="<?php echo base_url(); ?>usuarios/editar/<?php echo $user['id']; ?>" title="Edita tu perfil"><?php echo $user['nombre']; ?></a>
				| <a href="<?php echo site_url('salir'); ?>" title="Cerrar Sesión">Salir</a>.
				<br />
				<br />
				Fecha: <?php echo date('d/m/Y'); ?>, Hora: <?php echo date('H:i'); ?>.
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu -->
				
				<li>
					<a href="<?php echo site_url(); ?>" class="nav-top-item no-submenu<?php if (controllerName() == 'inicio') { echo ' current'; } ?>"> <!-- Add the class "no-submenu" to menu items with no sub menu -->
						Inicio
					</a>       
				</li>

				<li>
					<a href="#" class="nav-top-item<?php if (controllerName() == 'pedidos' || controllerName() == 'facturas' || controllerName() == 'notas_venta') { echo ' current'; } ?>">
						Ventas
					</a>
					<ul>
						<li><a href="<?php echo site_url('pedidos'); ?>"<?php if ((methodName() == 'pedidos/index') || (methodName() == 'pedidos/listar')) { echo ' class="current"'; } ?>>Administrar Pedidos</a></li>
						<li><a href="<?php echo site_url('pedidos/registrar'); ?>"<?php if ((methodName() == 'pedidos/registrar') || (methodName() == 'pedidos/registrar_detalles')) { echo ' class="current"'; } ?>>Agregar Pedido</a></li>
						<li><a href="<?php echo site_url('notas_venta'); ?>"<?php if ((methodName() == 'notas_venta/index') || (methodName() == 'notas_venta/listar')) { echo ' class="current"'; } ?>>Administrar Notas de Venta</a></li>
						<li><a href="<?php echo site_url('facturas'); ?>"<?php if ((methodName() == 'facturas/index') || (methodName() == 'facturas/listar')) { echo ' class="current"'; } ?>>Administrar Facturas</a></li>
						<li><a href="<?php echo site_url('facturas/crear_reporte'); ?>"<?php if (methodName() == 'facturas/crear_reporte') { echo ' class="current"'; } ?>>Reporte de Facturas</a></li>
						<li><a href="<?php echo site_url('notas_venta/crear_reporte'); ?>"<?php if (methodName() == 'notas_venta/crear_reporte') { echo ' class="current"'; } ?>>Reporte de Notas de Venta</a></li>
					</ul>
				</li>
				
				<li> 
					<a href="#" class="nav-top-item<?php if (controllerName() == 'clientes' || controllerName() == 'notas_credito' || controllerName() == 'pagos' || controllerName() == 'movimientos') { echo ' current'; } ?>"> <!-- Add the class "current" to current menu item -->
                        Cuentas por cobrar
					</a>
					<ul>
						<li><a href="<?php echo site_url('pagos'); ?>"<?php if (methodName() == 'pagos/index') { echo ' class="current"'; } ?>>Administrar Pagos</a></li>
						<li><a href="<?php echo site_url('pagos/agregar_pago'); ?>"<?php if ((methodName() == 'pagos/agregar_pago') || (methodName() == 'pagos/agregar_pago_detalles')) { echo ' class="current"'; } ?>>Agregar Pago</a></li>
						<li><a href="<?php echo site_url('clientes'); ?>"<?php if (methodName() == 'clientes/index' || methodName() == 'clientes/listar') { echo ' class="current"'; } ?>>Administrar Clientes</a></li>
						<li><a href="<?php echo site_url('clientes/registrar'); ?>"<?php if (methodName() == 'clientes/registrar') { echo ' class="current"'; } ?>>Agregar Cliente</a></li>
						<li><a href="<?php echo site_url('notas_credito'); ?>"<?php if (methodName() == 'notas_credito/index' || methodName() == 'notas_credito/listar') { echo ' class="current"'; } ?>>Administrar Notas de Crédito</a></li>
						<li><a href="<?php echo site_url('notas_credito/registrar'); ?>"<?php if (methodName() == 'notas_credito/registrar') { echo ' class="current"'; } ?>>Agregar Nota de Crédito</a></li>
						<li><a href="<?php echo site_url('movimientos/crear_reporte_cartera'); ?>"<?php if (methodName() == 'movimientos/crear_reporte_cartera') { echo ' class="current"'; } ?>>Reporte de Cartera</a></li>
						<li><a href="<?php echo site_url('movimientos/crear_reporte_antiguedad'); ?>"<?php if (methodName() == 'movimientos/crear_reporte_antiguedad') { echo ' class="current"'; } ?>>Reporte de Antigüedad de Saldos</a></li>
					</ul>
					
				</li>
				
				<li>
					<a href="#" class="nav-top-item<?php if ((controllerName() == 'usuarios') || (controllerName() == 'sucursales')) { echo ' current'; } ?>">
						Configuración
					</a>
					<ul>
						<li><a href="<?php echo site_url('usuarios'); ?>"<?php if ((methodName() == 'usuarios/index') || (methodName() == 'usuarios/listar')) { echo ' class="current"'; } ?>>Administrar Usuarios</a></li>
						<li><a href="<?php echo site_url('usuarios/registrar'); ?>"<?php if (methodName() == 'usuarios/registrar') { echo ' class="current"'; } ?>>Registrar Usuario</a></li>
						<li><a href="<?php echo site_url('sucursales'); ?>"<?php if (methodName() == 'sucursales/index') { echo ' class="current"'; } ?>>Administrar Sucursales</a></li>
						<li><a href="<?php echo site_url('sucursales/registrar'); ?>"<?php if (methodName() == 'sucursales/registrar') { echo ' class="current"'; } ?>>Registrar Sucursal</a></li>
					</ul>
				</li>      
				
			</ul> <!-- End #main-nav -->
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<noscript> <!-- Show a notification if the user has disabled javascript -->
				<div class="notification error png_bg">
					<div>
						Javascript está deshabilitado o tu navegador no lo incluye. Por favor <a href="http://browsehappy.com/" title="Actualiza a un mejor navegador">actualiza</a> tu navegador o <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Habilita Javascript en tu navegador">habilita</a> Javascript para navegar la interfaz propiamente.
					</div>
				</div>
			</noscript>