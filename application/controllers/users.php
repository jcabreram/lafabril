<?php

class Login extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('html');
	}
	
	public function index()
	{
		// Page title
		$data['title'] = 'Identificación';

		// If form was submitted
		if ($_POST) {
			// Load users model
			$this->load->model('users_model');

			// If login was correct
			if ($this->users_model->login($_POST['username'], $_POST['password'])) {
				// Load url helper
				$this->load->helper('url');
				redirect('home');
			}
		}

		// Display views
		$this->load->view('login/index.php', $data);
	}
	
	
}