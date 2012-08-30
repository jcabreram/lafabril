<?php

class Users extends CI_Model
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

	public function signUp($username, $password, $fullName, $department, $status)
	{
		

		$sql = "INSERT INTO usuarios (id, username, password, nombre, departamento, fecha_alta, activo)
				VALUES (NULL, $username, $password, $fullName, $department, $status)";

		exit(var_dump($sql));
	}
}