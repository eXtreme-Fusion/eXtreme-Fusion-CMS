<?php

class StaticContainer
{
	protected static $shared = array();

	public static function register($name, $value)
	{
		self::$shared[$name] = $value;
	}

	public static function get($name)
	{
		if (isset(self::$shared[$name]))
		{
			return self::$shared[$name];
		}

		throw new argumentException('StaticContainer::get()');
	}

	public static function has($name)
	{
		return isset(self::$shared[$name]);
	}
}