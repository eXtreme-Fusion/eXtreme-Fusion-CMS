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
	'title' => __('Warnings'),
	'description' => __('Warning System Module'),
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'warnings',
	'development' => TRUE,
	'developmentMessage' => __('Modules under Development.')
);

$new_table[1] = array(
	"warnings",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`guilty` MEDIUMINT NOT NULL DEFAULT '0',
		`sender` MEDIUMINT NOT NULL DEFAULT '0',
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description` VARCHAR(400) NOT NULL DEFAULT '',
		`cat` MEDIUMINT NOT NULL DEFAULT '0',
		`datestamp` INT NOT NULL DEFAULT '0',
		`expiry` INT NOT NULL DEFAULT '0',
		`notification` TINYINT UNSIGNED NOT NULL DEFAULT '1',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"warnings_sett",
	"(
		`key` VARCHAR(100) NOT NULL DEFAULT '',
		`value` TEXT NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[3] = array(
	"warnings_cats",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description` VARCHAR(255) DEFAULT NULL,
		`period` INT NOT NULL DEFAULT '1',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[4] = array(
	"warnings_explanation",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`warnings` MEDIUMINT NOT NULL DEFAULT '0',
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description` VARCHAR(400) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$admin_page[1] = array(
	'title' => __('Warning'),
	'image' => 'templates/images/warnings.png',
	'page' => 'admin/warnings.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'sett',
	'desc' => __('Permisions to manage the settings in the warning.')
);

$perm[2] = array(
	'name' => 'admin',
	'desc' => __('Permission to manage warnings.')
);

$menu_link[1] = array(
	'title' => __('Warnings'),
	'url' => 'warning',
	'visibility' => '1'
);

$drop_table[1] = "warnings";
$drop_table[2] = "warnings_sett";
$drop_table[3] = "warnings_cats";
$drop_table[4] = "warnings_explanation";