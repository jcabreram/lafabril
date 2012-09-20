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
		$status = $this->db->escape($status);
		

		$sql = "INSERT INTO usuarios (id, username, password, nombre, departamento, activo)
				VALUES (NULL, $username, $password, $fullName, $department, $status)";
		
		return $this->db->query($sql);
	}

	public function getUsers($department = false, $status = false)
	{
		// Trim returns an empty string if it's argument is false
		$department = trim($department);
		$status = trim($status);

		$this->db->select('*');
		$this->db->from('usuarios');
		$this->db->order_by('activo', 'DESC');

		if ($department !== '') {
			$this->db->where('departamento', $department);
		}
		
		if ($status !== '') {
			$this->db->where('activo', $status);
		}

		$query = $this->db->get();

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
	
	
	public function update($id, $username, $password, $fullName, $department, $status, $sucursales)
	{
		$id = $this->db->escape(intval($id));
		$username = $this->db->escape($username);
		$password = (empty($password)) ? false : $this->db->escape(sha1($password));
		$fullName = $this->db->escape($fullName);
		$department = $this->db->escape($department);
		$status = $this->db->escape($status);
		//$sucursales = $this->db->escape($sucursales);
		$resultado = TRUE;

		if (!$password) {
			$sql = "UPDATE usuarios	SET username = $username, nombre = $fullName, departamento = $department, activo = $status WHERE id = $id";
		} else {
			$sql = "UPDATE usuarios	SET username = $username, password = $password, nombre = $fullName, departamento = $department, activo = $status WHERE id = $id";
		}
		
		if(!$this->db->query($sql))
			$resultado = FALSE;
		
				
		//var_dump($sucursales);
		
		if (is_array($sucursales)) {
			$sql2 = "DELETE FROM usuarios_sucursales WHERE id_usuario = $id";
			if(!$this->db->query($sql2))
				$resultado = FALSE;
			foreach ($sucursales as $sucursal) {
				$sql3 = "INSERT INTO usuarios_sucursales(id_usuario,id_sucursal) VALUES ($id, $sucursal)";
				if(!$this->db->query($sql3))
					$resultado = FALSE;
			}	
		}
		
		return $resultado;
	}
	

	public function setStatus($id, $status)
	{
		$id = $this->db->escape(intval($id));
		$status = $this->db->escape($status);

		$sql = "UPDATE usuarios SET activo = $status WHERE id = $id";
		
		return $this->db->query($sql);
	}
}