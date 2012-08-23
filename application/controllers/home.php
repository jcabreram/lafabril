<?php

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url');
	}
	
	public function index()
	{
		if (true) {
			redirect('login');
		}
	}
}