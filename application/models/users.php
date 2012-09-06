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
			if ($row->activo == 1) {
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
		$username = $this->db->escape($username);
		$password = $this->db->escape(sha1($password));
		$fullName = $this->db->escape($fullName);
		$department = $this->db->escape($department);
		$status = $this->db->escape(intval($status));
		

		$sql = "INSERT INTO usuarios (id, username, password, nombre, departamento, activo)
				VALUES (NULL, $username, $password, $fullName, $department, $status)";
		
		return $this->db->query($sql);
	}

	public function getUsers()
	{
		$sql = 'SELECT * FROM usuarios';
		$query = $this->db->query($sql);

		// Returns the query result as an array of objects, or an empty array on failure
		return $query->result();
	}

	public function getUser($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT * FROM usuarios WHERE id = ' . $id;
		$query = $this->db->query($sql);

		exit(var_dump($query->row()));
	}	
	
	public function update($id, $username, $password, $fullName, $department, $status)
	{
		$username = $this->db->escape($username);
		$password = $this->db->escape($password);
		$fullName = $this->db->escape($fullName);
		$department = $this->db->escape($department);
		$status = $this->db->escape($status);
				
		if ($password == '\''. sha1('') . '\'') {
			$sql = "UPDATE usuarios
				SET username=$username, nombre=$fullName, departamento=$department, activo=$status
				WHERE id=$id";
				
		} else {
			$sql = "UPDATE usuarios
				SET username=$username, password=$password, nombre=$fullName, departamento=$department, activo=$status
				WHERE id=$id";
		}
		
		return $this->db->query($sql);
	}
	
	public function deactivate($id)
	{
		$sql = "UPDATE usuarios SET activo = 0 WHERE id = $id";
		return $this->db->query($sql);
	}

	public function activate($id)
	{
		$sql = "UPDATE usuarios SET activo = 1 WHERE id = $id";
		return $this->db->query($sql);
	}
}