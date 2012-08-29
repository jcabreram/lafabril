<?php

class Inicio extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		// Is user not logged in?
		if (!$this->session->userdata('user')) {
			redirect('/usuarios/ingresar');
		}

		// Page info
		$data['title'] = 'Inicio';
		$data['user'] = $this->session->userdata('user');

		// Display views
		$this->load->view('header.php', $data);
		$this->load->view('index.php', $data);
		$this->load->view('footer.php', $data);
	}
}