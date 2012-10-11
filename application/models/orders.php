<?php

class Orders extends CI_Model
{
	public function register ($id_sucursal, $id_vendedor, $id_cliente, $fecha_pedido, $fecha_entrega, $estatus, $usuario_captura)
	{	
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

	public function getOrder($id)
	{
		$order = array();

		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 	pedidos.id_pedido,
						sucursales.id_sucursal,
						sucursales.nombre AS sucursal_nombre,
						sucursales.iva AS sucursal_iva,
						vendedores.id_vendedor,
						empleados.nombre AS vendedor_nombre,
						clientes.id_cliente,
						clientes.nombre AS cliente_nombre,
						pedidos.fecha_pedido,
						pedidos.fecha_entrega,
						pedidos.estatus 
				FROM pedidos
				INNER JOIN sucursales ON pedidos.id_sucursal = sucursales.id_sucursal
				INNER JOIN vendedores ON pedidos.id_vendedor = vendedores.id_vendedor
				INNER JOIN empleados ON vendedores.id_empleado = empleados.id_empleado
				INNER JOIN clientes ON pedidos.id_cliente = clientes.id_cliente
				WHERE pedidos.id_pedido = ' . $id;

		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		$order = $query->row_array();

		if (count($order) > 0) {
			$order['products'] = $this->getOrderDetail($id);
		}
		
		return $order;
	}	
	
	public function getOrderDetail($id) {
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT 	pd.id_pedido_detalle,
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
	
}