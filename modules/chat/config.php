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
	'title' => 'Chat',
	'description' => 'Chat dla użytkowników strony',
	'developer' => 'eXtreme Crew',
	'support' => 'http://extreme-fusion.org',
	'version' => '1.0',
	'dir' => 'chat'
);

$new_table[1] = array(
	"chat_messages",
	"(
		`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
		`user_id` MEDIUMINT NOT NULL DEFAULT '0',
		`content` TEXT NOT NULL,
		`datestamp` INT NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"chat_settings",
	"(
		`refresh` SMALLINT NOT NULL DEFAULT '0',
		`life_messages` SMALLINT NOT NULL DEFAULT '0',
		`panel_limit` SMALLINT NOT NULL DEFAULT '0',
		`archive_limit` SMALLINT NOT NULL DEFAULT '0',
		PRIMARY KEY (`refresh`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$drop_table[1] = "chat_messages";
$drop_table[2] = "chat_settings";

$new_row[1] = array(
	"chat_settings", 
	"(`refresh`, `life_messages`, `panel_limit`, `archive_limit`) VALUES (5, 30, 15, 30)"
);

$admin_page[1] = array(
	'title' => 'Chat',
	'image' => 'images/chat.png',
	'page' => 'admin/chat.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);