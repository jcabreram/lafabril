<?php

class Notas_Credito extends CI_Controller
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

		$this->load->model('credit_notes');
	}

	public function index()
	{
		$this->listar();
	}
	
	public function listar()
	{
		$this->load->view('header', $data);
		$this->load->view('notas_credito/listar', $data);
		$this->load->view('footer', $data);		
	}
	
	public function registrar()
	{
		// Load necessary models
		$this->load->model('userBranches');
		$this->load->model('clients');

		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		$config = array(
			array(
				'field' => 'branch', 
				'label' => 'sucursal', 
				'rules' => 'required'
			),
			array(
				'field' => 'client', 
				'label' => 'cliente', 
				'rules' => 'required'
			),
			array(
				'field' => 'date', 
				'label' => 'fecha', 
				'rules' => 'required|callback_valid_date'
			),
			array(
				'field' => 'type', 
				'label' => 'tipo nota de crédito', 
				'rules' => 'required'
			), 
			array(
				'field' => 'observations',
				'label' => 'observaciones',
				'rules' => 'max_length[255]'
			)
		);

		$this->form_validation->set_rules($config);
		
		$user = $this->session->userdata('user');
		
		if ($this->form_validation->run()) {
			if($creditNoteId = $this->credit_notes->register($_POST['branch'], $_POST['client'], $_POST['date'], $_POST['type'], $_POST['observations'], $user['id'])) {
				redirect('notas_credito/registrar_detalles/' . $creditNoteId);
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema, intenta en 10 minutos.');
			}
		}
		
		$data['title'] = 'Registrar Nota de Crédito';
		$data['user'] = $this->session->userdata('user');
		$data['branches'] = $this->userBranches->getActiveUserBranches($data['user']['id']);
		$data['clients'] = $this->clients->getActiveClients();
		
		$this->load->view('header', $data);
		$this->load->view('notas_credito/registrar', $data);
		$this->load->view('footer', $data);
	}
	
	public function valid_date($date)
	{
		$date = explode('/', $date);
		
		if (count($date) === 3 && checkdate($date[1], $date[0], $date[2])) {
			return true;
		}
		
		$this->form_validation->set_message('valid_date', 'Escribe una fecha válida');
		return false;
	}
	
	public function registrar_detalles($id)
	{
		
	}
}