<?php

class Products extends CI_Model
{
	
	public function getProducts()
	{
		$sql = 'SELECT * FROM productos';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}

}