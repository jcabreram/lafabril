<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title>La Fabril - <?php echo $title; ?></title>
		
		<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="/resources/css/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="/resources/css/style.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="/resources/css/invalid.css" type="text/css" media="screen" />	
		
		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
		<link rel="stylesheet" href="/resources/css/blue.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="/resources/css/red.css" type="text/css" media="screen" />  
	 
		-->
		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="/resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
	  
		<!-- jQuery -->
		<script type="text/javascript" src="/resources/scripts/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="/resources/scripts/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="/resources/scripts/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="/resources/scripts/jquery.wysiwyg.js"></script>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="/resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
	</head>
  
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<h1>La Fabril</h1>
				<!-- Logo (221px width) -->
				<img id="logo" src="<?php echo base_url();?>resources/images/logo.png" alt="Logo de La Fabril" />
			</div> <!-- End #login-top -->
			
			<div id="login-content">
				
				<form action="<?php echo site_url('ingresar'); ?>" method="post">
				
					<?php if ($_POST) : ?>
						<?php if ($error == 'nonexistent') : ?>
							<div class="notification error png_bg">
								<div>
									Usuario y/o contraseña incorrectos.	
								</div>
							</div>
						<?php elseif ($error == 'inactive') : ?>
							<div class="notification attention png_bg">
								<div>
									Usuario inactivo.	
								</div>
							</div>
						<?php endif; ?>
					<?php endif; ?>
					
					<p>
						<label>Usuario</label>
						<input name="username" class="text-input" type="text" />
					</p>
					<div class="clear"></div>
					<p>
						<label>Contraseña</label>
						<input name="password" class="text-input" type="password" />
					</p>
					<div class="clear"></div>
					<p id="remember-password">
						<input type="checkbox" name="remember" value="on" />Recordarme
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="Entrar" />
					</p>
					
				</form>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>
