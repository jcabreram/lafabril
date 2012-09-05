<?php

class Usuarios extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('users');
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
			// If login was correct
			if ($this->users->login($_POST['username'], sha1($_POST['password']))) {
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

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'fullName', 
				'label' => 'Nombre Completo', 
				'rules' => 'trim|required|max_length[255]'
			),
			array(
				'field' => 'username', 
				'label' => 'Nombre de Usuario', 
				'rules' => 'trim|required|max_length[25]|is_unique[usuarios.username]'
			),
			array(
				'field' => 'password', 
				'label' => 'Contraseña', 
				'rules' => 'required|min_length[5]|max_length[20]'
			),
			array(
				'field' => 'repassword', 
				'label' => 'Repetir Contraseña', 
				'rules' => 'required|matches[password]'
			),
			array(
				'field' => 'department', 
				'label' => 'Departamento',
				'rules' => 'required'
			),
			array(
				'field' => 'status', 
				'label' => 'Estatus', 
				'rules' => 'required'
			),
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->users->signUp($_POST['username'], sha1($_POST['password']), $_POST['fullName'], $_POST['department'], intval($_POST['status'])))
			{
				$this->session->set_flashdata('mensaje', 'El usuario "'.$_POST['username'].'" se ha registrado exitosamente.');
				redirect('/usuarios/administrar');
			};
		}

		$data['title'] = "Registrar Usuario";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/registrar', $data);
		$this->load->view('footer', $data);
	}
	
	public function administrar($success = '0')
	{
		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('/usuarios/ingresar');
		}
		
		$data['title'] = "Administrar Usuarios";
		$data['user'] = $this->session->userdata('user');
		
		// Get the array with the users in the database
		$data['users'] = $this->users->get_users();
		$data['success'] = $success;
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/administrar', $data);
		$this->load->view('footer', $data);

	}
	
	public function modificar($id)
	{
		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('/usuarios/ingresar');
		}
		
		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'fullName', 
				'label' => 'Nombre Completo', 
				'rules' => 'trim|required|max_length[255]'
			),
			array(
				'field' => 'username', 
				'label' => 'Nombre de Usuario', 
				'rules' => 'trim|required|max_length[25]'
			),
			array(
				'field' => 'password', 
				'label' => 'Contraseña', 
				'rules' => 'min_length[5]|max_length[20]'
			),
			array(
				'field' => 'repassword', 
				'label' => 'Repetir Contraseña', 
				'rules' => 'matches[password]'
			),
			array(
				'field' => 'department', 
				'label' => 'Departamento',
				'rules' => 'required'
			),
			array(
				'field' => 'status', 
				'label' => 'Estatus', 
				'rules' => 'required'
			),
		);
		
		if ($_POST) {
			if ($_POST['username'] != $_POST['originalUsername']) {
				exit(var_dump($_POST));
				$config[0][1]['rules'] .= '|is_unique[usuarios.username]';
			}
		}

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->users->update($id, $_POST['username'], sha1($_POST['password']), $_POST['fullName'], $_POST['department'], intval($_POST['status'])))
			{
				$this->session->set_flashdata('mensaje', 'El usuario "'.$_POST['username'].'" se ha modificado exitosamente.');
				redirect('/usuarios/administrar/');
			};
		}
		
		$data['title'] = "Modificar Usuario";
		$data['user'] = $this->session->userdata('user');
		
		// Get the array with the row of the user in the database
		$data['users_item'] = $this->users->get_users($id);
		
		if (empty($data['users_item']))
		{
			show_404();
		}
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/modificar', $data);
		$this->load->view('footer', $data);

	}
	
	public function desactivar($id)
	{
		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('/usuarios/ingresar');
		}
		
		$this->users->deactivate($id);
		
		$this->session->set_flashdata('mensaje', 'El usuario se ha desactivado exitosamente.');
		redirect('/usuarios/administrar/');

	}
	
}