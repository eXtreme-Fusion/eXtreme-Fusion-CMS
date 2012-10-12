<?php

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