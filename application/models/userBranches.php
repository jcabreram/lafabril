<?php

class UserBranches extends CI_Model
{
	public function getUserBranches($id)
	{
		$id = $this->db->escape(intval($id));
		
		$sql = 'SELECT suc.nombre FROM sucursales AS suc, usuarios_sucursales AS us WHERE us.id_usuario = ' . $id . ' AND us.`id_sucursal` = suc.id_sucursal';
		$query = $this->db->query($sql);
		
		return $query->row_array();
	}
	
	

}