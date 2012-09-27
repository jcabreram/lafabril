<?php

class Pedidos extends CI_Controller
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

		$this->load->model('orders');
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
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->orders->register()) {
				$this->session->set_flashdata('message', 'El pedido ha sido registrado.');
				redirect('pedidos');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pedido, intenta de nuevo.');
			}
		}

		$data['title'] = "Registrar Pedido";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pedidos/registrar', $data);
		$this->load->view('footer', $data);
	}

	public function listar()
	{

	}
}