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
	
	
	// For payments, we need to get the invoices that are active (and the clients owe) that are from a specific client and branch
	public function getAllActive($id_sucursal, $id_cliente) {
		$sql = "SELECT 
					fa.id_factura, 
					fp.prefijo, 
					fo.folio, 
					su.nombre AS nombre_sucursal, 
					cl.nombre AS nombre_cliente, 
					fa.fecha AS fecha_factura, 
					fa.estatus,
					mo.importe,
					mo.saldo
				FROM facturas AS fa
				JOIN sucursales AS su ON fa.id_sucursal=su.id_sucursal
				JOIN pedidos AS pe ON pe.id_pedido=fa.id_pedido
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento='F'
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento='F'
				JOIN movimientos AS mo ON fa.id_factura = mo.id_documento
				WHERE su.id_sucursal = $id_sucursal AND cl.id_cliente = $id_cliente
				AND fa.estatus = 'A'
				ORDER BY fecha_factura ASC";
				
		//exit(var_dump($sql));
		$query = $this->db->query($sql);
		
		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getWalletInvoices($branch, $client, $fecha) {
		$branch = $this->db->escape(intval($branch));
		$client = $this->db->escape(intval($client));
		$fecha = $this->db->escape($fecha);
		

		$sql = "SELECT
					fa.id_factura,
					fp.prefijo,
					fo.folio,
					fa.fecha,
					mo.importe,
					mo.fecha_vencimiento,
					mo.saldo,
					su.nombre AS nombre_sucursal
				FROM facturas AS fa
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento='F'
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento='F'
				JOIN sucursales AS su ON fa.id_sucursal=su.id_sucursal
				JOIN movimientos AS mo ON mo.id_documento = fa.id_factura
				JOIN clientes AS cl ON mo.id_cliente=cl.id_cliente
				WHERE fa.id_sucursal = $branch AND fa.estatus != 'X' AND fa.fecha <= $fecha AND cl.id_cliente = $client
				GROUP BY fa.id_factura";
			
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getInvoice($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 
					fa.id_factura,
					fa.id_pedido, 
					pe.id_cliente,
					cl.nombre AS nombre_cliente,
					cl.razon_social,
					cl.rfc,
					fp.prefijo, 
					fo.folio, 
					fa.fecha AS fecha_factura,
					fa.estatus,
					fa.iva,
					fa.id_sucursal,
					su.nombre AS nombre_sucursal,
					su.iva AS sucursal_iva,
					mo.importe,
					mo.saldo
				FROM facturas AS fa
				JOIN pedidos AS pe ON pe.id_pedido=fa.id_pedido
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento="F"
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento="F"
				JOIN sucursales AS su ON fa.id_sucursal=su.id_sucursal
				JOIN movimientos AS mo ON fa.id_factura = mo.id_documento
				WHERE id_factura = ' . $id;

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}	

	public function getInvoiceProducts($id)
	{
		return $this->getInvoiceDetail($id);
	}
	
	public function getInvoiceDetail($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 
					fd.id_factura_detalle,
					fd.id_producto,
					fd.cantidad,
					pr.nombre AS nombre_producto,
					pr.udm AS udm_producto,
					pd.precio AS precio_producto
				FROM facturas AS fa
				JOIN facturas_detalles AS fd ON fa.id_factura=fd.id_factura
				JOIN productos AS pr ON fd.id_producto=pr.id_producto
				JOIN pedidos AS pe ON fa.id_pedido=pe.id_pedido
				JOIN pedidos_detalles AS pd ON fa.id_pedido=pd.id_pedido AND pd.id_producto=fd.id_producto
				WHERE fa.id_factura = ' . $id;

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}	
	
	public function cancelar($id_factura)
	{	
		$factura = $this->getInvoice($id_factura);
		$id_pedido = $factura['id_pedido'];
		$id_sucursal = $factura['id_sucursal'];
	
		$id_factura = $this->db->escape(intval($id_factura));
		$id_pedido = $this->db->escape(intval($id_pedido));
		$id_sucursal = $this->db->escape(intval($id_sucursal));
		
		$this->db->trans_start();
		
		$sql = "SELECT * FROM facturas_detalles WHERE id_factura = $id_factura";
		$query = $this->db->query($sql);
		$detalles = $query->result_array();
		
		foreach ($detalles as $detalle) {
			$sql = "UPDATE pedidos_detalles
					SET cantidad_surtida = cantidad_surtida - {$detalle['cantidad']}
					WHERE id_producto = {$detalle['id_producto']} AND id_pedido = $id_pedido";
			$query = $this->db->query($sql);
			
			$sql = "INSERT INTO movimientos_inventario (id_documento, concepto, id_producto, cantidad, id_sucursal, fecha_mov, tipo_movimiento) VALUES ($id_factura, 'F', {$detalle['id_producto']}, {$detalle['cantidad']}, $id_sucursal, NOW(), 'E')";
			$query = $this->db->query($sql);
		}
		
		$sql = "UPDATE movimientos
				SET estatus = 'X', saldo = 0
				WHERE id_documento = $id_factura";
		$query = $this->db->query($sql);
		
		$sql = "UPDATE facturas
				SET estatus = 'X'
				WHERE id_factura = $id_factura";
		$query = $this->db->query($sql);
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === true) {
		    return true;
		}

		return false;
		
	}
	
	public function getReportData($branch, $client, $ini_date, $fin_date)
	{
		$branch = $this->db->escape(intval($branch));
		$ini_date = $this->db->escape($ini_date);
		$fin_date = $this->db->escape($fin_date);
		$client = $this->db->escape(intval($client));

		$sql = "SELECT 
					cl.nombre AS nombre_cliente,
					fp.prefijo, 
					fo.folio, 
					fa.fecha AS fecha_factura,
					fa.estatus,
					mo.importe
				FROM facturas AS fa
				JOIN pedidos AS pe ON pe.id_pedido=fa.id_pedido
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento='F'
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento='F'
				JOIN sucursales AS su ON fa.id_sucursal=su.id_sucursal
				JOIN movimientos AS mo ON mo.id_documento = fa.id_factura AND mo.id_documento
				WHERE fa.id_sucursal = $branch AND fa.fecha BETWEEN $ini_date AND $fin_date";
		
		if ($client != '0') {
			$sql .= "AND pe.id_cliente = $client";
		}

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}	
	
}