<?php

if (!function_exists('getStatusName')) {
	function getStatusName($code) 
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
				
			case 'P':
				$name = 'Pendiente';
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

/*
 * Converts date from yyyy-mm-dd format to dd/mm/yyyy
 * In case of receive an invalid date, returns false
 */
if (!function_exists('convertToHumanDate')) {
	function convertToHumanDate($date)
	{
		$oldDate = $date;
		$date = explode('-', $date);
		
		if (count($date) === 3 && checkdate($date[1], $date[2], $date[0])) {
			return $date[2] . '/' . $date[1] . '/' . $date[0];
		}
		
		return false;
	}
}

/*
 * Converts date from dd/mm/yyyy format to yyyy-mm-dd
 * In case of receive an invalid date, returns false
 */
if (!function_exists('convertToComputerDate')) {
	function convertToComputerDate($date)
	{
		$oldDate = $date;
		$date = explode('/', $date);
		
		if (count($date) === 3 && checkdate($date[1], $date[0], $date[2])) {
			return $date[2] . '-' . $date[1] . '-' . $date[0];
		}
		
		return false;
	}
}