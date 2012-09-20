<?php

class UserBranches extends CI_Model
{
	public function getUserBranches($id)
	{
		$id = $this->db->escape(intval($id));
		
		$sql = 'SELECT us.id_sucursal FROM sucursales AS suc, usuarios_sucursales AS us WHERE us.id_usuario = ' . $id . ' AND us.id_sucursal = suc.id_sucursal';
		$query = $this->db->query($sql);
		
		return $query->result_array();
	}
	
	

}