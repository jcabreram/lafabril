<?php

class Users_Model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	
	public function login($username, $password)
	{
		// FALTA FILTRAR LOS DATOS

		$sql = "SELECT id, nombre, departamento, activo FROM usuarios WHERE username = '$username' AND password = '$password'";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			$row = $query->row();

			// Load session library
			$this->load->library('session');

			// Prepare data for saving
			$data = array(
				'id' => $row->id,
				'nombre' => $row->nombre,
				'departamento' => $row->departamento,
				'activo' => $row->activo
 			);

			// Save session data
			$this->session->set_userdata($data);

			return true;
		}

		return false;
	}
}