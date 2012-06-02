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
	'title' => 'Google Analytics',
	'description' => 'Stats from Google Analytics',
	'developer' => 'eXtreme Crew',
	'support' => 'http://extreme-fusion.org',
	'version' => '1.0',
	'dir' => 'google_analytics'
);

$new_table[1] = array(
	"google_analytics_sett",
	"(
		`email` VARCHAR(100) NOT NULL DEFAULT '',
		`password` VARCHAR(100) NOT NULL DEFAULT '',
		`account_id` VARCHAR(100) NOT NULL DEFAULT '',
		`profile_id` VARCHAR(100) NOT NULL DEFAULT '',
		`status` SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1'
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "google_analytics_sett";

$new_row[1] = array(
	"google_analytics_sett", 
	"(`status`) VALUES (0)"
);

$admin_page[1] = array(
	'title' => 'Google Analytics',
	'image' => 'templates/images/google_analytics.png',
	'page' => 'admin/google_analytics.php',
	'perm' => 'sett'
);

$perm[1] = array(
	'name' => 'sett',
	'desc' => 'Zarządzanie modułem'
);

$perm[2] = array(
	'name' => 'preview',
	'desc' => 'Podgląd modułu'
);
