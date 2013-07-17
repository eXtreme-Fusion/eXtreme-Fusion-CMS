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
	'image' => 'templates/images/cautions.png',
	'page' => 'admin/cautions.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);