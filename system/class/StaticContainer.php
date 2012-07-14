<?php



class StaticContainer
{
	protected static $shared = array();
	
	function register($name, $value)
	{
		self::$shared[$name] = $value;
	}

	function get($name)
	{
		return self::$shared[$name];
	}
}