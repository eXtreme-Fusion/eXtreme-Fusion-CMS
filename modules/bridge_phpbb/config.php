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
	'title' => 'phpBB3 Bridge',
	'description' => 'Most łączący eXtreme-Fusion z forum phpBB3',
	'developer' => 'eXtreme Crew',
	'support' => 'http://www.extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'bridge_phpbb'
);

$new_table[1] = array(
	"bridge_phpbb",
	"(
		`status` MEDIUMINT NOT NULL DEFAULT '0',
		`prefix` VARCHAR(100) NOT NULL DEFAULT 'phpbb_'
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);

$new_row[1] = array(
	"bridge_phpbb", 
	"(`status`, `prefix`) VALUES (0, 'phpbb_')"
);

$drop_table[1] = "bridge_phpbb";

$admin_page[1] = array(
	'title' => 'phpBB3 Bridge',
	'image' => 'images/bridge.png',
	'page' => 'admin/bridge.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem'
);