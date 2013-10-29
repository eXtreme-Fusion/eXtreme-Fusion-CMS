<?php defined('EF5_SYSTEM') || exit;
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
*********************************************************/

$mod_info = array(
	'title' => 'Download&trade;',
	'description' => 'Moduł downloadu dla systemu eXtreme-Fusion 5',
	'developer' => 'Rafał Krupiński',
	'support' => 'http://rafik.eu/',
	'version' => '1.0',
	'dir' => 'downloads'
);

$admin_page[1] = array(
	'title' => 'Download',
	'image' => 'templates/images/download.png',
	'page' => 'admin/download.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem Download&trade;'
);

$new_table[1] = array(
	"download_cat",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(100) NOT NULL DEFAULT '',
		`description` TEXT NOT NULL,
		`sorting` VARCHAR(50) NOT NULL DEFAULT '`title ASC',
		`access` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "download_cat";

$new_table[2] = array(
	"download",
	"(
		`id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`user` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
		`homepage` VARCHAR(100) NOT NULL DEFAULT '',
		`title` VARCHAR(100) NOT NULL DEFAULT '',
		`description_short` VARCHAR(500) NOT NULL,
		`description` TEXT NOT NULL,
		`sms_price` VARCHAR(100) NOT NULL DEFAULT '0.00',
		`sms_number` VARCHAR(100) NOT NULL DEFAULT '',
		`sms_code` VARCHAR(100) NOT NULL DEFAULT '',
		`sms_service` VARCHAR(100) NOT NULL DEFAULT 'sms',
		`sms_system` VARCHAR(100) NOT NULL DEFAULT 'DotPay',
		`sms_delete` TINYINT(1) NOT NULL DEFAULT '0',
		`image` VARCHAR(100) NOT NULL DEFAULT '',
		`image_thumb` VARCHAR(100) NOT NULL DEFAULT '',
		`url` VARCHAR(200) NOT NULL DEFAULT '',
		`file` VARCHAR(100) NOT NULL DEFAULT '',
		`cat` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
		`license` VARCHAR(50) NOT NULL DEFAULT '',
		`copyright` VARCHAR(250) NOT NULL DEFAULT '',
		`os` VARCHAR(50) NOT NULL DEFAULT '',
		`version` VARCHAR(20) NOT NULL DEFAULT '',
		`filesize` VARCHAR(20) NOT NULL DEFAULT '',
		`datestamp` INT(10) UNSIGNED NOT NULL DEFAULT '0',
		`count` INT(10) UNSIGNED NOT NULL DEFAULT '0',
		`allow_comments` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
		`allow_ratings` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`),
		KEY `datestamp` (`datestamp`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[2] = "download";

$new_table[3] = array(
	"download_settings",
	"(
		`key` VARCHAR(100) NOT NULL DEFAULT '',
		`value` text NOT NULL,
		PRIMARY KEY (`key`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[3] = "download_settings";

$new_row[1] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('types', '.zip,.rar,.tar,.bz2,.7z')"
);
$new_row[2] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('max_b', '10240000')"
);
$new_row[3] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('screen_max_w', '1024')"
);
$new_row[4] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('screen_max_h', '768')"
);
$new_row[5] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('screen_max_b', '10240000')"
);
$new_row[6] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('comments_enabled', '1')"
);
$new_row[7] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('ratings_enabled', '1')"
);
$new_row[8] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('screenshot', '1')"
);
$new_row[9] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('thumb_max_w', '100')"
);
$new_row[10] = array(
	'download_settings',
	"(`key`, `value`) VALUES ('thumb_max_h', '100')"
);

$menu_link[1] = array(
	'title'      => 'Download',
	'url'        => 'downloads',
	'visibility' => '3',
);

$perm[1] = array(
	'name' => 'admin.download',
	'desc' => 'Zarządzanie modułem downloadu'
);

$perm[2] = array(
	'name' => 'admin.download_cats',
	'desc' => 'Zarządzanie kategoriami modułu downloadu'
);

$perm[3] = array(
	'name' => 'admin.download_sett',
	'desc' => 'Zarządzanie ustawieniami modułu downloadu'
);