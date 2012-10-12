<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/

$mod_info = array(
	'title' => 'SignProtection&trade;',
	'description' => 'Zabezpieczenie formularzy przed spamem',
	'developer' => 'Clear-PHP',
	'support' => 'http://clear-php.com/',
	'version' => '1.0',
	'dir' => 'sign_protection',
	'category' => 'security'
);

$admin_page[1] = array(
	'title' => 'Sign Protection',
	'image' => 'images/protection.png',
	'page' => 'admin/sign_protection.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem SignProtection&trade;'
);

$new_table[1] = array(
	"sign_protection",
	"(
		`validation_type` TINYINT UNSIGNED NOT NULL DEFAULT '0'
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);
$new_row[1] = array(
	"sign_protection", 
	"(`validation_type`) VALUES ('0')"
);

$drop_table[1] = "sign_protection";