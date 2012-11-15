<?php

class Payments extends CI_Model
{
	public function getPaymentTypes() {
	
		$sql = 'SELECT id_pago_tipo, nombre FROM pagos_tipo';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
		
	}
	public function register ($id_sucursal, $id_cliente, $importe, $fecha, $id_tipo_pago, $usuario_captura, $id_moneda) {
		
		$this->load->model('folios');
		
		$id_sucursal = $this->db->escape(intval($id_sucursal));
		$id_cliente = $this->db->escape(intval($id_cliente));
		$importe = $this->db->escape(floatval($importe));
		$fecha = $this->db->escape($fecha);
		$id_tipo_pago = $this->db->escape(intval($id_tipo_pago));
		$estatus = $this->db->escape('P');
		$usuario_captura = $this->db->escape(intval($usuario_captura));
		$id_moneda = $this->db->escape(intval($id_moneda));
		$tipo_documento = $this->db->escape('A');
		
		$fecha_captura = date("Y-m-d H:i:s");
		$fecha_captura = $this->db->escape($fecha_captura);
		
		$this->db->trans_start();
		
		// We get the last folio of the orders in that branch
		$ultimo_folio = $this->folios->getLastFolio($id_sucursal, 'A');
		$folio_actual = ++$ultimo_folio['ultimo_folio'];
		
		$sql = "INSERT INTO pagos_facturas (id_pago_factura, id_pago_tipo, id_moneda, importe, fecha, id_sucursal, id_cliente, estatus, fecha_captura, usuario_captura)
				VALUES (NULL, $id_tipo_pago, $id_moneda, $importe, $fecha, $id_sucursal, $id_cliente, $estatus, $fecha_captura,  $usuario_captura)";
		
		$this->db->query($sql);
		$id_pago_factura = $this->db->insert_id();
		
		// Insert the next folio for the document in the folios table
		$sql = "INSERT INTO folios (id, id_documento, id_sucursal, tipo_documento, folio)
					VALUES (NULL, $id_pago_factura, $id_sucursal, $tipo_documento, $folio_actual)";
					
		$this->db->query($sql);
		
		// Update the value of the last folio
		$sql = "UPDATE folios_prefijo SET ultimo_folio=$folio_actual WHERE tipo_documento = $tipo_documento AND id_sucursal = $id_sucursal";
		
		$this->db->query($sql);
			
		/*** TRANSACTION FINISHES ***/
		$this->db->trans_complete();
		/*** TRANSACTION FINISHES ***/
		
		if ($this->db->trans_status() === true) {
		    return $id_pago_factura;
		}

		return false;
	}
	
	public function getPayment($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 
					pf.id_pago_factura, 
					fp.prefijo, 
					fo.folio, 
					pf.id_sucursal,
					su.nombre AS nombre_sucursal, 
					pf.id_cliente,
					cl.nombre AS nombre_cliente,
					mo.nombre, 
					pf.fecha, 
					pf.estatus,
					pf.importe
				FROM pagos_facturas AS pf
				JOIN sucursales AS su ON pf.id_sucursal=su.id_sucursal
				JOIN clientes AS cl ON pf.id_cliente=cl.id_cliente
				JOIN folios AS fo ON pf.id_pago_factura=fo.id_documento AND fo.tipo_documento="A"
				JOIN folios_prefijo AS fp ON pf.id_sucursal=fp.id_sucursal AND fp.tipo_documento="A"
				JOIN monedas AS mo ON pf.id_moneda = mo.id_moneda
				WHERE id_pago_factura = ' . $id;

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}	
	
	public function getPaymentDetails($id) {
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 	pd.id_pago_factura_detalle,
						fp.prefijo, 
						fo.folio, 
						fa.fecha,
						mo.importe,
						mo.saldo
				FROM pagos_facturas_detalles AS pd
				JOIN pagos_facturas AS pf ON pd.id_pago_factura=pf.id_pago_factura
				JOIN facturas as fa ON pd.id_factura=fa.id_factura
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento="F"
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento="F"
				JOIN movimientos AS mo ON fa.id_factura = mo.id_documento
				WHERE pf.id_pago_factura = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function addLine ($id_pedido, $id_producto, $cantidad, $precio) {
		$id_pedido = $this->db->escape(intval($id_pedido));
		$id_producto = $this->db->escape(intval($id_producto));
		$precio = $this->db->escape($precio);
		
		$this->db->trans_start();
		
		$sql = "SELECT * FROM pedidos_detalles WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$sql = "UPDATE pedidos_detalles SET cantidad = cantidad + $cantidad WHERE id_pedido = $id_pedido AND id_producto = $id_producto";
			$this->db->query($sql);	
			
		} else {
			$sql = "INSERT INTO pedidos_detalles (id_pedido_detalle, id_pedido, id_producto, cantidad, precio, cantidad_surtida)
				VALUES (NULL, $id_pedido, $id_producto, $cantidad, $precio, 0)";
			$this->db->query($sql);	
		}
		
		$sql = "UPDATE pedidos SET estatus = 'A' WHERE id_pedido = $id_pedido";
		$this->db->query($sql);	
		
		/*** TRANSACTION FINISHES ***/
		$this->db->trans_complete();
		/*** TRANSACTION FINISHES ***/


		if ($this->db->trans_status() === true) {
		    return true;
		}

		return false;
	}
	
}