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
	'title'       => 'Forum',
	'description' => 'Forum dyskusyjne dla Twojej strony internetowej',
	'developer'   => 'eXtreme-Crew',
	'support'     => 'http://extreme-fusion.org/',
	'version'     => '1.0',
	'dir'         => 'forum',
);

$new_table[1] = array(
	"boards",
	"(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(255) NOT NULL,
		`order` int(10) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[2] = array(
	"board_categories",
	"(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`board_id` int(11) NOT NULL,
		`title` varchar(255) NOT NULL,
		`description` text,
		`is_locked` tinyint(1) NOT NULL DEFAULT '0',
		`order` int(10) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[3] = array(
	"threads",
	"(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`category_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`title` varchar(255) NOT NULL,
		`is_pinned` tinyint(1) NOT NULL DEFAULT '0',
		`timestamp` int(10) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_table[4] = array(
	"entries",
	"(
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`thread_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`content` text NOT NULL,
		`is_main` tinyint(1) NOT NULL DEFAULT '0',
		`timestamp` int(10) NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_row[1] = array(
	'boards',
	"(`title`, `order`) VALUES ('Testowy dział', 1)"
);

$new_row[2] = array(
	'board_categories',
	"(`board_id`, `title`, `description`, `order`) VALUES (1, 'Kategoria', 'Forum dyskusyjne dzieli się na działy, natomiast działy na kategorie.', 1)"
);

$drop_table[1] = 'boards';
$drop_table[2] = 'board_categories';
$drop_table[3] = 'threads';
$drop_table[4] = 'entries';

$menu_link[1] = array(
	'title'      => 'Forum',
	'url'        => 'forum',
	'visibility' => '3',
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie forum dyskusyjnym'
);