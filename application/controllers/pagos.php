<?php

class Pagos extends CI_Controller
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

		$this->load->model('payments');
	}

	public function index()
	{
		$this->listar();
	}

	public function agregar_pago()
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('clients');

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'branch', 
				'label' => 'sucursal', 
				'rules' => 'callback_not_default'
			),
			array(
				'field' => 'client', 
				'label' => 'cliente', 
				'rules' => 'callback_not_default'
			),
			array(
				'field' => 'importe', 
				'label' => 'importe', 
				'rules' => 'required|alpha_numeric'
			),
			array(
				'field' => 'fecha', 
				'label' => 'fecha', 
				'rules' => 'required|exact_length[10]|alpha_dash'
			),
			array(
				'field' => 'tipo_pago', 
				'label' => 'tipo de pago', 
				'rules' => 'callback_not_default'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			$usuario_captura = $usuario['id'];
			if($id_pago_factura = $this->payments->register($_POST['branch'], $_POST['client'], $_POST['importe'], $_POST['fecha'], $_POST['tipo_pago'], 'P', $usuario_captura)) {
				redirect("pagos/agregar_pago_detalles/$id_pago_factura");
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pago, intenta de nuevo.');
				redirect("pagos/agregar_pago");
			}
		}

		$data['title'] = "Agregar Pago";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['clients'] = $this->clients->getActiveClients();
		$data['payment_types'] = $this->payments->getPaymentTypes();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pagos/agregar_pago', $data);
		$this->load->view('footer', $data);
	}
	
}