<?php
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

// Pobranie Zasad cookies z cache
$policy = $_system->cache('cookies_policy', NULL, 'cookies', 2592000);

// Brak danych w cache
if ($policy === NULL)
{
	// Pobranie Zasad Cookies z bazy danych
	$policy = $_pdo->getField('SELECT `policy` FROM [cookies]');
	$_system->cache('cookies_policy', $policy, 'cookies');
}

// Sprawdzanie, czy dane istnieją w cache/bazie
if ($policy)
{
	$_tpl->assign('policy', $policy);

	// Definiowanie katalogu templatek modułu
	$_tpl->setPageCompileDir(DIR_MODULES.'cookies'.DS.'templates'.DS);
}
else
{
	$_route->trace(array('controller' => 'error', 'action' => 404));
}

