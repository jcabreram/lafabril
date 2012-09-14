<?php

if ( ! function_exists('controllerName'))
{
	function controllerName()
	{
		$CI =& get_instance();
		$segments = explode('/', $CI->uri->uri_string());

		if (!empty($segments[0])) {
			return $segments[0];
		}

		return 'inicio';
	}
}

if ( ! function_exists('methodName'))
{
	function methodName()
	{
		$CI =& get_instance();
		$segments = explode('/', $CI->uri->uri_string());

		if (count($segments) > 1) {
			return $segments[0] . '/' . $segments[1];
		}

		if (!empty($segments[0])) {
			return $segments[0] . '/index';
		}

		return 'inicio/index';
	}
}

if ( ! function_exists('getParameters'))
{
	function getParameters()
	{
		$CI =& get_instance();

		$parameters = array();
		$totalSegments = $CI->uri->total_segments();

		if ($totalSegments > 2) {
			$segments = explode('/', $CI->uri->uri_string());
			
			for ($i = 2; $i < $totalSegments; $i++) {
				$parameters[] = $segments[$i];
			}
		}

		if (count($parameters) > 0) {
			return '/' . implode('/', $parameters);
		}

		return '';
	}
}