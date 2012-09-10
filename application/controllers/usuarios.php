<?php

class Usuarios extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('ingresar');
		}
		// User doesn't want to be remembered?
		elseif (!$this->session->userdata('remember') && (($this->session->userdata('lastActivity') + $this->config->item('maximumIdleTime')) < time())) {
			redirect('salir');
		}

		// We need it to know his idle time
		$this->session->set_userdata('lastActivity', time());

		$this->load->model('users');
	}
	
	public function registrar()
	{
		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'fullName', 
				'label' => 'nombre completo', 
				'rules' => 'trim|required|max_length[255]'
			),
			array(
				'field' => 'username', 
				'label' => 'nombre de usuario', 
				'rules' => 'trim|required|max_length[25]|is_unique[usuarios.username]'
			),
			array(
				'field' => 'password', 
				'label' => 'contraseña', 
				'rules' => 'required|min_length[5]|max_length[20]'
			),
			array(
				'field' => 'repassword', 
				'label' => 'repetir contraseña', 
				'rules' => 'required|matches[password]'
			),
			array(
				'field' => 'department', 
				'label' => 'departamento',
				'rules' => 'required'
			),
			array(
				'field' => 'status', 
				'label' => 'estatus', 
				'rules' => 'required'
			),
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->users->signUp($_POST['username'], $_POST['password'], $_POST['fullName'], $_POST['department'], $_POST['status'])) {
				$this->session->set_flashdata('message', 'El usuario "' . $_POST['username'] . '" ha sido registrado.');
				redirect('usuarios');
			};
		}

		$data['title'] = "Registrar Usuario";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/registrar', $data);
		$this->load->view('footer', $data);
	}

	public function index()
	{
		$data['title'] = "Usuarios";
		$data['user'] = $this->session->userdata('user');
		
		// Get the array with the users in the database
		$data['usersData'] = $this->users->getUsers();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/index', $data);
		$this->load->view('footer', $data);
	}
	
	public function editar($id)
	{
		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'fullName', 
				'label' => 'nombre completo', 
				'rules' => 'trim|required|max_length[255]'
			),
			array(
				'field' => 'username', 
				'label' => 'nombre de usuario', 
				'rules' => 'trim|required|max_length[25]'
			),
			array(
				'field' => 'password', 
				'label' => 'contraseña', 
				'rules' => 'min_length[5]|max_length[20]'
			),
			array(
				'field' => 'repassword', 
				'label' => 'repetir contraseña', 
				'rules' => 'matches[password]'
			),
			array(
				'field' => 'department', 
				'label' => 'departamento',
				'rules' => 'required'
			),
			array(
				'field' => 'status', 
				'label' => 'estatus', 
				'rules' => 'required'
			),
		);

		// If the user tried to change the username
		if (isset($_POST['username']) && ($_POST['username'] != $_POST['originalUsername'])) {
			$config[1]['rules'] .= '|is_unique[usuarios.username]';
		}
		
		// If the user tried to input some password
		if (isset($_POST['password']) && (!empty($_POST['password']) || !empty($_POST['repassword']))) {
			$config[2]['rules'] = 'required|' . $config[2]['rules'];
			$config[3]['rules'] = 'required|' . $config[3]['rules'];
		}

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->users->update($id, $_POST['username'], $_POST['password'], $_POST['fullName'], $_POST['department'], $_POST['status'])) {
				$this->session->set_flashdata('message', "El usuario {$_POST['username']} ha sido modificado.");
				redirect('usuarios');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema actualizando a ' . $_POST['username'] . ', <a href="' . site_url('usuarios/editar/' . $id) . '" title="Intenta de Nuevo">intenta de nuevo</a>.');
			}
		}
		
		// Get the array with the row of the user in the database
		$data['userData'] = $this->users->getUser($id);
		
		// If the user doesn't exist
		if (!is_array($data['userData'])) {
			$this->session->set_flashdata('error', 'Tuvimos un problema accediendo al usuario, <a href="' . site_url('usuarios/editar/' . $id) . '" title="Intenta de Nuevo">intenta de nuevo</a>.');
			redirect('usuarios');
		}

		$data['title'] = "Modificar Usuario";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/editar', $data);
		$this->load->view('footer', $data);
	}

	public function activar($id)
	{
		$this->users->activate($id);
		$this->session->set_flashdata('message', 'El usuario ha sido activado.');
		redirect('usuarios');
	}
	
	public function desactivar($id)
	{	
		$this->users->deactivate($id);
		$this->session->set_flashdata('message', 'El usuario ha sido desactivado.');
		redirect('usuarios');
	}
}