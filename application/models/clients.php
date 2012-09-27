<?php

class Clients extends CI_Model
{
	public function create($nombre, $razon_social, $calle, $numero_exterior, $numero_interior, $colonia, $ciudad, $municipio, $estado, $pais, $codigo_postal, $rfc, $tipo_contribuyente, $contacto, $lim_credito, $dias_credito, $status)
	{
		$nombre = $this->db->escape($nombre);
		$razon_social = $this->db->escape($razon_social);
		$calle = $this->db->escape($calle);
		$numero_exterior = $this->db->escape($numero_exterior);
		$numero_interior = $this->db->escape($numero_interior);
		$colonia = $this->db->escape($colonia);
		$ciudad = $this->db->escape($ciudad);
		$municipio = $this->db->escape($municipio);
		$estado = $this->db->escape($estado);
		$pais = $this->db->escape($pais);
		$codigo_postal = $this->db->escape($codigo_postal);
		$rfc = $this->db->escape($rfc);
		$tipo_contribuyente = $this->db->escape($tipo_contribuyente);
		$contacto = $this->db->escape($contacto);
		$lim_credito = $this->db->escape($lim_credito);
		$dias_credito = $this->db->escape(intval($dias_credito));
		$status = $this->db->escape(intval($status));

		$sql = "INSERT INTO clientes VALUES (NULL, $nombre, $razon_social, $calle, $numero_exterior, $numero_interior, $colonia, $ciudad, $municipio, $estado, $pais, $codigo_postal, $rfc, $tipo_contribuyente, $contacto, $lim_credito, $dias_credito, $status)";

		return $this->db->query($sql);
	}

	public function getClientes()
	{
		$sql = 'SELECT * FROM clientes';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}

	public function getCliente($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT * FROM clientes WHERE id_cliente = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}

	public function update($id, $nombre, $razon_social, $calle, $numero_exterior, $numero_interior, $colonia, $ciudad, $municipio, $estado, $pais, $codigo_postal, $rfc, $tipo_contribuyente, $contacto, $lim_credito, $dias_credito, $status)
	{
		$id = $this->db->escape(intval($id));
		$nombre = $this->db->escape($nombre);
		$razon_social = $this->db->escape($razon_social);
		$calle = $this->db->escape($calle);
		$numero_exterior = $this->db->escape($numero_exterior);
		$numero_interior = $this->db->escape($numero_interior);
		$colonia = $this->db->escape($colonia);
		$ciudad = $this->db->escape($ciudad);
		$municipio = $this->db->escape($municipio);
		$estado = $this->db->escape($estado);
		$pais = $this->db->escape($pais);
		$codigo_postal = $this->db->escape($codigo_postal);
		$rfc = $this->db->escape($rfc);
		$tipo_contribuyente = $this->db->escape($tipo_contribuyente);
		$contacto = $this->db->escape($contacto);
		$lim_credito = $this->db->escape($lim_credito);
		$dias_credito = $this->db->escape(intval($dias_credito));
		$status = $this->db->escape(intval($status));

		$sql = "UPDATE clientes SET nombre = $nombre, razon_social = $razon_social, calle = $calle, numero_exterior = $numero_exterior, numero_interior = $numero_interior, colonia = $colonia, ciudad = $ciudad, municipio = $municipio, estado = $estado, pais = $pais, codigo_postal = $codigo_postal, rfc = $rfc, tipo_contribuyente = $tipo_contribuyente, contacto = $contacto, limite_credito = $lim_credito, dias_credito = $dias_credito, activo = $status WHERE id_cliente = $id";

		return $this->db->query($sql);
	}

	public function setStatus($id, $status)
	{
		$id = $this->db->escape(intval($id));
		$status = $this->db->escape(intval($status));

		$sql = "UPDATE clientes SET activo = $status WHERE id_cliente = $id";

		return $this->db->query($sql);
	}
}