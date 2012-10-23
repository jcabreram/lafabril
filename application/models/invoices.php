<?php

class Invoices extends CI_Model
{
	
	public function getAll()
	{

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
				ORDER BY fecha_factura ASC';
		$query = $this->db->query($sql);
		
		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
}