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
			),
			array(
				'field' => 'from_client', 
				'label' => 'de cliente', 
				'rules' => 'required'
			),
			array(
				'field' => 'to_client', 
				'label' => 'a cliente', 
				'rules' => 'required'
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
		
		$i = 0;

		foreach ($clients as $client) {
			$clientData = array(
				'name' => $client['nombre_cliente'],
				'invoices' => $this->invoices->getWalletInvoices($filters['branch'], $client['id_cliente'], $filters['cutDate'])
			);
			
			$wallet[$i] = $clientData;

			foreach($wallet[$i]['invoices'] as $invoiceKey => $invoice) {
				$wallet[$i]['invoices'][$invoiceKey]['payments'] = 
				$this->payments->getWalletPayments($invoice['id_factura'], $filters['cutDate']);
				$wallet[$i]['invoices'][$invoiceKey]['credit_notes'] = 
				$this->credit_notes->getWalletCreditNotes($invoice['id_factura'], $filters['cutDate']);
			}
			
			$i++;
		}
		
		//exit(var_dump($wallet));
		
		// Get branch name
		$branch = $this->branches->getBranch($filters['branch']);
		$branch = $branch['nombre'];
		
		$cutDate = convertToHumanDate($filters['cutDate']);
		
		$data['title'] = 'Reporte de Cartera';
		$data['clients'] = $clients;
		$data['from_client'] = $filters['fromClient'];
		$data['to_client'] = $filters['toClient'];
		$data['cutDate'] = $cutDate;
		$data['branch'] = $branch;
		$data['wallet'] = $wallet;
				
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