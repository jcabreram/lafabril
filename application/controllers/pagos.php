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
		$this->load->model('currencies');

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
				'rules' => 'required|numeric'
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
			if($id_pago_factura = $this->payments->register($_POST['branch'], $_POST['client'], $_POST['importe'], $_POST['fecha'], $_POST['tipo_pago'], $usuario_captura)) {
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
		$data['currencies'] = $this->currencies->getCurrencies();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pagos/agregar_pago', $data);
		$this->load->view('footer', $data);
	}
	
	public function agregar_pago_detalles($id_pago_factura)
	{
		// Load necessary models
		$this->load->model('invoices');

		$data['payment'] = $this->payments->getPrePayment($id_pago_factura);
		$data['payment_details'] = $this->payments->getPaymentDetails($id_pago_factura);
		
		// Declare the $total as float so it gets it in the foreach
		settype($total, "float");
		$total = 0.0;
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['payment_details'] as $line) {
			$total+=$line['importe_pago'];
		}
		$data['total'] = $total;
		
		$data['disponible'] = $data['payment']['importe'] - $total;

		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'invoice', 
				'label' => 'factura', 
				'rules' => 'callback_not_default'
			),
			array(
				'field' => 'pago', 
				'label' => 'pago', 
				'rules' => 'required|numeric|callback_check_balance|callback_check_available['.$data['disponible'].']'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->payments->addLine($id_pago_factura, $_POST['invoice'], $_POST['pago'])) {
				$this->session->set_flashdata('message', 'El pago ha sido registrado.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar el pago, intenta de nuevo.');
			}
		}
		
		$data['title'] = "Registrar detalles del pago";
		$data['user'] = $this->session->userdata('user');
		$data['payment'] = $this->payments->getPrePayment($id_pago_factura);
		$data['payment_details'] = $this->payments->getPaymentDetails($id_pago_factura);
		
		$id_sucursal = $data['payment']['id_sucursal'];
		$id_cliente = $data['payment']['id_cliente'];
		//exit(var_dump($data));
		$data['invoices'] = $this->invoices->getAllActive($id_sucursal, $id_cliente);
		
		
		// Declare the $total as float so it gets it in the foreach
		settype($total, "float");
		$total = 0.0;
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['payment_details'] as $line) {
			$total+=$line['importe_pago'];
		}
		$data['total'] = $total;
		
		$data['disponible'] = $data['payment']['importe'] - $total;
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pagos/agregar_pago_detalles', $data);
		$this->load->view('footer', $data);
	}
	
	public function check_balance($payment)
	{
		$invoice = $this->invoices->getInvoice($_POST['invoice']);
		
		if ($invoice['saldo'] < $payment) {
			$this->form_validation->set_message("check_balance", "El pago debe ser menor o igual al saldo.");
			return false;
		} else {
			return true;
		}
		
	}

	public function check_available($payment, $available)
	{
		if ($payment > $available) {
			$this->form_validation->set_message("check_available", "Estás pagando más de lo disponible.");
			return false;
		} else {
			return true;
		}
	}
	
	public function eliminar($id_pago_factura, $id)
	{	
		$this->payments->eliminar($id);
		redirect("pagos/agregar_pago_detalles/$id_pago_factura");
	}
	
	public function listar()
	{
		/*
		// We need it to populate the filter form
		$this->load->helper('form');

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// To populate the filter form
		$this->load->model('branches');
		$this->load->model('clients');
		*/

		// Get orders
		$data['paymentsData'] = $this->payments->getAll();
		
		$data['title'] = "Pagos";
		$data['user'] = $this->session->userdata('user');
		//$data['branches'] = $this->branches->getAll(array('status' => '1')); // Active branches
		//$data['clients'] = $this->clients->getAll(array('status' => '1')); // Active clients
		//$data['filters'] = $filters;

		// Display views
		$this->load->view('header', $data);
		$this->load->view('pagos/listar', $data);
		//$this->load->view('pedidos/filterForm', $data);
		$this->load->view('footer', $data);
	}
	
	public function detalles($id_pago_factura)
	{

		$data['title'] = "Detalles del Pago";
		$data['user'] = $this->session->userdata('user');
		$data['payment'] = $this->payments->getPayment($id_pago_factura);
		$data['payment_details'] = $this->payments->getPaymentDetails($id_pago_factura);
		
		// Declare the $total as float so it gets it in the foreach
		settype($total, "float");
		
		// For every detail of the order, gather the sum of the product of the prices and quantities
		foreach ($data['payment_details'] as $line) {
			$total+=$line['importe_pago'];
		}
		$data['total'] = $total;
		
		// Display views
		$this->load->view('header', $data);
		$this->load->view('pagos/detalles', $data);
		$this->load->view('footer', $data);
	}
	
	public function cancelar($id_pago_factura)
	{	
		if($this->payments->cancelar($id_pago_factura)) {
				$this->session->set_flashdata('message', 'El pago ha sido cancelado.');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar cancelar el pago, intenta de nuevo.');
			}
		redirect("pagos");
		
	}

	public function imprimir($id)
	{
		$payment = $this->payments->getPayment($id);

		if (count($payment) === 0) {
			// We kill the script because usually the PDF is opened in a different tab.
			exit('Pago no encontrado.');
		}

		$paymentDetails = $this->payments->getPaymentDetails($id);

		$total = 0.0;

		foreach ($paymentDetails as $detail) {
			$total += $detail['importe_pago'];
		}

		// Necessary to create a PDF
		$this->load->helper(array('dompdf', 'file'));
		
		$this->load->model('branches'); // This is mandatory to create the PDF header
		$this->load->model('clients'); // This model is necessary because the format has the client address

		$branch = $this->branches->getBranch($payment['id_sucursal']);
		$clientAddress = $this->clients->getClientAddress($payment['id_cliente']);

		$data['title'] = 'Formato de Pago';
		$data['branch'] = $branch;
		$data['folio'] = getFolio($payment['prefijo'], $payment['folio']);
		$data['clientAddress'] = $clientAddress;
		$data['payment'] = $payment;
		$data['paymentDetails'] = $paymentDetails;
		$data['total'] = $total;

		$html = $this->load->view('formatos/header', $data, true);
		$html .= $this->load->view('formatos/pago', $data, true);
		$html .= $this->load->view('formatos/footer', $data, true);

		createPDF($html, 'formato');
	}
	
	public function not_default($str) {
		if ($str == 'escoge') {
			$this->form_validation->set_message('not_default', 'Escoge una opción');
			return FALSE;
		} else {
	    	return TRUE;
	    }
	}
	
	public function finalizar($id)
	{
		if ($this->payments->finalize($id)) {
			$this->session->set_flashdata('message', 'Pago creado.');
			redirect('pagos');
		}

		$this->session->set_flashdata('error', 'Tenemos problemas por el momento, intenta en 10 minutos.');
		redirect('pagos/agregar_pago_detalles/' . $id);
	}

}