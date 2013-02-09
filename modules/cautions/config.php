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
	'title' => 'Ostrzeżenia',
	'description' => 'Umożliwia nadawanie ostrzeżeń użytkownikom',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'cautions'
);

$new_table[1] = array(
	"cautions",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id` MEDIUMINT NOT NULL DEFAULT '0',
		`admin_id` MEDIUMINT NOT NULL DEFAULT '0',
		`content` VARCHAR(400) NOT NULL,
		`date` INT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "cautions";

$admin_page[1] = array(
	'title' => 'Ostrzeżenia',
	'image' => 'images/cautions.png',
	'page' => 'admin/cautions.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);