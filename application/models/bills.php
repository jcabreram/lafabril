<?php

class Bills extends CI_Model
{
	
	public function getAll()
	{

		$sql = 'SELECT 
					nv.id_nota_venta, 
					fp.prefijo, 
					fo.folio, 
					su.nombre AS nombre_sucursal, 
					cl.nombre AS nombre_cliente, 
					nv.fecha AS fecha_nota_venta, 
					nv.estatus
				FROM notas_venta AS nv
				JOIN sucursales AS su ON nv.id_sucursal=su.id_sucursal
				JOIN pedidos AS pe ON pe.id_pedido=nv.id_pedido
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN folios AS fo ON nv.id_nota_venta=fo.id_documento AND fo.tipo_documento="N"
				JOIN folios_prefijo AS fp ON nv.id_sucursal=fp.id_sucursal AND fp.tipo_documento="N"
				ORDER BY fecha_nota_venta ASC';
				
		$query = $this->db->query($sql);
		
		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function getBill($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 
					nv.id_nota_venta,
					nv.id_pedido, 
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
					su.iva AS sucursal_iva
				FROM notas_venta AS nv
				JOIN pedidos AS pe ON pe.id_pedido=nv.id_pedido
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN folios_prefijo AS fp ON nv.id_sucursal=fp.id_sucursal AND fp.tipo_documento="N"
				JOIN folios AS fo ON nv.id_nota_venta=fo.id_documento AND fo.tipo_documento="N"
				JOIN sucursales AS su ON nv.id_sucursal=su.id_sucursal
				WHERE id_nota_venta = ' . $id;

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}	
	
	/*

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
	*/
	
}