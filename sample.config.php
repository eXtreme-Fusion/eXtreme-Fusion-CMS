<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This product is licensed under the BSD License.
| http://extreme-fusion.org/ef5/license/
***********************************************************/
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// Database

$_dbconfig = array(
	'host'     => 'localhost',
	'port'     => '3306',
	'user'     => 'root',
	'password' => '',
	'database' => 'ef',
	'prefix'   => 'extreme_99939b3_',
	'charset'  => 'utf8',
	'version'  => 'eXtreme-Fusion CMS - Ninja Edition',
);

// Cookie && cache

defined('COOKIE_PREFIX') || define('COOKIE_PREFIX', 'extreme_5dd6cc4_');
defined('CACHE_PREFIX') || define('CACHE_PREFIX', 'extreme_16bbe3e_');

// Main path && site address

defined('DIR_SITE') || define('DIR_SITE', dirname(__FILE__).DS);
defined('ADDR_SITE') || define('ADDR_SITE', 'http://localhost/extreme/cms_5/');

// Encryption

defined('CRYPT_KEY') || define('CRYPT_KEY', '741e2fbc5191d4bb5a6e7f3d8af70add');
defined('CRYPT_IV') || define('CRYPT_IV', '0ad55a45');