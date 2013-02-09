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
	'title' => 'Kontakt',
	'description' => 'Formularze pozwalające kontaktować się ze stroną',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'contact'
);

$new_table[1] = array(
	"contact",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`email` VARCHAR(200) NOT NULL DEFAULT '',
		`title` VARCHAR(400) NOT NULL DEFAULT '',
		`description` TEXT NOT NULL DEFAULT '',
		`value` TEXT NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "contact";

$admin_page[1] = array(
	'title' => 'Kontakt',
	'image' => 'images/contact.png',
	'page' => 'admin/contact.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);

$menu_link[1] = array(
	'title' => 'Kontakt',
	'url' => 'contact',
	'visibility' => '3'
);