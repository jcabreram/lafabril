<?php

class Branches extends CI_Model
{
	public function create($name, $address, $status)
	{
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);
		$status = $this->db->escape(intval($status));

		$sql = "INSERT INTO sucursales (id_sucursal, nombre, direccion, estatus) VALUES (NULL, $name, $address, $status)";

		return $this->db->query($sql);
	}

	public function getBranches()
	{
		$sql = 'SELECT * FROM sucursales ORDER BY nombre ASC';
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

	public function update($id, $name, $address, $status)
	{
		$id = $this->db->escape(intval($id));
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);
		$status = $this->db->escape(intval($status));

		$sql = "UPDATE sucursales SET nombre = $name, direccion = $address, estatus = $status WHERE id_sucursal = $id";

		return $this->db->query($sql);
	}

	public function setStatus($id, $status)
	{
		$id = $this->db->escape(intval($id));
		$status = $this->db->escape(intval($status));

		$sql = "UPDATE sucursales SET estatus = $status WHERE id_sucursal = $id";

		return $this->db->query($sql);
	}
}