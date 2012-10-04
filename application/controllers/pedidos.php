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
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('salesmen');
		$this->load->model('clients');

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'fecha_pedido', 
				'label' => 'fecha del pedido', 
				'rules' => 'required, exact_length[10], alpha_dash'
			),
			array(
				'field' => 'fecha_entrega', 
				'label' => 'nombre completo', 
				'rules' => 'required, exact_length[10], alpha_dash'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			$usuario_captura = $usuario['id'];
			if($id_pedido = $this->orders->register($_POST['branch'], $_POST['salesman'], $_POST['client'], $_POST['fecha_pedido'], $_POST['fecha_entrega'], $_POST['status'], $usuario_captura)) {
				redirect("pedidos/registrar_detalles/$id_pedido");
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pedido, intenta de nuevo.');
			}
		}

		$data['title'] = "Registrar Pedido";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['salesmen'] = $this->salesmen->getAll();
		$data['clients'] = $this->clients->getClientes();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pedidos/registrar', $data);
		$this->load->view('footer', $data);
	}

	public function listar()
	{

	}
	
	public function registrar_detalles($id_pedido)
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('salesmen');
		$this->load->model('clients');
		$this->load->model('branches');
		$this->load->model('orders');
		

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'fecha_pedido', 
				'label' => 'fecha del pedido', 
				'rules' => 'required, exact_length[10], alpha_dash'
			),
			array(
				'field' => 'fecha_entrega', 
				'label' => 'nombre completo', 
				'rules' => 'required, exact_length[10], alpha_dash'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			$usuario_captura = $usuario['id'];
			if($this->orders->register($_POST['branch'], $_POST['salesman'], $_POST['client'], $_POST['fecha_pedido'], $_POST['fecha_entrega'], $_POST['status'], $usuario_captura)) {
				$this->session->set_flashdata('message', 'El pedido ha sido registrado.');
				redirect('pedidos');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pedido, intenta de nuevo.');
			}
		}

		$data['title'] = "Registrar detalles del pedido";
		$data['user'] = $this->session->userdata('user');
		$data['order'] = $this->orders->getOrder($id_pedido);
		$data['sucursal'] = $this-<branches->getBranch($data['order']['id_sucursal'])
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pedidos/registrar_detalles', $data);
		$this->load->view('footer', $data);
	}

}