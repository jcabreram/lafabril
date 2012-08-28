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
		die(var_dump($this->session->userdata('nombre')
			));
	}
}