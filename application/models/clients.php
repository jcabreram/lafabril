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

	public function getAll($filters = false)
	{
		// Filters
		$typeOfPerson = isset($filters['typeOfPerson']) ? $filters['typeOfPerson'] : false;
		$status = isset($filters['status']) ? $filters['status'] : false;

		$this->db->select('*');
		$this->db->from('clientes');
		$this->db->order_by('nombre', 'ASC');

		if ($typeOfPerson !== false) {
			$this->db->where('tipo_contribuyente', $typeOfPerson);
		}
		
		if ($status !== false) {
			$this->db->where('activo', $status);
		}

		$query = $this->db->get();

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getActiveClients()
	{
		$sql = 'SELECT * FROM clientes WHERE activo = 1 ORDER BY nombre ASC';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getWalletClients($branch, $from_client, $to_client, $fecha) {
		$branch = $this->db->escape(intval($branch));
		$from_client = $this->db->escape($from_client);
		$to_client = $this->db->escape($to_client);
		$fecha = $this->db->escape($fecha);
		

		$sql = "SELECT
					cl.id_cliente,
					cl.nombre AS nombre_cliente
				FROM facturas AS fa
				JOIN movimientos AS mo ON mo.id_documento = fa.id_factura
				JOIN clientes AS cl ON mo.id_cliente=cl.id_cliente
				WHERE fa.id_sucursal = $branch AND fa.estatus != 'X' AND fa.fecha <= $fecha AND left(cl.nombre,1) BETWEEN left($from_client,1) AND left($to_client,1)
				GROUP BY cl.id_cliente";
				
		//exit(var_dump($sql));
				

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}

	public function getClient($id)
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

	public function getClientAddress($id)
	{
		$client = $this->getClient($id);

		$clientAddress =  $client['calle'] . ' #' . $client['numero_exterior'];
		
		if ($client['numero_interior'] !== null) {
			$clientAddress .= ' interior ' . $client['numero_interior'];
		}

		$clientAddress .= ' ' . $client['colonia'] . '. ' . $client['ciudad'] 
		. ', ' . $client['municipio'] . ', ' . $client['estado'] . ', ' . $client['pais']
		. '. C.P. ' . $client['codigo_postal'];

		return $clientAddress;
	}
}