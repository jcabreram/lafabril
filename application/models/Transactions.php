<?php

class Transactions extends CI_Model
{
	
	public function getPaymentsAndCreditNotes($invoice, $fecha)
	{
		$invoice = $this->db->escape(intval($invoice));
		$fecha = $this->db->escape($fecha);
		

		$sql = "SELECT
					fpn.prefijo AS prefijo_nota_credito, 
					fon.folio AS folio_nota_credito, 
					fpp.prefijo AS prefijo_pago, 
					fop.folio AS folio_pago,
					nc.fecha AS fecha_nota_credito,
					pf.fecha AS fecha_pago,
					ncd.importe AS importe_nota_credito,
					pfd.importe AS importe_pago
				FROM facturas AS fa
				LEFT JOIN notas_credito_detalles AS ncd ON fa.id_factura=ncd.id_factura
				LEFT JOIN pagos_facturas_detalles AS pfd ON fa.id_factura=pfd.id_factura
				LEFT JOIN notas_credito AS nc ON ncd.id_nota_credito=nc.id_nota_credito
				LEFT JOIN pagos_facturas AS pf ON pfd.id_pago_factura=pf.id_pago_factura
				LEFT JOIN folios_prefijo AS fpn ON nc.id_sucursal=fpn.id_sucursal AND fpn.tipo_documento='B'
				LEFT JOIN folios AS fon ON nc.id_nota_credito=fon.id_documento AND fon.tipo_documento='B'
				LEFT JOIN folios_prefijo AS fpp ON pf.id_sucursal=fpp.id_sucursal AND fpp.tipo_documento='A'
				LEFT JOIN folios AS fop ON pf.id_pago_factura=fop.id_documento AND fop.tipo_documento='A'
				WHERE fa.id_factura = $invoice nc.fecha <= $fecha AND pf.fecha <= $fecha";
				

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}	
	
}