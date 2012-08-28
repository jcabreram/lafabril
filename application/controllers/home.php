<?php

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	public function index()
	{
		$data['username'] = $this->session->userdata('nombre');
		$data['title'] = "La Fabril - Pantalla Principal";
			
		// Display views
		$this->load->view('header.php', $data);
		$this->load->view('index.php', $data);
		$this->load->view('footer.php', $data);
	}
}