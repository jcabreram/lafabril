<?php

class Currencies extends CI_Model
{
	public function getCurrencies() {
	
		$sql = 'SELECT * FROM monedas';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
		
	}
}