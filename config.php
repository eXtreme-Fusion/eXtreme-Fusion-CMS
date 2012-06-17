<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 The eXtreme-Fusion Crew                |
| http://extreme-fusion.org/                              	     |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

#Database

$_dbconfig = array(
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'root',
    'password' => 'root',
    'database' => 'ef',
    'prefix' => 'extreme_471e249_',
    'charset' => 'utf8',
    'version' => 'eXtreme-Fusion CMS - Ninja Edition'
);

#Cookie && cache

defined('COOKIE_PREFIX') || define('COOKIE_PREFIX', 'extreme_07a9dbd_');
defined('CACHE_PREFIX') || define('CACHE_PREFIX', 'extreme_09ff92c_');

#Main path && site address

defined('DIR_SITE') || define('DIR_SITE', dirname(__FILE__).DS);
defined('ADDR_SITE') || define('ADDR_SITE', 'http://rafik.net/efgit/');

#Encryption

defined('CRYPT_KEY') || define('CRYPT_KEY', '0cc16cd4b739cf70e8f137a7b21fd4e0');
defined('CRYPT_IV') || define('CRYPT_IV', 'ba56050f');