<?php

function controller_name()
{
	$CI =& get_instance();
	$segments = explode('/', $CI->uri->uri_string());

	if (!empty($segments[0])) {
		return $segments[0];
	}

	return 'inicio';
}

function method_name()
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