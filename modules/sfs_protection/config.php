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
	'title' => 'SFSProtection&trade;',
	'description' => 'Zabezpieczenie formularzy przed spamem',
	'developer' => 'Rafał Krupiński',
	'support' => 'http://rafik.eu/',
	'version' => '1.0',
	'dir' => 'sfs_protection',
	'category' => 'security'
);

$admin_page[1] = array(
	'title' => 'SFS Protection',
	'image' => 'images/protection.png',
	'page' => 'admin/sfs_protection.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem SFSProtection&trade;'
);

$new_table[1] = array(
	"sfs_protection",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(100) NOT NULL DEFAULT '',
		`email` VARCHAR(100) NOT NULL DEFAULT '',
		`ip` VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
		`datestamp` INT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "sfs_protection";