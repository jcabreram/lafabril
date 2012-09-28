<?php

class Branches extends CI_Model
{
	public function create($name, $address, $status, $iva)
	{
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);
		$status = $this->db->escape($status);
		$iva = $this->db->escape(floatval($iva));

		$sql = "INSERT INTO sucursales (id_sucursal, nombre, direccion, estatus, iva) VALUES (NULL, $name, $address, $status, $iva)";

		return $this->db->query($sql);
	}

	public function getBranches()
	{
		$sql = 'SELECT * FROM sucursales ORDER BY nombre ASC';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getActiveBranches()
	{
		$sql = 'SELECT * FROM sucursales WHERE estatus = 1 ORDER BY nombre ASC';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}

	public function getBranch($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT * FROM sucursales WHERE id_sucursal = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}

	public function update($id, $name, $address, $status, $iva)
	{
		$id = $this->db->escape(intval($id));
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);
		$status = $this->db->escape($status);
		$iva = $this->db->escape(floatval($iva));

		$sql = "UPDATE sucursales SET nombre = $name, direccion = $address, estatus = $status, iva = $iva WHERE id_sucursal = $id";

		return $this->db->query($sql);
	}

	public function setStatus($id, $status)
	{
		$id = $this->db->escape(intval($id));
		$status = $this->db->escape($status);

		$sql = "UPDATE sucursales SET estatus = $status WHERE id_sucursal = $id";

		return $this->db->query($sql);
	}
}