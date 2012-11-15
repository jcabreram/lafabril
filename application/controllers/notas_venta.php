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
		
		// Get the array with the bills in the database
		$data['billsData'] = $this->bills->getAll($filters);

		$data['title'] = "Notas de Venta";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->branches->getAll(array('status' => '1')); // Active branches
		$data['clients'] = $this->clients->getAll(array('status' => '1')); // Active clients
		$data['filters'] = $filters;
		

		// Display views
		$this->load->view('header', $data);
		$this->load->view('notas_venta/listar', $data);
		$this->load->view('notas_venta/filterForm', $data);
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
				redirect('notas_venta/listar/' . $this->uri->assoc_to_uri($filters));
			} else {
				redirect('notas_venta');
			}
		}

		// WTH is the user doing here?
		redirect();
	}

	public function detalles($id_nota_venta)
	{
		// Load necessary models
		$this->load->model('bills');
		$this->load->model('orders');


		$data['title'] = "Detalles de la nota de venta";
		$data['user'] = $this->session->userdata('user');
		$data['bill'] = $this->bills->getBill($id_nota_venta);
		$data['bill_details'] = $this->bills->getBillDetail($id_nota_venta);
		$data['bill_payment'] = $this->bills->getBillPayment($id_nota_venta);
		$data['order'] = $this->orders->getOrder($data['bill']['id_pedido']);
		
		// Declare the $subtotal as float so it gets it in the foreach
		settype($subtotal, "float");
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['bill_details'] as $line) {
			$subtotal+=$line['cantidad']*$line['precio_producto'];
		}
		
		$data['subtotal'] = $subtotal;
		
		// The total is equal to the subtotal plus its tax
		$data['total'] = $subtotal + $subtotal * $data['bill']['iva']; 
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('notas_venta/detalles', $data);
		$this->load->view('footer', $data);
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
		
		$bills = $this->bills->getAll($filters);

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

		$data['title'] = "Reporte de Notas de Venta";
		$data['bills'] = $bills;
		$data['branch'] = $branch;
		$data['client'] = $client;
		$data['status'] = $status;

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes/notas_venta', $data, true);
		$html .= $this->load->view('reportes/footer', $data, true);
		createPDF($html, 'reporte');
	}
	
	public function cancelar($id_nota_venta)
	{	
		if($this->bills->cancelar($id_nota_venta)) {
				$this->session->set_flashdata('message', 'La nota de venta ha sido cancelada.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar cancelar la nota de venta, intenta de nuevo.');
			}
		redirect("notas_venta");
		
	}

	public function imprimir($id)
	{
		$bill = $this->bills->getBill($id);

		if (count($bill) === 0) {
			// We kill the script because usually the PDF is opened in a different tab.
			exit('Nota de venta no encontrada.');
		}

		// Necessary to create a PDF
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('branches'); // This is mandatory to create the PDF header
		$this->load->model('clients'); // This model is necessary because the format has the client address
		$this->load->model('orders'); // To get the order folio

		$bill['products'] = $this->bills->getBillProducts($id);
		$branch = $this->branches->getBranch($bill['id_sucursal']);
		$clientAddress = $this->clients->getClientAddress($bill['id_cliente']);
		$orderFolio = $this->orders->getOrder($bill['id_pedido']);
		$orderFolio = $orderFolio['prefijo'] . str_pad($orderFolio['folio'], 9, '0', STR_PAD_LEFT);

		$subtotal = 0;

		foreach ($bill['products'] as $product) {
			$subtotal += $product['cantidad'] * $product['precio_producto'];
		}

		$iva = $subtotal * $bill['sucursal_iva'];
		$total = $subtotal + $iva;

		$data['title'] = 'Nota de Venta';
		$data['branch'] = $branch;
		$data['folio'] = $bill['prefijo'] . str_pad($bill['folio'], 9, '0', STR_PAD_LEFT);
		$data['clientAddress'] = $clientAddress;
		$data['orderFolio'] = $orderFolio;
		$data['bill'] = $bill;
		$data['subtotal'] = $subtotal;
		$data['iva'] = $iva;
		$data['total'] = $total;

		$html = $this->load->view('formatos/header', $data, true);
		$html .= $this->load->view('formatos/nota_venta', $data, true);
		$html .= $this->load->view('formatos/footer', $data, true);

		createPDF($html, 'formato');
	}
	
	public function crear_reporte()
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
				'field' => 'fecha_inicio', 
				'label' => 'fecha de inicio', 
				'rules' => 'required|exact_length[10]|alpha_dash'
			),
			array(
				'field' => 'fecha_final', 
				'label' => 'fecha final', 
				'rules' => 'required|exact_length[10]|alpha_dash|callback_end_date_check'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			$filters = array(
				'branch' => $_POST['branch'],
				'client' => $_POST['client'],
				'since' => $_POST['fecha_inicio'],
				'until' => $_POST['fecha_final']
			);
			
			$this->_makeReport($filters);
		}

		$data['title'] = "Crear Reporte de Notas de Venta";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['clients'] = $this->clients->getActiveClients();

		// Display views
		$this->load->view('header', $data);
		$this->load->view('notas_venta/crear_reporte', $data);
		$this->load->view('footer', $data);
	}
	
	private function _makeReport($filters)
	{
		$this->load->helper(array('dompdf', 'file'));
		$this->load->model('branches');
		$this->load->model('clients');
		
		$bills = $this->bills->getReportData($filters['branch'], $filters['client'], $filters['since'], $filters['until']);
		$payments = $this->bills->getPaymentsData($filters['branch'], $filters['client'], $filters['since'], $filters['until']);
		
		if (count($bills) === 0) {
			exit('No existen notas de venta con esas especificaciones.');
		}
		
		// Get branch name
		$branch = $this->branches->getBranch($filters['branch']);
		$branch = $branch['nombre'];

		if ($filters['client'] === '0') {
			$client = 'Todos';
		} else {
			// Get client name
			$client = $this->clients->getBranch($filters['client']);
			$client = $client['nombre'];
		}
		
		$since = convertToHumanDate($filters['since']);
		$until = convertToHumanDate($filters['until']);
		
		$total = 0;
		foreach ($bills as $bill) {
			$total += $bill['importe'];
		}

		$data['title'] = 'Reporte Financiero de Facturas';
		$data['invoices'] = $invoices;
		$data['branch'] = $branch;
		$data['client'] = $client;
		$data['since'] = $since;
		$data['until'] = $until;
		$data['total'] = $total;

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes_financieros/facturas', $data, true);
		$html .= $this->load->view('reportes/footer', $data, true);
		createPDF($html, 'reporte');
	}
}