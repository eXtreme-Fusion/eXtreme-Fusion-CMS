<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

#Database section										// You have to change parameters below:

$_dbconfig = array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'dbpass',
    'database' => 'dbname',
    'prefix' => '123ABC_',
    'charset' => 'utf-8',
    'version' => 'eXtreme-Fusion CMS - Ninja Edition'
);

define('COOKIE_PREFIX', 'extreme_ef26593_');
define('CACHE_PREFIX', 'extreme_9b39976_');

#Site settings

define('ADDR_SITE', 'http://localhost/ef/');			// Your site address
define('DIR_SITE', dirname(__FILE__).DS);

#Encryption

defined('CRYPT_KEY') || define('CRYPT_KEY', '94948054dae30a3bd3a64a8caddcb7af');
defined('CRYPT_IV') || define('CRYPT_IV', 'f76c3cbb');