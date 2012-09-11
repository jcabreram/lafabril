<?php

class Branches extends CI_Model
{
	public function create($name, $address)
	{
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);

		$sql = "INSERT INTO sucursales (id_sucursal, nombre, direccion) VALUES (NULL, $name, $address)";

		return $this->db->query($sql);
	}

	public function getBranches()
	{
		$sql = 'SELECT * FROM sucursales';
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

	public function update($id, $name, $address)
	{
		$id = $this->db->escape(intval($id));
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);

		$sql = "UPDATE sucursales SET nombre = $name, direccion = $address WHERE id_sucursal = $id";

		return $this->db->query($sql);
	}

	public function delete($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = "DELETE FROM sucursales WHERE id_sucursal = $id";

		return $this->db->query($sql);
	}
}