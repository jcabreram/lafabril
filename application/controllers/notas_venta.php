<?php

class Notas_venta extends CI_Controller
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

		$this->load->model('bills');
	}

	public function index()
	{
		$this->listar();
	}


	public function listar()
	{
		// We need it to populate the filter form
		$this->load->helper('form');

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// To populate the filter form
		$this->load->model('branches');
		$this->load->model('clients');
		
		// Get the array with the bills in the database
		$data['billsData'] = $this->bills->getAll($filters);

		$data['title'] = "Notas de venta";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->branches->getAll(array('status' => '1')); // Active branches
		$data['clients'] = $this->clients->getAll(array('status' => '1')); // Active clients
		$data['filters'] = $filters;
		

		// Display views
		$this->load->view('header', $data);
		$this->load->view('notas_venta/listar', $data);
		$this->load->view('facturas/filterForm', $data);
		$this->load->view('footer', $data);
	}
	
	/*

	public function detalles($id_factura)
	{
		// Load necessary models
		$this->load->model('invoices');
		$this->load->model('orders');


		$data['title'] = "Detalles de la factura";
		$data['user'] = $this->session->userdata('user');
		$data['invoice'] = $this->invoices->getInvoice($id_factura);
		$data['invoice_details'] = $this->invoices->getInvoiceDetail($id_factura);
		$data['order'] = $this->orders->getOrder($data['invoice']['id_pedido']);
		
		// Declare the $subtotal as float so it gets it in the foreach
		settype($subtotal, "float");
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['invoice_details'] as $line) {
			$subtotal+=$line['cantidad']*$line['precio_producto'];
		}
		
		$data['subtotal'] = $subtotal;
		
		// The total is equal to the subtotal plus its tax
		$data['total'] = $subtotal + $subtotal * $data['invoice']['iva']; 
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('facturas/detalles', $data);
		$this->load->view('footer', $data);
	}
	
	
	public function cancelar($id_factura)
	{	
		if($this->invoices->cancelar($id_factura)) {
				$this->session->set_flashdata('message', 'La factura ha sido cancelada.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar cancelar la factura, intenta de nuevo.');
			}
		redirect("facturas");
		
	}
	
	*/
	
}