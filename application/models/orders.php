<?php

class Orders extends CI_Model
{
	public function register ($id_sucursal, $id_vendedor, $id_cliente, $fecha_pedido, $fecha_entrega, $estatus, $usuario_captura) {
		$id_sucursal = $this->db->escape(intval($id_sucursal));
		$id_vendedor = $this->db->escape(intval($id_vendedor));
		$id_cliente = $this->db->escape(intval($id_cliente));
		$fecha_pedido = $this->db->escape($fecha_pedido);
		$fecha_entrega = $this->db->escape($fecha_entrega);
		$estatus = $this->db->escape($estatus);
		$usuario_captura = $this->db->escape(intval($usuario_captura));
		
		$fecha_captura = date("Y-m-d H:i:s");
		$fecha_captura = $this->db->escape($fecha_captura);
		//$id_pedido = $this->db->insert_id();
		
		$sql = "INSERT INTO pedidos (id_pedido, id_sucursal, id_vendedor, id_cliente, fecha_pedido, fecha_entrega, estatus, fecha_captura, usuario_captura)
				VALUES (NULL, $id_sucursal, $id_vendedor, $id_cliente, $fecha_pedido, $fecha_entrega, $estatus, $fecha_captura, $usuario_captura)";
		
		if ($this->db->query($sql)) {
			return $this->db->insert_id();
		} else {
			return false;
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
		$id = $this->db->escape(intval($id));

		$sql = 'SELECT * FROM pedidos WHERE id_pedido = ' . $id;
		$query = $this->db->query($sql);

		// Returns the query result as a pure array, or an empty array when no result is produced.
		return $query->row_array();
	}	
	
	public function getOrderDetail($id) {
		$id = $this->db->escape(intval($id));

		$sql = "SELECT pd.*, productos.* FROM pedidos_detalles as pd INNER JOIN productos ON pd.id_producto=productos.id_producto WHERE id_pedido = $id";
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