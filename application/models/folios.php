<?php

class Folios extends CI_Model
{	
	public function getLastFolio($id_sucursal, $tipo_documento)
	{
		$id_sucursal = $this->db->escape(intval($id_sucursal));
		$tipo_documento = $this->db->escape($tipo_documento);
		
		$sql = "SELECT prefijo, ultimo_folio FROM folios_prefijo WHERE id_sucursal = $id_sucursal AND tipo_documento = $tipo_documento";
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}
		
}