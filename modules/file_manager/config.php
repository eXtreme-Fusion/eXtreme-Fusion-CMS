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
	'title' => 'File Manager',
	'description' => 'Przeglądanie i upload plików',
	'developer' => 'Logan Cai',
	'support' => 'http://phpletter.com',
	'version' => '1.0',
	'dir' => 'file_manager'
);

$admin_page[1] = array(
	'title' => 'File Manager',
	'image' => 'images/file_manager.png',
	'page' => 'admin/index.php?language='.__('xml_lang'),
	'perm' => 'admin'
);

$perm[1] = array(
	'name' => 'admin',
	'desc' => 'Zarządzanie modułem File Menager'
);