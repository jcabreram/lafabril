<?php

class Ingresar extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Is user logged in?
		if ($this->session->userdata('user')) {
			redirect();
		}

		$this->load->model('users');
	}

	public function index()
	{
		// Page title
		$data['title'] = 'Identificación';

		// If form was submitted
		if ($_POST) {
			$user = $this->users->login($_POST['username'], $_POST['password']);

			// If login was correct
			if (is_array($user)) {
				// If user is active
				if ($user['activo'] == 1) {
					// Prepare data for saving
					$sessionData['user'] = array(
						'id' => $user['id'],
						'nombre' => $user['nombre'],
						'departamento' => $user['departamento']
		 			);

					// If the user wants to be remembered
		 			if (isset($_POST['remember']) && ($_POST['remember'] == 'on')) {
		 				$sessionData['remember'] = true;
		 			}

		 			// We need it to know his idle time
		 			$sessionData['lastActivity'] = time();

					// Save session data
					$this->session->set_userdata($sessionData);

					redirect();
				} else {
					$data['error'] = 'inactive';
				}
			} else {
				$data['error'] = 'nonexistent';
			}
		}

		// Display views
		$this->load->view('ingresar/index', $data);
	}
}