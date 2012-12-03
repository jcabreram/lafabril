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
		
		/*
		
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
		
		*/
		
		if ($this->form_validation->run()) {
			$usuario = $this->session->userdata('user');
			
			$filters = array(
				'branch' => $_POST['branch'],
				'cutDate' => $_POST['fecha_corte'],
				'fromClient' => $_POST['from_client'],
				'toClient' => $_POST['to_client']
			);
			
			$this->_makeReport($filters);
			
			$transactions = $this->transactions->getReportData($_POST['branch'], $_POST['from_client'], $_POST['to_client'], $_POST['fecha_corte']);
			exit(var_dump($transactions));
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
		
		$transactions = $this->transactions->getReportData($filters['branch'], $filters['fromClient'], $filters['toClient'], $filters['cutDate']);

		
		if (count($transactions) === 0) {
			exit('No existen movimientos con esas especificaciones.');
		}
		
		// Get branch name
		$branch = $this->branches->getBranch($filters['branch']);
		$branch = $branch['nombre'];

		$data['title'] = 'Reporte Financiero de Facturas';
		$data['transactions'] = $transactions;

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes_financieros/movimientos', $data, true);
		$html .= $this->load->view('reportes/footer', $data, true);
		createPDF($html, 'reporte');
	}
}