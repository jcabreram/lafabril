<?php

class Salesmen extends CI_Model
{
	public function getAll()
	{
		$sql = 'SELECT id_vendedor, nombre FROM vendedores INNER JOIN empleados ON vendedores.id_empleado = empleados.id_empleado';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getSalesman($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = "SELECT id_vendedor, nombre FROM vendedores INNER JOIN empleados ON vendedores.id_empleado = empleados.id_empleado WHERE id_vendedor = $id";
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}

}