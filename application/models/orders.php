<?php

class Orders extends CI_Model
{
	public function register ($id_sucursal, $id_vendedor, $id_cliente, $fecha_pedido, $fecha_entrega, $estatus, $usuario_captura) {
		
		$this->load->model('folios');
		
		$id_sucursal = $this->db->escape(intval($id_sucursal));
		$id_vendedor = $this->db->escape(intval($id_vendedor));
		$id_cliente = $this->db->escape(intval($id_cliente));
		$fecha_pedido = $this->db->escape($fecha_pedido);
		$fecha_entrega = $this->db->escape($fecha_entrega);
		$estatus = $this->db->escape($estatus);
		$usuario_captura = $this->db->escape(intval($usuario_captura));
		$tipo_documento = $this->db->escape('P');
		
		$fecha_captura = date("Y-m-d H:i:s");
		$fecha_captura = $this->db->escape($fecha_captura);
		$resultado = TRUE;
		
		// We get the last folio of the orders in that branch
		$ultimo_folio = $this->folios->getLastFolio($id_sucursal, 'P');
		$folio_actual = ++$ultimo_folio['ultimo_folio'];
		
		$sql = "INSERT INTO pedidos (id_pedido, id_sucursal, id_vendedor, id_cliente, fecha_pedido, fecha_entrega, estatus, fecha_captura, usuario_captura)
				VALUES (NULL, $id_sucursal, $id_vendedor, $id_cliente, $fecha_pedido, $fecha_entrega, $estatus, $fecha_captura, $usuario_captura)";
		
		if ($this->db->query($sql)) {
			$id_pedido = $this->db->insert_id();
		} else {
			$resultado = FALSE;
		}
		
		// Insert the next folio for the document in the folios table
		$sql = "INSERT INTO folios (id, id_documento, id_sucursal, tipo_documento, folio)
					VALUES (NULL, $id_pedido, $id_sucursal, $tipo_documento, $folio_actual)";
					
		if (!$this->db->query($sql))
			$resultado = FALSE;
		
		// Update the value of the last folio
		$sql = "UPDATE folios_prefijo SET ultimo_folio=$folio_actual WHERE tipo_documento = $tipo_documento AND id_sucursal = $id_sucursal";
		
		if (!$this->db->query($sql))
			$resultado = FALSE;
		
		// If there were no problems creating the order, return its ID. If there were, return false.
		if ($resultado) {
			return $id_pedido;
		} else {
			return FALSE;
		}
	}
	
	public function addLine ($id_pedido, $id_producto, $id_cantidad, $precio) {
		$id_pedido = $this->db->escape(intval($id_pedido));
		$id_producto = $this->db->escape(intval($id_producto));
		$precio = $this->db->escape($precio);
		
		$sql = "INSERT INTO pedidos_detalles (id_pedido_detalle, id_pedido, id_producto, cantidad, precio, cantidad_surtida)
				VALUES (NULL, $id_pedido, $id_producto, $id_cantidad, $precio, 0)";
		
		return $this->db->query($sql);
	}
	
	public function getAll($filters = false)
	{
		/*** PREPARE FILTERS ***/
		$branch = isset($filters['branch']) ? $filters['branch'] : false;
		$client = isset($filters['client']) ? $filters['client'] : false;
		/*** PREPARE FILTERS ***/

		$where = '';

		if ($branch !== false) {
			$where = 'WHERE pe.id_sucursal = ' . $this->db->escape(intval($branch));
		}

		if ($client !== false) {
			$where .= ' AND pe.id_cliente = ' . $this->db->escape(intval($client));
		}

		$sql = 'SELECT 
					pe.id_pedido, 
					fp.prefijo, 
					fo.folio, 
					su.nombre AS nombre_sucursal, 
					em.nombre AS nombre_vendedor, 
					cl.nombre AS nombre_cliente, 
					pe.fecha_pedido, 
					pe.estatus
				FROM pedidos AS pe
				JOIN sucursales AS su ON pe.id_sucursal=su.id_sucursal
				JOIN vendedores AS ve ON pe.id_vendedor=ve.id_vendedor
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN empleados AS em ON ve.id_empleado=em.id_empleado
				JOIN folios AS fo ON pe.id_pedido=fo.id_documento AND fo.tipo_documento="P"
				JOIN folios_prefijo AS fp ON pe.id_sucursal=fp.id_sucursal AND fp.tipo_documento="P"
				' . $where . '
				ORDER BY fecha_pedido ASC';
		$query = $this->db->query($sql);
		
		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
		
		
	}
	
	public function getOrder($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 
					pe.id_pedido, 
					fp.prefijo, 
					fo.folio, 
					pe.id_sucursal,
					su.nombre AS nombre_sucursal, 
					su.iva AS sucursal_iva,
					em.nombre AS nombre_vendedor, 
					pe.id_cliente,
					cl.nombre AS nombre_cliente, 
					pe.fecha_pedido, 
					pe.estatus
				FROM pedidos AS pe
				JOIN sucursales AS su ON pe.id_sucursal=su.id_sucursal
				JOIN vendedores AS ve ON pe.id_vendedor=ve.id_vendedor
				JOIN clientes AS cl ON pe.id_cliente=cl.id_cliente
				JOIN empleados AS em ON ve.id_empleado=em.id_empleado
				JOIN folios AS fo ON pe.id_pedido=fo.id_documento AND fo.tipo_documento="P"
				JOIN folios_prefijo AS fp ON pe.id_sucursal=fp.id_sucursal AND fp.tipo_documento="P"
				WHERE id_pedido = ' . $id;

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}	

	/**
	 * The same as getOrderDetail
	 */
	public function getOrderProducts($orderId)
	{
		return $this->getOrderDetail($orderId);
	}
	
	public function getOrderDetail($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 	pd.id_pedido_detalle,
						p.id_producto,
						p.nombre,
						pd.cantidad,
						p.udm,
						pd.precio,
						pd.cantidad_surtida
		FROM pedidos_detalles AS pd 
		INNER JOIN productos AS p ON pd.id_producto = p.id_producto
		WHERE pd.id_pedido = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->result_array();
	}
	
	public function eliminar($id)
	{
		$id = $this->db->escape(intval($id));

		$sql = "DELETE FROM pedidos_detalles WHERE id_pedido_detalle = $id";
		
		return $this->db->query($sql);
	}
	
	public function limpiar_vacias()
	{
	
		// Removes all the items in pedidos that have no corresponding id on pedidos_detalles and that have more than 2 hours of having been created
		$sql = "DELETE pedidos
				FROM pedidos
				LEFT JOIN pedidos_detalles
				ON pedidos.id_pedido=pedidos_detalles.id_pedido
				WHERE pedidos_detalles.id_pedido IS NULL AND (now() - pedidos.fecha_captura) > (120*60);";
				
		return $this->db->query($sql);
	}
	
	public function invoice($order, $products, $date, $userId)
	{
		$this->load->model('folios');
		$this->load->model('clients');


		/*** TRANSACTION STARTS ***/
		$this->db->trans_start();
		/*** TRANSACTION STARTS ***/


		/*** INSERT INVOICE HEADER ***/
		$sql = "INSERT INTO facturas (id_factura, id_pedido, fecha, estatus, iva, id_sucursal, fecha_captura, usuario_captura)
				VALUES (NULL, {$order['id_pedido']}, '$date', 'A', {$order['sucursal_iva']}, {$order['id_sucursal']}, NOW(), $userId)";
		$this->db->query($sql);
		/*** INSERT INVOICE HEADER ***/


		$invoiceId = $this->db->insert_id();


		/*** INSERT INVOICE PRODUCTS AND UPDATE DELIVERED AMOUNT ***/
		$total = 0.0;

		foreach ($products as $productId => $productInformation) {
			$sql = "INSERT INTO facturas_detalles (id_factura_detalle, id_factura, id_producto, cantidad)
					VALUES (NULL, $invoiceId, $productId, {$productInformation['amount']})";
			$this->db->query($sql);

			$sql = "UPDATE pedidos_detalles SET cantidad_surtida = {$productInformation['amount']} WHERE id_pedido = {$order['id_pedido']} AND id_producto = $productId";
			$this->db->query($sql);

			$total += $productInformation['amount'] * $productInformation['price'];
		}
		/*** INSERT INVOICE PRODUCTS AND UPDATE DELIVERED AMOUNT ***/


		/*** GET LAST FOLIO NUMBER ***/
		$folioInformation = $this->folios->getLastFolio($order['id_sucursal'], 'F');
		$currentFolio = intval($folioInformation['ultimo_folio']) + 1;
		/*** GET LAST FOLIO NUMBER ***/


		/*** INSERT NEW FOLIO FOR INVOICE ***/
		$sql = "INSERT INTO folios (id, id_documento, id_sucursal, tipo_documento, folio)
				VALUES (NULL, $invoiceId, {$order['id_sucursal']}, 'F', $currentFolio)";
		$this->db->query($sql);
		/*** INSERT NEW FOLIO FOR INVOICE ***/


		/*** UPDATE LAST FOLIO INFORMATION ***/
		$sql = "UPDATE folios_prefijo SET ultimo_folio = $currentFolio WHERE tipo_documento = 'F' AND id_sucursal = {$order['id_sucursal']}";
		$this->db->query($sql);
		/*** UPDATE LAST FOLIO INFORMATION ***/


		/*** CLOSE ORDER IF NECESSARY ***/
		$closeOrder = true;
		$orderProducts = $this->getOrderProducts($order['id_pedido']);

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


		$client = $this->clients->getClient($order['id_cliente']);
		$dueDate = strtotime("+{$client['dias_credito']} days", strtotime($date));
		$dueDate = date('Y-m-d', $dueDate);


		/*** REGISTER INVOICE IN ACCOUNTS RECEIVABLE ***/
		$sql = "INSERT INTO movimientos (id_movimiento, id_cliente, id_documento, importe, fecha_documento, fecha_vencimiento, saldo, id_sucursal)
				VALUES (NULL, {$order['id_cliente']}, $invoiceId, $total, '$date', '$dueDate', $total, {$order['id_sucursal']})";
		$this->db->query($sql);
		/*** REGISTER INVOICE IN ACCOUNTS RECEIVABLE ***/


		/*** TRANSACTION FINISHES ***/
		$this->db->trans_complete();
		/*** TRANSACTION FINISHES ***/


		if ($this->db->trans_status() === true) {
		    return true;
		}

		return false;
	}
}