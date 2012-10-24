<?php

class Facturas extends CI_Controller
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

		$this->load->model('invoices');
	}

	public function index()
	{
		$this->listar();
	}

	public function listar()
	{

		// Get the array with the clients in the database
		$data['invoicesData'] = $this->invoices->getAll();
		
		$data['title'] = "Facturas";
		$data['user'] = $this->session->userdata('user');

		// Display views
		$this->load->view('header', $data);
		$this->load->view('facturas/listar', $data);
		$this->load->view('footer', $data);
	}
	
	public function detalles($id_factura)
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('salesmen');
		$this->load->model('clients');
		$this->load->model('branches');
		$this->load->model('orders');
		$this->load->model('products');
		$this->load->model('invoices');


		$data['title'] = "Detalles del pedido";
		$data['user'] = $this->session->userdata('user');
		$data['invoice'] = $this->invoices->getInvoice($id_factura);
		$data['products'] = $this->products->getProducts();
		$data['order_details'] = $this->orders->getOrderDetail($id_pedido);
		$data['invoice_id'] = $id_factura;
		
		// Declare the $subtotal as float so it gets it in the foreach
		settype($subtotal, "float");
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['order_details'] as $line) {
			$subtotal+=$line['cantidad']*$line['precio'];
		}
		
		$data['subtotal'] = $subtotal;
		
		// The total is equal to the subtotal plus its tax
		$data['total'] = $subtotal + $subtotal * $data['order']['sucursal_iva']; 
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('facturas/detalles', $data);
		$this->load->view('footer', $data);
	}

}