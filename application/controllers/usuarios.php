<?php

class Usuarios extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('html');
	}

	public function ingresar()
	{
		// Is user logged in?
		if ($this->session->userdata('user')) {
			redirect();
		}

		// Page title
		$data['title'] = 'Identificación';

		// If form was submitted
		if ($_POST) {
			// Load users model
			$this->load->model('users_model');

			// If login was correct
			if ($this->users_model->login($_POST['username'], $_POST['password'])) {
				redirect();
			}
		}

		// Display views
		$this->load->view('usuarios/ingresar', $data);
	}

	public function salir()
	{
		$this->session->sess_destroy();
		redirect('usuarios/ingresar');
	}
	
	public function registrar()
	{
		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('/usuarios/ingresar');
		}

		$data['title'] = "Registrar Usuario";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/registrar', $data);
		$this->load->view('footer', $data);		
	}
}