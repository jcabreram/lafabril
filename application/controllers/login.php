<?php

class Login extends CI_Controller
{
	public function index()
	{
		// Page title
		$data['title'] = 'Identificación';

		// We load form validation library
		$this->load->library('form_validation');

		// Set validation rules
		$this->form_validation->set_rules('username', 'usuario', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'contraseña', 'trim|required|sha1');

		// We run form validation
		if ($this->form_validation->run() == true) { // If validation succeeded
			if (false) { // If login succeeded
				$this->load->helper('url');
				redirect();
			}
		}
		
		$this->load->view('login/index.php', $data);
	}
	
	
}