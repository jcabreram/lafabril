<?php

class Branches extends CI_Model
{
	public function create($name, $address, $status, $iva)
	{
		$pedidos = mb_substr($name, 0, 3).'P';
		$facturas = mb_substr($name, 0, 3).'F';
		$nota_venta = mb_substr($name, 0, 3).'N';
		$pagos = mb_substr($name, 0, 3).'A';
		$nota_credito = mb_substr($name, 0, 3).'B';
		
		$pedidos = $this->db->escape(strtoupper($pedidos));
		$facturas = $this->db->escape(strtoupper($facturas));
		$nota_venta = $this->db->escape(strtoupper($nota_venta));
		$pagos = $this->db->escape(strtoupper($pagos));
		$nota_credito = $this->db->escape(strtoupper($nota_credito));
		
		// Convert the tax to decimals
		$iva = $iva/100;
	
		$name = $this->db->escape($name);
		$address = empty($address) ? 'NULL' : $this->db->escape($address);
		$status = $this->db->escape($status);
		$iva = $this->db->escape(floatval($iva));
		
		$this->db->trans_start();

		$sql = "INSERT INTO sucursales (id_sucursal, nombre, direccion, estatus, iva) VALUES (NULL, $name, $address, $status, $iva)";
		$this->db->query($sql);
		
		$id = $this->db->insert_id();
		
		// Fill the prefixes for the folios
		$sql = "INSERT INTO folios_prefijo (id, id_sucursal, tipo_documento, prefijo, ultimo_folio) VALUES (NULL, $id, 'P', $pedidos, 0)";
		$this->db->query($sql);
		$sql = "INSERT INTO folios_prefijo (id, id_sucursal, tipo_documento, prefijo, ultimo_folio) VALUES (NULL, $id, 'F', $facturas, 0)";
		$this->db->query($sql);
		
		$sql = "INSERT INTO folios_prefijo (id, id_sucursal, tipo_documento, prefijo, ultimo_folio) VALUES (NULL, $id, 'N', $nota_venta, 0)";
		$this->db->query($sql);
		
		$sql = "INSERT INTO folios_prefijo (id, id_sucursal, tipo_documento, prefijo, ultimo_folio) VALUES (NULL, $id, 'A', $pagos, 0)";
		$this->db->query($sql);
		
		$sql = "INSERT INTO folios_prefijo (id, id_sucursal, tipo_documento, prefijo, ultimo_folio) VALUES (NULL, $id, 'B', $nota_credito, 0)";
		$this->db->query($sql);
		
		$this->db->trans_complete();
		
		return ($this->db->trans_status() === true);
	}

	public function getAll($filters = false)
	{
		// Filters
		$status = isset($filters['status']) ? $filters['status'] : false;

		$this->db->select('*');
		$this->db->from('sucursales');
		$this->db->order_by('nombre', 'ASC');

		if ($status !== false) {
			$this->db->where('estatus', $status);
		}

		$query = $this->db->get();

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
		// Convert the tax to decimals
		$iva = $iva/100;
		
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