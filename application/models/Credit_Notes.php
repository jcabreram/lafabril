<?php

class Credit_Notes extends CI_Model
{
	public function getAll()
	{
		$sql = 'SELECT 
					nc.id_nota_credito, 
					fp.prefijo, 
					fo.folio, 
					su.nombre AS nombre_sucursal, 
					cl.nombre AS nombre_cliente, 
					nc.fecha, 
					nc.estatus
				FROM notas_credito AS nc
				JOIN sucursales AS su ON nc.id_sucursal=su.id_sucursal
				JOIN clientes AS cl ON nc.id_cliente=cl.id_cliente
				JOIN folios AS fo ON nc.id_nota_credito=fo.id_documento AND fo.tipo_documento="B"
				JOIN folios_prefijo AS fp ON nc.id_sucursal=fp.id_sucursal AND fp.tipo_documento="B"
				ORDER BY nc.fecha ASC';
		$query = $this->db->query($sql);
		
		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();	
	}
	
	public function getCreditNote($id)
	{
		$sql = 'SELECT 
				nc.id_nota_credito AS id,
				nc.id_sucursal,
				su.nombre AS nombre_sucursal,
				nc.id_cliente,
				cl.nombre AS nombre_cliente,
				nc.fecha AS fecha,
				nc.estatus,
				nc.observaciones,
				nc.tipo,
				nc.usuario_captura,
				nc.fecha_captura
				FROM notas_credito AS nc
				JOIN sucursales AS su ON nc.id_sucursal = su.id_sucursal
				JOIN clientes AS cl ON nc.id_cliente = cl.id_cliente
				WHERE id_nota_credito = ' . $id;
				
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}

	public function getCreditNoteDetails($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 	ncd.id_nota_credito_detalle,
						fp.prefijo, 
						fo.folio, 
						fa.fecha,
						mo.importe AS importe_factura,
						mo.saldo AS saldo_factura,
						ncd.importe AS importe_nota_credito
				FROM notas_credito_detalles AS ncd
				JOIN notas_credito AS nc ON ncd.id_nota_credito=nc.id_nota_credito
				JOIN facturas as fa ON ncd.id_factura=fa.id_factura
				JOIN folios AS fo ON fa.id_factura=fo.id_documento AND fo.tipo_documento="F"
				JOIN folios_prefijo AS fp ON fa.id_sucursal=fp.id_sucursal AND fp.tipo_documento="F"
				JOIN movimientos AS mo ON fa.id_factura = mo.id_documento
				WHERE ncd.id_nota_credito = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function register($branch, $client, $date, $type, $observations, $user)
	{
		$this->load->model('folios');

		$branch = $this->db->escape(intval($branch));
		$client = $this->db->escape(intval($client));
		$date = $this->db->escape(convertToComputerDate($date));
		$type = $this->db->escape($type);
		$observations = $this->db->escape($observations);
		$user = $this->db->escape(intval($user));
		
		$this->db->trans_start();
		
		$sql = "INSERT INTO notas_credito (id_nota_credito, id_sucursal, id_cliente, fecha, estatus, observaciones, tipo, usuario_captura, fecha_captura)
				VALUES (NULL, $branch, $client, $date, 'P', $observations, $type, $user, NOW())";
		$this->db->query($sql);

		$creditNoteId = $this->db->insert_id();

		// We get the last folio of the orders in that branch
		$ultimo_folio = $this->folios->getLastFolio($branch, 'B');
		$folio_actual = $ultimo_folio['ultimo_folio'] + 1;

		// Insert the next folio for the document in the folios table
		$sql = "INSERT INTO folios (id, id_documento, id_sucursal, tipo_documento, folio)
					VALUES (NULL, $creditNoteId, $branch, 'B', $folio_actual)";		
		$this->db->query($sql);
		
		// Update the value of the last folio
		$sql = "UPDATE folios_prefijo SET ultimo_folio=$folio_actual WHERE tipo_documento = 'B' AND id_sucursal = $branch";
		$this->db->query($sql);
			
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === true) {
		    return $creditNoteId;
		}

		return false;
	}

	public function addLine ($creditNoteId, $invoiceId, $amount) {
		$creditNoteId = $this->db->escape(intval($creditNoteId));
		$invoiceId = $this->db->escape(intval($invoiceId));
		$amount = $this->db->escape($amount);
		
		$this->db->trans_start();
		
		$sql = "SELECT * FROM notas_credito_detalles WHERE id_nota_credito = $creditNoteId AND id_factura = $invoiceId";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			$sql = "UPDATE notas_credito_detalles SET importe = importe + $amount WHERE id_nota_credito = $creditNoteId AND id_factura = $invoiceId";
			$this->db->query($sql);	
			
		} else {
			$sql = "INSERT INTO notas_credito_detalles (id_nota_credito_detalle, id_nota_credito, id_factura, importe)
				VALUES (NULL, $creditNoteId, $invoiceId, $amount)";
			$this->db->query($sql);	
		}
		
		$sql = "UPDATE notas_credito SET estatus = 'A' WHERE id_nota_credito = $creditNoteId";
		$this->db->query($sql);	
		
		$sql = "UPDATE movimientos SET saldo = saldo-$amount WHERE id_documento = $invoiceId";
		$this->db->query($sql);
		
		$this->db->trans_complete();


		if ($this->db->trans_status() === true) {
		    return true;
		}

		return false;
	}

	public function eliminar($id)
	{
		$id = $this->db->escape(intval($id));
		
		$this->db->trans_start();
		
		$sql = "SELECT * FROM notas_credito_detalles WHERE id_nota_credito_detalle = $id";
		$query = $this->db->query($sql);
		$credito_detalle = $query->row_array();
		
		$sql = "UPDATE movimientos SET saldo=saldo+{$credito_detalle['importe']} WHERE id_documento = {$credito_detalle['id_factura']}";
		$this->db->query($sql);
		
		$sql = "DELETE FROM notas_credito_detalles WHERE id_nota_credito_detalle = $id";
		$this->db->query($sql);

		$this->db->trans_complete();


		if ($this->db->trans_status() === true) {
		    return true;
		}

		return false;
	}

	public function cancelar($creditNoteId)
	{
		$this->db->trans_start();
		
		$creditNoteId = $this->db->escape(intval($creditNoteId));
		
		$sql = "SELECT * FROM notas_credito_detalles WHERE id_nota_credito = $creditNoteId";
		$query = $this->db->query($sql);
		$detalles = $query->result_array();
		
		foreach ($detalles as $detalle) {
			$sql = "UPDATE movimientos SET saldo=saldo+{$detalle['importe']} WHERE id_documento={$detalle['id_factura']}";
			$this->db->query($sql);
		}

		$sql = "UPDATE notas_credito SET estatus='X' WHERE id_nota_credito = $creditNoteId";
		$this->db->query($sql);
		
		$this->db->trans_complete();

		if ($this->db->trans_status() === true) {
		    return true;
		 }
	}
}