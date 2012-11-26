<?php
if ( ! function_exists('optLocale'))
{
	function optLocale(optClass &$tpl, $key, array $values = array())
	{
		return __($key, $values);
	}
}