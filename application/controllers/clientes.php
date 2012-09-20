<?php

class Clientes extends CI_Controller
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

		$this->load->model('clients');
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
				'field' => 'nombre', 
				'label' => 'nombre', 
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'razon_social', 
				'label' => 'razón social', 
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'calle',
				'label' => 'calle',
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'num_ext', 
				'label' => 'número exterior', 
				'rules' => 'trim|required|max_length[25]|alpha_numeric'
			),
			array(
				'field' => 'num_int', 
				'label' => 'número interior', 
				'rules' => 'trim|max_length[25]|alpha_numeric'
			),
			array(
				'field' => 'colonia', 
				'label' => 'colonia', 
				'rules' => 'trim|max_length[100]'
			),
			array(
				'field' => 'ciudad', 
				'label' => 'ciudad', 
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'municipio', 
				'label' => 'municipio', 
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'estado', 
				'label' => 'estado', 
				'rules' => 'trim|required|max_length[45]'
			),
			array(
				'field' => 'pais', 
				'label' => 'país', 
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'cp', 
				'label' => 'código postal', 
				'rules' => 'trim|required|max_length[10]|integer'
			),
			array(
				'field' => 'rfc', 
				'label' => 'RFC', 
				'rules' => 'trim|required|min_length[12]|max_length[13]|is_unique[clientes.rfc]'
			),
			array(
				'field' => 'tipo', 
				'label' => 'tipo de contribuyente', 
				'rules' => 'required'
			),
			array(
				'field' => 'contacto', 
				'label' => 'contacto', 
				'rules' => 'trim|max_length[200]'
			),
			array(
				'field' => 'lim_credito', 
				'label' => 'límite de crédito', 
				'rules' => 'trim|required|max_length[13]|numeric'
			),
			array(
				'field' => 'dias_credito', 
				'label' => 'días de crédito', 
				'rules' => 'trim|required|max_length[11]|is_natural'
			),
			array(
				'field' => 'status', 
				'label' => 'estatus', 
				'rules' => 'required'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->clients->create($_POST['nombre'], $_POST['razon_social'], $_POST['calle'], $_POST['num_ext'], $_POST['num_int'], $_POST['colonia'], $_POST['ciudad'], $_POST['municipio'], $_POST['estado'], $_POST['pais'], $_POST['cp'], $_POST['rfc'], $_POST['tipo'], $_POST['contacto'], $_POST['lim_credito'], $_POST['dias_credito'], $_POST['status'])) {
				$this->session->set_flashdata('message', 'El usuario "' . htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8') . '" ha sido registrado.');
				redirect('clientes');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el usuario, intenta de nuevo.');
			}
		}

		$data['title'] = "Registrar Cliente";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('clientes/registrar', $data);
		$this->load->view('footer', $data);
	}

	public function index()
	{
		$data['title'] = "Clientes";
		$data['user'] = $this->session->userdata('user');
		
		// Get the array with the users in the database
		$data['clients'] = $this->clients->getClientes();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('clientes/index', $data);
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
				'field' => 'nombre', 
				'label' => 'nombre', 
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'razon_social', 
				'label' => 'razón social', 
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'calle',
				'label' => 'calle',
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'num_ext', 
				'label' => 'número exterior', 
				'rules' => 'trim|required|max_length[25]|alpha_numeric'
			),
			array(
				'field' => 'num_int', 
				'label' => 'número interior', 
				'rules' => 'trim|max_length[25]|alpha_numeric'
			),
			array(
				'field' => 'colonia', 
				'label' => 'colonia', 
				'rules' => 'trim|max_length[100]'
			),
			array(
				'field' => 'ciudad', 
				'label' => 'ciudad', 
				'rules' => 'trim|required|max_length[100]'
			),
			array(
				'field' => 'municipio', 
				'label' => 'municipio', 
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'estado', 
				'label' => 'estado', 
				'rules' => 'trim|required|max_length[45]'
			),
			array(
				'field' => 'pais', 
				'label' => 'país', 
				'rules' => 'trim|required|max_length[60]'
			),
			array(
				'field' => 'cp', 
				'label' => 'código postal', 
				'rules' => 'trim|required|max_length[10]|integer'
			),
			array(
				'field' => 'rfc', 
				'label' => 'RFC', 
				'rules' => 'trim|required|min_length[12]|max_length[13]'
			),
			array(
				'field' => 'tipo', 
				'label' => 'tipo de contribuyente', 
				'rules' => 'required'
			),
			array(
				'field' => 'contacto', 
				'label' => 'contacto', 
				'rules' => 'trim|max_length[200]'
			),
			array(
				'field' => 'lim_credito', 
				'label' => 'límite de crédito', 
				'rules' => 'trim|required|max_length[13]|numeric'
			),
			array(
				'field' => 'dias_credito', 
				'label' => 'días de crédito', 
				'rules' => 'trim|required|max_length[11]|is_natural'
			),
			array(
				'field' => 'status', 
				'label' => 'estatus', 
				'rules' => 'required'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->clients->update($id, $_POST['nombre'], $_POST['razon_social'], $_POST['calle'], $_POST['num_ext'], $_POST['num_int'], $_POST['colonia'], $_POST['ciudad'], $_POST['municipio'], $_POST['estado'], $_POST['pais'], $_POST['cp'], $_POST['rfc'], $_POST['tipo'], $_POST['contacto'], $_POST['lim_credito'], $_POST['dias_credito'], $_POST['status'])) {
				$this->session->set_flashdata('message', 'El cliente "' . htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8') . '" ha sido modificado.');
				redirect('clientes');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema intentando actualizar al cliente, intenta de nuevo.');
			}
		}
		
		// Get the array with the row of the user in the database
		$data['client'] = $this->clients->getCliente($id);
		
		// If the branch doesn't exist
		if (!is_array($data['client'])) {
			$this->session->set_flashdata('error', 'Tuvimos un problema accediendo al cliente, <a href="' . site_url('clientes/editar/' . $id) . '" title="Intenta de Nuevo">intenta de nuevo</a>.');
			redirect('clientes');
		}

		$data['title'] = "Editar Cliente";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('clientes/editar', $data);
		$this->load->view('footer', $data);
	}

	public function activar($id)
	{
		$this->clients->setStatus($id, 1);
		$this->session->set_flashdata('message', 'El cliente ha sido desactivado.');
		redirect('clientes');
	}

	public function desactivar($id)
	{
		$this->clients->setStatus($id, 0);
		$this->session->set_flashdata('message', 'La cliente ha sido activado.');
		redirect('clientes');		
	}
}