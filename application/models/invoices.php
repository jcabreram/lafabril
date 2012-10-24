<?php

class Invoices extends CI_Model
{
	
	public function getAll($filters = false)
	{
		/*** PREPARE FILTERS ***/
		$branch = isset($filters['branch']) ? $filters['branch'] : false;
		$client = isset($filters['client']) ? $filters['client'] : false;
		$status = isset($filters['status']) ? $filters['status'] : false;
		/*** PREPARE FILTERS ***/

		$where = '';

		if ($branch !== false) {
			$where .= 'WHERE fa.id_sucursal = ' . $this->db->escape(intval($branch));
		}

		if ($client !== false) {
			$where .= ' AND pe.id_cliente = ' . $this->db->escape(intval($client));
		}

		if ($status !== false) {
			$where .= ' AND fa.estatus = ' . $this->db->escape($status);
		}

		$sql = 'SELECT 
					fa.id_factura, 
					fp.prefijo, 
					fo.folio, 
					su.nombre AS nombre_sucursal, 
					cl.nombre AS nombre_cliente, 
					fa.fecha AS fecha_factura, 
					fa.estatus
				FROM facturas AS fa
				JOIN sucursales AS su ON fa.id_sucursal=su.id_sucursal
				JOIN pedidos AS pe ON pe.id_pedido=fa.id_pedido
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento="F"
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento="F"
				' . $where . '
				ORDER BY fecha_factura ASC';
		$query = $this->db->query($sql);
		
		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
}