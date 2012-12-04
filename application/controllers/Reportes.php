<?php

class Reportes extends CI_Controller
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

		$this->load->model('clients');
	}

	public function antiguedad_saldos()
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

		$data['title'] = "Crear Reporte de Antigüedad de Saldos";
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['clients'] = $this->clients->getActiveClients();

		// Display views
		$this->load->view('header', $data);
		$this->load->view('reportes/antiguedad_saldos', $data);
		$this->load->view('footer', $data);
	}
}