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
	'title' => 'Biuletyn',
	'description' => 'Biuletyn informacyjny',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'newsletter'
);

$new_table[1] = array(
	"newsletter",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`email` VARCHAR(200) NOT NULL DEFAULT '',
		`ip` VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
		`datestamp` INT NOT NULL DEFAULT '0',
		`value` TEXT NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "newsletter";

$admin_page[1] = array(
	'title' => 'Biuletyn informacyjny',
	'image' => 'templates/images/newsletter.png',
	'page' => 'admin/newsletter.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarz¹dzanie modu³em Biuletyn informacyjny'
);