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
	'title' => 'Punktacja',
	'description' => 'Umożliwia rozdzielanie punktów użytkownikom',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'point_system',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$new_table[1] = array(
	"points",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`section` VARCHAR(64) NOT NULL DEFAULT '',
		`points` MEDIUMINT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"points_history",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
		`points` MEDIUMINT NOT NULL DEFAULT '0',
		`text` TEXT NOT NULL DEFAULT '',
		`date` INT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[3] = array(
	"ranks",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`ranks` VARCHAR(100) NOT NULL DEFAULT '',
		`points` MEDIUMINT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_row[1] = array(
	"ranks",
	"(`id`, `ranks`, `points`) VALUES ('1', 'User', '0')"
);

$new_row[2] = array(
	"points",
	"(`id`, `section`, `points`) VALUES ('1', 'News', '25')"
);

$add_field[1] = array(
	"users",
	"`points` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0'"
);

$drop_table[1] = "points";
$drop_table[2] = "points_history";
$drop_table[3] = "ranks";

$drop_field[1] = array(
	"users",
	"`points`"
);

$admin_page[1] = array(
	'title' => 'System punktów',
	'image' => 'images/ps.png',
	'page' => 'admin/point_system.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);

$menu_link[1] = array(
	'title' => 'Historia punktów',
	'url' => 'point_system',
	'visibility' => '2'
);