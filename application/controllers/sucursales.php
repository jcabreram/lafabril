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
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->branches->create($_POST['name'], $_POST['address'])) {
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

	public function index()
	{
		$data['title'] = "Sucursales";
		$data['user'] = $this->session->userdata('user');
		
		// Get the array with the users in the database
		$data['branches'] = $this->branches->getBranches();
			
		// Display views
		$this->load->view('header', $data);
		$this->load->view('sucursales/index', $data);
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
			)
		);

		$this->form_validation->set_rules($config);

		// If validation was successful
		if ($this->form_validation->run()) {
			if($this->branches->update($id, $_POST['name'], $_POST['address'])) {
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

	public function eliminar($id)
	{
		$this->branches->delete($id);
		$this->session->set_flashdata('message', 'La sucursal ha sido eliminada.');
		redirect('sucursales');
	}
}