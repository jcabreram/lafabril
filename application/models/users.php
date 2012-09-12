<?php

class Users extends CI_Model
{
	/**
	 * @return false in case of failure and an array in case of success
	 */
	public function login($username, $password)
	{
		$username = $this->db->escape($username);
		$password = $this->db->escape(sha1($password));

		$sql = "SELECT id, nombre, departamento, activo FROM usuarios WHERE username = $username AND password = $password";
		$query = $this->db->query($sql);
		
		if($query->num_rows() == 1) {
			return $query->row_array();
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

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}

	public function getUser($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT * FROM usuarios WHERE id = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}	
	
	public function update($id, $username, $password, $fullName, $department, $status)
	{
		$id = $this->db->escape(intval($id));
		$username = $this->db->escape($username);
		$password = (empty($password)) ? 'NULL' : $this->db->escape(sha1($password));
		$fullName = $this->db->escape($fullName);
		$department = $this->db->escape($department);
		$status = $this->db->escape(intval($status));

		$sql = "UPDATE usuarios	SET username = $username, password = $password, nombre = $fullName, departamento = $department, activo = $status WHERE id = $id";

		return $this->db->query($sql);
	}

	public function setStatus($id, $status)
	{
		$id = $this->db->escape(intval($id));
		$status = $this->db->escape(intval($status));

		$sql = "UPDATE usuarios SET activo = $status WHERE id = $id";
		
		return $this->db->query($sql);
	}
}