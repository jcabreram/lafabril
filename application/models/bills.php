<?php

class Bills extends CI_Model
{
	public function register($order, $date, $payments, $userId)
	{
		$this->load->model('orders');
		$this->load->model('folios');
		$this->load->model('clients');


		/*** TRANSACTION STARTS ***/
		$this->db->trans_start();
		/*** TRANSACTION STARTS ***/


		/*** INSERT BILL HEADER ***/
		$sql = "INSERT INTO notas_venta (id_nota_venta, id_pedido, fecha, estatus, iva, id_sucursal, fecha_captura, usuario_captura)
				VALUES (NULL, {$order['id_pedido']}, '$date', 'A', {$order['sucursal_iva']}, {$order['id_sucursal']}, NOW(), $userId)";
		$this->db->query($sql);
		/*** INSERT BILL HEADER ***/


		$billId = $this->db->insert_id();


		/*** INSERT BILL PRODUCTS AND UPDATE DELIVERED AMOUNT ***/
		$total = 0.0;

		foreach ($order['products'] as $product) {
			$sql = "INSERT INTO notas_venta_detalles (id_nota_venta_detalle, id_nota_venta, id_producto, cantidad)
					VALUES (NULL, $billId, {$product['id_producto']}, {$product['cantidad']})";
			$this->db->query($sql);
			
			$sql = "INSERT INTO movimientos_inventario (id_documento, concepto, id_producto, cantidad, id_sucursal, fecha_mov, tipo_movimiento)
					VALUES ($billId, 'N', {$product['id_producto']}, {$product['cantidad']}, {$order['id_sucursal']}, '$date', 'S')";
			$this->db->query($sql);

			$sql = "UPDATE pedidos_detalles SET cantidad_surtida = cantidad_surtida + {$product['cantidad']} WHERE id_pedido = {$order['id_pedido']} AND id_producto = {$product['id_producto']}";
			$this->db->query($sql);

			$total += $product['cantidad'] * $product['precio'];
		}
		/*** INSERT BILL PRODUCTS AND UPDATE DELIVERED AMOUNT ***/


		/*** GET LAST FOLIO NUMBER ***/
		$folioInformation = $this->folios->getLastFolio($order['id_sucursal'], 'N');
		$currentFolio = intval($folioInformation['ultimo_folio']) + 1;
		/*** GET LAST FOLIO NUMBER ***/


		/*** INSERT NEW FOLIO FOR INVOICE ***/
		$sql = "INSERT INTO folios (id, id_documento, id_sucursal, tipo_documento, folio)
				VALUES (NULL, $billId, {$order['id_sucursal']}, 'N', $currentFolio)";
		$this->db->query($sql);
		/*** INSERT NEW FOLIO FOR INVOICE ***/


		/*** UPDATE LAST FOLIO INFORMATION ***/
		$sql = "UPDATE folios_prefijo SET ultimo_folio = $currentFolio WHERE tipo_documento = 'N' AND id_sucursal = {$order['id_sucursal']}";
		$this->db->query($sql);
		/*** UPDATE LAST FOLIO INFORMATION ***/


		/*** CLOSE ORDER IF NECESSARY ***/
		$closeOrder = true;
		$orderProducts = $this->orders->getOrderProducts($order['id_pedido']);

		foreach ($orderProducts as $product) {
			if ($product['cantidad'] !== $product['cantidad_surtida']) {
				$closeOrder = false;
				break;
			}
		}

		if ($closeOrder === true) {
			$sql = 'UPDATE pedidos SET estatus = "C" WHERE id_pedido = ' . $order['id_pedido'];
			$this->db->query($sql);
		}
		/*** CLOSE ORDER IF NECESSARY ***/


		/*** REGISTER PAYMENTS ***/
		$cash = $payments['cash'] === '' ? 0 : $payments['cash'];

		if ($cash > 0) {
			$sql = "INSERT INTO pagos_notas (id, nota_id, pago_tipo, cantidad)
					VALUES (NULL, $billId, 1, $cash)";
			$this->db->query($sql);
		}

		$cards = $payments['cards'];
		$bank = '';

		if (count($cards) > 0) {
			foreach ($cards as $cardBank => $cardInformation) {
				foreach ($cardInformation as $cardNumber => $paymentAmount) {
					$sql = "INSERT INTO pagos_notas (id, nota_id, pago_tipo, cantidad)
							VALUES (NULL, $billId, 2, $paymentAmount)";
					$this->db->query($sql);

					$paymentId = $this->db->insert_id();
					$cardNumberLast4Digits = substr($cardNumber, strlen($cardNumber) - 4, 4);

					$sql = "INSERT INTO pagos_tarjeta (id, tipo_documento, id_pago, banco, numero_tarjeta)
							VALUES (NULL, 'N', $paymentId, '$cardBank', '$cardNumberLast4Digits')";
					$this->db->query($sql);
				}
			}
		}

		$checks = $payments['checks'];
		$bank = '';

		if (count($checks) > 0) {
			foreach ($checks as $checkBank => $checkInformation) {
				foreach ($checkInformation as $checkNumber => $paymentAmount) {
					$sql = "INSERT INTO pagos_notas (id, nota_id, pago_tipo, cantidad)
							VALUES (NULL, $billId, 2, $paymentAmount)";
					$this->db->query($sql);

					$paymentId = $this->db->insert_id();

					$sql = "INSERT INTO pagos_tarjeta (id, tipo_documento, id_pago, banco, numero_tarjeta)
							VALUES (NULL, 'N', $paymentId, '$checkBank', '$checkNumber')";
					$this->db->query($sql);
				}
			}
		}
		/*** REGISTER PAYMENTS ***/


		/*** TRANSACTION FINISHES ***/
		$this->db->trans_complete();
		/*** TRANSACTION FINISHES ***/


		if ($this->db->trans_status() === true) {
		    return true;
		}

		return false;
	}
	
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