<?php

if (!function_exists('getOrderStatusName')) {
	function getOrderStatusName($code) 
	{
		$name = 'Desconocido';

		switch ($code) {
			case 'A':
				$name = 'Abierto';
				break;

			case 'C':
				$name = 'Cerrado';
				break;

			case 'X':
				$name = 'Cancelado';
				break;
		}

		return $name;
	}
}

if (!function_exists('getFolio')) {
	function getFolio($prefix, $number)
	{
		return $prefix . str_pad($number, 9, '0', STR_PAD_LEFT);
	}
}

if (!function_exists('getMoneyFormat')) {
	function getMoneyFormat($money)
	{
		return number_format($money, 2, '.', ',');
	}
}