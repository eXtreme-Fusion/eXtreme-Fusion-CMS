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
$_locale->moduleLoad('lang', 'forum');



$theme = array(
	'Title' => __('Chat'),
	'Keys' => '',
	'Desc' => ''
);

$_tpl->assign('Theme', $theme);

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);


// Definiowanie katalogu templatek modu³u
$_tpl->setPageCompileDir(DIR_MODULES.'chat'.DS.'templates'.DS);
