<?php

class Users_Model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
		$this->load->library('session');
	}
	
	public function login($username, $password)
	{
		$username = $this->db->escape($username);
		$password = $this->db->escape(sha1($password));

		$sql = "SELECT id, nombre, departamento, activo FROM usuarios WHERE username = $username AND password = $password";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			$row = $query->row();

			// If user is active
			if ($row->activo == '1') {
				// Prepare data for saving
				$data['user'] = array(
					'id' => $row->id,
					'nombre' => $row->nombre,
					'departamento' => $row->departamento
	 			);

				// Save session data
				$this->session->set_userdata($data);

				return true;
			}
		}

		return false;
	}
}