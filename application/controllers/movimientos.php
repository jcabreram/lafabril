<?php

class Movimientos extends CI_Controller
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

		$this->load->model('transactions');
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
				'rules' => 'required'
			),
			array(
				'field' => 'fecha_corte', 
				'label' => 'fecha de inicio', 
				'rules' => 'required|exact_length[10]|alpha_dash'
			)
		);

		$this->form_validation->set_rules($config);
		
		// If validation was successful
		if ($this->form_validation->run()) {
			$filters = array(
				'branch' => $_POST['branch'],
				'cutDate' => $_POST['fecha_corte'],
				'fromClient' => $_POST['from_client'],
				'toClient' => $_POST['to_client']
			);
			
			$this->_makeReport($filters);
		}
		
		
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			
			$filters = array(
				'branch' => $_POST['branch'],
				'cutDate' => $_POST['fecha_corte'],
				'fromClient' => $_POST['from_client'],
				'toClient' => $_POST['to_client']
			);
			
			$this->_makeReport($filters);
		}

		$data['title'] = "Crear Reporte de Cartera";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['clients'] = $this->clients->getActiveClients();

		// Display views
		$this->load->view('header', $data);
		$this->load->view('movimientos/crear_reporte', $data);
		$this->load->view('footer', $data);
	}
	
	private function _makeReport($filters)
	{
		$this->load->helper(array('dompdf', 'file'));
		$this->load->model('branches');
		$this->load->model('clients');
		$this->load->model('invoices');
		$this->load->model('payments');
		$this->load->model('credit_notes');
		

		$wallet = array();
		$clients = $this->clients->getWalletClients($filters['branch'], $filters['fromClient'], $filters['toClient'], $filters['cutDate']);

		foreach ($clients as $client) {
			$wallet[$client['id_cliente']]['name'] = $client['nombre_cliente'];
			$wallet[$client['id_cliente']]['invoices'] = $this->invoices->getWalletInvoices($filters['branch'], $client['id_cliente'], $filters['cutDate']);

			foreach($wallet[$client['id_cliente']]['invoices'] as $invoice) {
				$wallet[$client['id_cliente']]['invoices'][$invoice['id_factura']]['payments']
					= $this->payments->getWalletPayments($invoice['id_factura'], $filters['cutDate']);
				$wallet[$client['id_cliente']]['invoices'][$invoice['id_factura']]['credit_notes']
					= $this->credit_notes->getWalletCreditNotes($invoice['id_factura'], $filters['cutDate']);
			}
		}
		
		exit(var_dump($wallet));
		
		// Get branch name
		$branch = $this->branches->getBranch($filters['branch']);
		$branch = $branch['nombre'];
		
		// Get clients names
		$from_client = $this->clients->getClient($filters['from_client']);
		$from_client = $from_client['nombre'];
		$to_client = $this->clients->getClient($filters['to_client']);
		$to_client = $to_client['nombre'];
		
		$cutDate = convertToHumanDate($filters['cutDate']);
		
		$data['clients'] = $clients;
		$data['from_client'] = $from_client;
		$data['to_client'] = $to_client;
		$data['cutDate'] = $cutDate;
				
		if (count($clients) === 0) {
			$this->session->set_flashdata('attention', 'No existen facturas con esas especificaciones.');
			redirect("movimientos/crear_reporte");
		}

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes_financieros/movimientos', $data, true);
		$html .= $this->load->view('reportes/footer', $data, true);
		createPDF($html, 'reporte');
	}
}