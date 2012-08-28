<?php

class Usuario extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	public function agregar()
	{
		$data['username'] = $this->session->userdata('nombre');
		$data['title'] = "La Fabril - Agregar usuario";
			
		// Display views
		$this->load->view('header.php', $data);
		$this->load->view('usuarios/agregar_usuario.php', $data);
		$this->load->view('footer.php', $data);

		
	}
}