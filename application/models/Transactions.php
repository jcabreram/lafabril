<?php

class Transactions extends CI_Model
{
	
	public function getReportData($branch, $from_client, $to_client, $fecha)
	{
		$branch = $this->db->escape(intval($branch));
		$from_client = $this->db->escape(intval($from_client));
		$to_client = $this->db->escape(intval($to_client));
		$fecha = $this->db->escape($fecha);
		

		$sql = "SELECT
					fp.prefijo AS prefijo_factura, 
					fo.folio AS folio_factura, 
					fa.fecha AS fecha_factura,
					fa.estatus,
					mo.importe AS importe_factura,
					mo.fecha_vencimiento,
					mo.saldo AS saldo_factura,
					cl.nombre AS nombre_cliente,
					su.nombre AS nombre_sucursal,
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
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento='F'
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento='F'
				LEFT JOIN folios_prefijo AS fpn ON nc.id_sucursal=fpn.id_sucursal AND fpn.tipo_documento='N'
				LEFT JOIN folios AS fon ON nc.id_nota_credito=fon.id_documento AND fon.tipo_documento='N'
				LEFT JOIN folios_prefijo AS fpp ON pf.id_sucursal=fpp.id_sucursal AND fpp.tipo_documento='A'
				LEFT JOIN folios AS fop ON pf.id_pago_factura=fop.id_documento AND fop.tipo_documento='A'
				JOIN sucursales AS su ON fa.id_sucursal=su.id_sucursal
				JOIN movimientos AS mo ON mo.id_documento = fa.id_factura
				JOIN clientes AS cl ON mo.id_cliente=cl.id_cliente
				WHERE fa.id_sucursal = $branch AND fa.estatus != 'X' AND fa.fecha < $fecha AND cl.id_cliente BETWEEN $from_client AND $to_client";

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}	
	
}