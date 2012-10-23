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

}