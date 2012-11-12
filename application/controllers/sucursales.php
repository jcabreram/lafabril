<?php

class Sucursales extends CI_Controller
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

		$this->load->model('branches');
	}

	public function index()
	{
		$this->listar();
	}

	public function registrar()
	{
		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'name', 
				'label' => 'nombre', 
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'address', 
				'label' => 'dirección', 
				'rules' => 'trim|max_length[255]'
			),
			array(
				'field' => 'status',
				'label' => 'estatus',
				'rules' => 'required'
			),
			array(
				'field' => 'iva',
				'label' => 'IVA',
				'rules' => 'required|numeric'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->branches->create($_POST['name'], $_POST['address'], $_POST['status'], $_POST['iva'])) {
				$this->session->set_flashdata('message', 'La sucursal "' . htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') . '" ha sido registrada.');
				redirect('sucursales');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema al intentar registrar la sucursal, intenta de nuevo.');
			}
		}

		$data['title'] = "Registrar Sucursal";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('sucursales/registrar', $data);
		$this->load->view('footer', $data);
	}

	public function editar($id)
	{
		// Load form validation library
		$this->load->library('form_validation');

		// Setting error delimiters
		$this->form_validation->set_error_delimiters('<span class="input-notification error png_bg">', '</span>');
		
		// Define validation rules
		$config = array(
			array(
				'field' => 'name', 
				'label' => 'nombre', 
				'rules' => 'trim|required|max_length[50]'
			),
			array(
				'field' => 'address', 
				'label' => 'dirección', 
				'rules' => 'trim|max_length[255]'
			),
			array(
				'field' => 'status',
				'label' => 'estatus',
				'rules' => 'required'
			),
			array(
				'field' => 'iva',
				'label' => 'IVA',
				'rules' => 'required|numeric'
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->branches->update($id, $_POST['name'], $_POST['address'], $_POST['status'], $_POST['iva'])) {
				$this->session->set_flashdata('message', 'La sucursal "' . htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') . '" ha sido modificada.');
				redirect('sucursales');
			} else {
				$this->session->set_flashdata('error', 'Tuvimos un problema intentando actualizar a la sucursal, intenta de nuevo.');
			}
		}
		
		// Get the array with the row of the user in the database
		$data['branch'] = $this->branches->getBranch($id);
		
		// If the branch doesn't exist
		if (!is_array($data['branch'])) {
			$this->session->set_flashdata('error', 'Tuvimos un problema accediendo a la sucursal, <a href="' . site_url('sucursales/editar/' . $id) . '" title="Intenta de Nuevo">intenta de nuevo</a>.');
			redirect('sucursales');
		}

		$data['title'] = "Editar Sucursal";
		$data['user'] = $this->session->userdata('user');
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('sucursales/editar', $data);
		$this->load->view('footer', $data);
	}

	private function _sanitizeFilters($dirtyFilters)
	{
		$filters = array();

		if (isset($dirtyFilters['estatus']) && trim($dirtyFilters['estatus']) !== '') {
			switch ($dirtyFilters['estatus']) {
				case 'activo':
					$filters['status'] = '1';
					break;

				case 'inactivo':
					$filters['status'] = '0';
					break;
			}
		}

		return $filters;
	}

	public function listar()
	{
		// We need it to populate the filter form
		$this->load->helper('form');

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// Get the array with the users in the database
		$data['branches'] = $this->branches->getAll($filters);
		
		$data['title'] = 'Sucursales';
		$data['user'] = $this->session->userdata('user');
		$data['filters'] = $filters;

		// Display views
		$this->load->view('header', $data);
		$this->load->view('sucursales/listar', $data);
		$this->load->view('sucursales/filterForm', $data);
		$this->load->view('footer', $data);
	}

	public function filtrar()
	{
		if ($_POST) {
			$filters = array();

			$status = isset($_POST['status']) ? trim($_POST['status']) : false;

			if ($status !== false && $status !== '') {
				switch ($status) {
					case '1':
						$filters['estatus'] = 'activo';
						break;

					case '0':
						$filters['estatus'] = 'inactivo';
						break;
				}
			}

			if (count($filters) > 0) {
				redirect('sucursales/listar/' . $this->uri->assoc_to_uri($filters));
			} else {
				redirect('sucursales');
			}
		}

		redirect();
	}

	public function exportar()
	{
		$this->load->helper(array('dompdf', 'file'));

		// Fetch filters from uri
		$filters = $this->uri->uri_to_assoc(3);
		$filters = $this->_sanitizeFilters($filters);

		// Data we may need in our PDF
		$data['title'] = "Reporte de Sucursales";
		
		// Get the array with the users in the database
		$data['branches'] = $this->branches->getAll($filters);
		if (!isset($filters['status'])) {
			$data['status'] = 'Todos';
		} else {
			switch ($filters['status']) {
				case '1':
					$data['status'] = 'Activo';
					break;

				case '0':
					$data['status'] = 'Inactivo';
					break;
			}
		}

		$html = $this->load->view('reportes/header', $data, true);
		$html .= $this->load->view('reportes/sucursales', $data, true);
		createPDF($html, 'reporte');
	}

	public function activar($id)
	{
		$this->branches->setStatus($id, 1);
		$this->session->set_flashdata('message', 'La sucursal ha sido activada.');
		redirect('sucursales');
	}

	public function desactivar($id)
	{
		$this->branches->setStatus($id, 0);
		$this->session->set_flashdata('message', 'La sucursal ha sido desactivada.');
		redirect('sucursales');		
	}
}