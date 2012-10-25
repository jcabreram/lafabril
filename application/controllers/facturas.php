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

	private function _sanitizeFilters($dirtyFilters)
	{
		$filters = array();

		if (isset($dirtyFilters['sucursal']) && trim($dirtyFilters['sucursal']) !== '') {
			$filters['branch'] = $dirtyFilters['sucursal'];
		}

		if (isset($dirtyFilters['cliente']) && trim($dirtyFilters['cliente']) !== '') {
			$filters['client'] = $dirtyFilters['cliente'];
		}

		if (isset($dirtyFilters['estatus']) && trim($dirtyFilters['estatus']) !== '') {
			switch ($dirtyFilters['estatus']) {
				case 'abierto':
					$filters['status'] = 'A';
					break;

				case 'cerrado':
					$filters['status'] = 'C';
					break;

				case 'cancelado':
					$filters['status'] = 'X';
					break;
			}
		}
		
		return $filters;
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

		// Get the array with the clients in the database
		$data['invoicesData'] = $this->invoices->getAll($filters);

		$data['title'] = "Facturas";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->branches->getAll(array('status' => '1')); // Active branches
		$data['clients'] = $this->clients->getAll(array('status' => '1')); // Active clients
		$data['filters'] = $filters;

		// Display views
		$this->load->view('header', $data);
		$this->load->view('facturas/listar', $data);
		$this->load->view('facturas/filterForm', $data);
		$this->load->view('footer', $data);
	}

	public function filtrar()
	{
		if ($_POST) {
			$filters = array();

			$branch = isset($_POST['branch']) ? trim($_POST['branch']) : false;
			$client = isset($_POST['client']) ? trim($_POST['client']) : false;
			$status = isset($_POST['status']) ? trim($_POST['status']) : false;

			if ($branch !== false && $branch !== '') {
				// Is a numeric value? I mean, is it an id?
				$filters['sucursal'] = $branch;
			}

			if ($client !== false && $client !== '') {
				// Is a numeric value? I mean, is it an id?
				$filters['cliente'] = $client;
			}

			if ($status !== false && $status !== '') {
				switch ($status) {
					case 'A':
						$filters['estatus'] = 'abierto';
						break;

					case 'C':
						$filters['estatus'] = 'cerrado';
						break;

					case 'X':
						$filters['estatus'] = 'cancelado';
						break;
				}
			}

			if (count($filters) > 0) {
				redirect('facturas/listar/' . $this->uri->assoc_to_uri($filters));
			} else {
				redirect('facturas');
			}
		}

		// WTH is the user doing here?
		redirect();
	}

	public function exportar()
	{
		$this->load->helper(array('dompdf', 'file'));

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// To populate the filters data
		$this->load->model('branches');
		$this->load->model('clients');
		
		$invoices = $this->invoices->getAll($filters);

		if (!isset($filters['branch'])) {
			$branch = 'Todos';
		} else {
			$branch = $this->branches->getBranch($filters['branch']);
			$branch = $branch['nombre'];
		}

		if (!isset($filters['client'])) {
			$client = 'Todos';
		} else {
			$client = $this->clients->getBranch($filters['client']);
			$client = $client['nombre'];
		}

		if (!isset($filters['status'])) {
			$status = 'Todos';
		} else {
			switch ($filters['status']) {
				case 'A':
					$status = 'Abierto';
					break;

				case 'C':
					$status = 'Cerrado';
					break;

				case 'X':
					$status = 'Cancelado';
					break;
			}
		}

		$data['title'] = "Reporte de Pedidos";
		$data['invoices'] = $invoices;
		$data['branch'] = $branch;
		$data['client'] = $client;
		$data['status'] = $status;

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes/facturas', $data, true);
		$html .= $this->load->view('reportes/footer', $data, true);
		createPDF($html, 'reporte');
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

	public function imprimir($id)
	{
		$invoice = $this->orders->getInvoice($id);

		if (count($order) === 0) {
			// We kill the script because usually the PDF is opened in a different tab.
			exit('Factura no encontrada.');
		}

		// Necessary to create a PDF
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('branches'); // This is mandatory to create the PDF header
		$this->load->model('clients'); // This model is necessary because the format has the client address

		$order['products'] = $this->orders->getOrderProducts($id);
		$data = $this->branches->getBranch($order['id_sucursal']);
		$clientAddress = $this->clients->getClientAddress($order['id_cliente']);

		$subtotal = 0;

		foreach ($order['products'] as $product) {
			$subtotal += $product['cantidad'] * $product['precio'];
		}

		$iva = $subtotal * $order['sucursal_iva'];
		$total = $subtotal + $iva;

		$data['title'] = 'Pedido';
		$data['branch'] = $branch;
		$data['order'] = $order;
		$data['clientAddress'] = $clientAddress;
		$data['subtotal'] = $subtotal;
		$data['iva'] = $iva;
		$data['total'] = $total;

		$html = $this->load->view('formatos/header', $data, true);
		$html .= $this->load->view('formatos/pedido', $data, true);
		$html .= $this->load->view('formatos/footer', $data, true);

		createPDF($html, 'formato');
	}
}