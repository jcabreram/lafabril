<?php

class Users_Model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	
	public function login($username, $password)
	{
		$username = $this->security->xss_clean($username);
		$password = $this->security->xss_clean($password);

		$this->db->where('username', $username);
		$this->db->where('password', $password);

		$query = $this->db->get('users');

		if ($query->num_rows == 1) {
			$row = $query->row();
			$data = array(
				'user_id' => $row->id,
				'username' => $row->username,
				'nombre' => $row->nombre,
				'departamento' => $row->departamento
			);

			$this->session->set_userdata($data);

			return true;
		}

		return false;
	}
}