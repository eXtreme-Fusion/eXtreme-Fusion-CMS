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
$this->_locale->moduleLoad('cookies', 'cookies');

$mod_info = array(
	'title' => __('Cookies'),
	'description' => __('Module description'),
	'developer' => 'eXtreme-Fusion Crew',
	'support' => 'http://extreme-fusion.org/',
	'version' => '1.0',
	'dir' => 'cookies',
);

$admin_page[1] = array(
	'title' => __('Cookies'),
	'image' => 'templates/images/cookies.png',
	'page' => 'admin/cookies.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => __('Cookies page - perm description')
);

$new_table[1] = array(
	'cookies',
	"(
		`message` VARCHAR(255) NOT NULL,
		`policy` TEXT NOT NULL
	) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
);
$new_row[1] = array(
	'cookies',
	"(`message`) VALUES ('Strona ta korzysta z plików cookies w celu zapewnienia lepszej jakości usług.')"
);

$drop_table[1] = 'cookies';