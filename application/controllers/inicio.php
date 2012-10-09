<?php

class Inicio extends CI_Controller
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
		
		$this->load->model('orders');
	}

	public function index()
	{
		$data['title'] = 'Inicio';
		$data['user'] = $this->session->userdata('user');
		
		// Clean up all the orders that have no details
		$this->orders->limpiar_vacias();

		// Display views
		$this->load->view('header', $data);
		$this->load->view('inicio/index', $data);
		$this->load->view('footer', $data);
	}
}