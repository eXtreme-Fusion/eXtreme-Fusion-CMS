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
class Server
{
	private static $php_version = '5.2.2';

	public static function getRequiredPHPVersion()
	{
		return self::$php_version;
	}

	public static function getPHPVersionID()
	{
		// PHP_VERSION_ID is available as of PHP 5.2.7, if our
		// version is lower than that, then emulate it

		if (!defined('PHP_VERSION_ID')) {

			$version = explode('.', PHP_VERSION);
			define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
		}

		return PHP_VERSION_ID;
	}

	public static function createPHPVersionId($version)
	{
		$version = explode('.', $version);

		return $version[0] * 10000 + $version[1] * 100 + $version[2];
	}
}