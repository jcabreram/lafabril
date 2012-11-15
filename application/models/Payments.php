<?php

class Payments extends CI_Model
{
	public function getPaymentTypes() {
	
		$sql = 'SELECT id_pago_tipo, nombre FROM pagos_tipo';
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
		
	}
	public function register ($id_sucursal, $id_cliente, $importe, $fecha, $id_tipo_pago, $usuario_captura) {
		
		$this->load->model('folios');
		
		$id_sucursal = $this->db->escape(intval($id_sucursal));
		$id_cliente = $this->db->escape(intval($id_cliente));
		$importe = $this->db->escape(floatval($importe));
		$fecha = $this->db->escape($fecha);
		$id_tipo_pago = $this->db->escape(intval($id_tipo_pago));
		$estatus = $this->db->escape('P');
		$usuario_captura = $this->db->escape(intval($usuario_captura));
		$tipo_documento = $this->db->escape('A');
		
		$fecha_captura = date("Y-m-d H:i:s");
		$fecha_captura = $this->db->escape($fecha_captura);
		
		$this->db->trans_start();
		
		// We get the last folio of the orders in that branch
		$ultimo_folio = $this->folios->getLastFolio($id_sucursal, 'A');
		$folio_actual = ++$ultimo_folio['ultimo_folio'];
		
		$sql = "INSERT INTO pagos_facturas (id_pago_factura, id_pago_tipo, importe, fecha, id_sucursal, id_cliente, estatus, fecha_captura, usuario_captura)
				VALUES (NULL, $id_tipo_pago, $importe, $fecha, $id_sucursal, $id_cliente, $estatus, $fecha_captura,  $usuario_captura)";
		
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
					pf.fecha, 
					pf.estatus,
					pf.importe,
					pt.nombre AS tipo_pago
				FROM pagos_facturas AS pf
				JOIN sucursales AS su ON pf.id_sucursal=su.id_sucursal
				JOIN clientes AS cl ON pf.id_cliente=cl.id_cliente
				JOIN folios AS fo ON pf.id_pago_factura=fo.id_documento AND fo.tipo_documento="A"
				JOIN folios_prefijo AS fp ON pf.id_sucursal=fp.id_sucursal AND fp.tipo_documento="A"
				JOIN pagos_tipo AS pt ON pf.id_pago_tipo = pt.id_pago_tipo
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
						mo.importe AS importe_factura,
						mo.saldo AS saldo_factura,
						pd.importe AS importe_pago
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
	
	public function addLine ($id_pago_factura, $id_factura, $pago) {
		$id_pago_factura = $this->db->escape(intval($id_pago_factura));
		$id_factura = $this->db->escape(intval($id_factura));
		$pago = $this->db->escape($pago);
		
		$this->db->trans_start();
		
		$sql = "SELECT * FROM pagos_facturas_detalles WHERE id_pago_factura = $id_pago_factura AND id_factura = $id_factura";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$sql = "UPDATE pagos_facturas_detalles SET importe = importe + $pago WHERE id_pago_factura = $id_pago_factura AND id_factura = $id_factura";
			$this->db->query($sql);	
			
		} else {
			$sql = "INSERT INTO pagos_facturas_detalles (id_pago_factura_detalle, id_pago_factura, id_factura, importe)
				VALUES (NULL, $id_pago_factura, $id_factura, $pago)";
			$this->db->query($sql);	
		}
		
		$sql = "UPDATE pagos_facturas SET estatus = 'A' WHERE id_pago_factura = $id_pago_factura";
		$this->db->query($sql);	
		
		$sql = "UPDATE movimientos SET saldo = saldo-$pago WHERE id_documento = $id_factura";
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