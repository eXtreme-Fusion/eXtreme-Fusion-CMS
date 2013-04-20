<?php
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
*********************************************************/
class StaticContainer
{
	protected static $shared = array();

	public static function register($name, $value)
	{
		self::$shared[$name] = $value;
	}

	public static function get($name, $default = 0)
	{
		if (isset(self::$shared[$name]))
		{
			return self::$shared[$name];
		}

		if ($default === 0)
		{
			throw new argumentException('StaticContainer::get()');
		}
		
		return $default;
	}

	public static function has($name)
	{
		return isset(self::$shared[$name]);
	}
}