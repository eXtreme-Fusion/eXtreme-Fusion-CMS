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
		if (isset(self::$shared[$name]))
		{
			return self::$shared[$name];
		}
		
		throw new argumentException('StaticContainer::get()');
	}
	
	function has($name)
	{
		return isset(self::$shared[$name]);
	}
}