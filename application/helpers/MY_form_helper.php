<?php

if ( ! function_exists('setSelect'))
{
	function setSelect($formValue, $value)
	{
		if ($formValue === $value) {
			return 'selected="selected"';
		}

		return '';
	}
}

if ( ! function_exists('setRadio'))
{
	function setRadio($formValue, $value)
	{
		if ($formValue === $value) {
			return 'checked="checked"';
		}

		return '';
	}
}