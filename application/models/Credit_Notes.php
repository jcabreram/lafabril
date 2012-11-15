<?php

class Credit_Notes extends CI_Model
{
	public function getAll()
	{
		
		
	}
	
	public function register($branch, $client, $date, $type, $observations, $user)
	{
		$branch = $this->db->escape(intval($branch));
		$client = $this->db->escape(intval($client));
		$date = $this->db->escape(convertToComputerDate($date));
		$type = $this->db->escape($type);
		$observations = $this->db->escape($observations);
		$user = $this->db->escape(intval($user));
		
		
		$sql = "INSERT INTO notas_credito (id_nota_credito, id_sucursal, id_cliente, fecha, estatus, observaciones, tipo, usuario_captura, fecha_captura)
				VALUES (NULL, $branch, $client, $date, 'P', $observations, $type, $user, NOW())";
		
		if ($this->db->query($sql)) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}
}