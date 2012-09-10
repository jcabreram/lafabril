<?php

class Sucursales extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('branches');
	}

	public function registrar()
	{
	}
}