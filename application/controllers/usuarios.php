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
		$this->load->model('userBranches');
		$this->load->model('branches');
	}

	public function index()
	{
		$this->listar();
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
			if (!isset($_POST['sucursales'])) {
				$_POST['sucursales'] = array();
			}
			if($this->users->signUp($_POST['username'], $_POST['password'], $_POST['fullName'], $_POST['department'], $_POST['status'], $_POST['sucursales'])) {
				$this->session->set_flashdata('message', 'El usuario "' . htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') . '" ha sido registrado.');
				redirect('usuarios');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar al usuario, intenta de nuevo.');
			}
		}
		
		// Get the array with the rows of all the branches in the database
		$data['branchesData'] = $this->branches->getActiveBranches();

		$data['title'] = "Registrar Usuario";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/registrar', $data);
		$this->load->view('footer', $data);
	}

	public function listar()
	{
		// We need it to populate the filter form
		$this->load->helper('form');

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		$data['title'] = "Usuarios";
		$data['user'] = $this->session->userdata('user');
		$data['filters'] = $filters;
		
		// Get the array with the users in the database
		$data['usersData'] = $this->users->getUsers($filters['department'], $filters['status']);
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/listar', $data);
		$this->load->view('usuarios/filterForm', $data);
		$this->load->view('footer', $data);
	}

	
	public function editar($id)
	{
		// Load form validation library
		$this->load->library('form_validation');
		
		// Load array helper for custom function array_flatten()
		$this->load->helper('array');

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
			if (!isset($_POST['sucursales'])) {
				$_POST['sucursales'] = array();
			}
			if($this->users->update($id, $_POST['username'], $_POST['password'], $_POST['fullName'], $_POST['department'], $_POST['status'], $_POST['sucursales'])) {
				$this->session->set_flashdata('message', 'El usuario "' . htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') . '" ha sido modificado.');
				redirect('usuarios');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema intentando actualizar al usuario, intenta de nuevo.');
				redirect('usuarios');
			}
		}
		
		// Get the array with the row of the user in the database
		$data['userData'] = $this->users->getUser($id);
		// Get the array with the rows of the branches of the user in the database
		$data['userBranchesData'] = $this->userBranches->getUserBranches($id);
		// Get the array with the rows of all the branches in the database
		$data['branchesData'] = $this->branches->getActiveBranches();
		
		// If the user doesn't exist
		if (!is_array($data['userData'])) {
			$this->session->set_flashdata('error', 'Tuvimos un problema accediendo al usuario, <a href="' . site_url('usuarios/editar/' . $id) . '" title="Intenta de Nuevo">intenta de nuevo</a>.');
			redirect('usuarios');
		}

		$data['title'] = "Editar Usuario";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('usuarios/editar', $data);
		$this->load->view('footer', $data);
	}

	public function activar($id)
	{
		$this->users->setStatus($id, '1');
		$this->session->set_flashdata('message', 'El usuario ha sido activado.');
		redirect('usuarios');
	}
	
	public function desactivar($id)
	{	
		$this->users->setStatus($id, '0');
		$this->session->set_flashdata('message', 'El usuario ha sido desactivado.');
		redirect('usuarios');
	}

	public function filtrar()
	{
		if ($_POST) {
			$filters = array();

			$department = isset($_POST['department']) ? trim($_POST['department']) : false;
			$status = isset($_POST['status']) ? trim($_POST['status']) : false;

			if ($department !== false && $department != '') {
				$filters['departamento'] = $department;
			}

			if ($status !== false && $status != '') {
				switch ($status) {
					case '1':
						$filters['estatus'] = 'activo';
						break;

					case '0':
						$filters['estatus'] = 'inactivo';
						break;
				}
			}

			if (count($filters) > 0) {
				redirect('usuarios/listar/' . $this->uri->assoc_to_uri($filters));
			} else {
				redirect('usuarios');
			}
		}

		redirect();
	}

	public function exportar()
	{
		$this->load->helper(array('dompdf', 'file'));

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// Data we need in our PDF
		$data['title'] = "Reporte de Usuarios";
		$data['user'] = $this->session->userdata('user');
		
		// Get the array with the users in the database
		$data['users'] = $this->users->getUsers($filters['department'], $filters['status']);

		if ($filters['department'] == '') {
			$data['department'] = 'todos';
		} else {
			$data['department'] = $filters['department'];
		}

		if ($filters['status'] == '') {
			$data['status'] = 'todos';
		} else {
			switch ($filters['status']) {
				case '1':
					$data['status'] = 'activo';
					break;

				case '0':
					$data['status'] = 'inactivo';
					break;

				default:
					$data['status'] = 'ERROR';
					break;
			}
		}

		$html = $this->load->view('reportes/usuarios', $data, true);
		createPDF($html, 'reporte');
	}

	/**
	 * @return   $filters['department'] as an empty string or a department string.
	 * @return   $filters['status'] as an empty string or a string containing 1, 0.
	 */
	private function _sanitizeFilters($dirtyFilters)
	{
		$filters['department'] = isset($dirtyFilters['departamento']) ? trim($dirtyFilters['departamento']) : '';
		$filters['status'] = isset($dirtyFilters['estatus']) ? trim($dirtyFilters['estatus']) : '';

		if ($filters['status'] != '') {
			switch ($filters['status']) {
				case 'activo':
					$filters['status'] = '1';
					break;

				case 'inactivo':
					$filters['status'] = '0';
					break;
				
				default:
					$filters['status'] = '';
					break;
			}
		}

		return $filters;
	}
}