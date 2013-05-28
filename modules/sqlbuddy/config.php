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
	'title' => 'SQL Buddy',
	'description' => 'Podsystem do zarządzania zawartością bazy danych',
	'developer' => 'Calvin Lough',
	'support' => 'http://sqlbuddy.com/',
	'version' => '1.3.3',
	'dir' => 'sqlbuddy'
);

$admin_page[1] = array(
	'title' => 'SQL Buddy',
	'image' => 'images/sqlbuddy.png',
	'page' => 'files/index.php',
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem SQL Buddy'
);