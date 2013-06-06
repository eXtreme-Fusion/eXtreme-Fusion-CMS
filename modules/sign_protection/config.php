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
	'title' => 'SignProtection&trade;',
	'description' => 'Zabezpieczenie formularzy przed spamem',
	'developer' => 'Clear-PHP',
	'support' => 'http://clear-php.com/',
	'version' => '1.0',
	'dir' => 'sign_protection',
	'category' => 'security'
);

$admin_page[1] = array(
	'title' => 'Sign Protection',
	'image' => 'images/protection.png',
	'page' => 'admin/sign_protection.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem SignProtection&trade;'
);

$new_table[1] = array(
	'sign_protection',
	"(
		`validation_type` TINYINT UNSIGNED NOT NULL DEFAULT '0'
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);
$new_row[1] = array(
	'sign_protection', 
	"(`validation_type`) VALUES (0)"
);

$drop_table[1] = 'sign_protection';