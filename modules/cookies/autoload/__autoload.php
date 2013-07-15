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

// Pobranie z cache zapytania sprawdzającego czy moduł jest zainstalowany
$message = $_system->cache('cookies_msg', NULL, 'cookies', 2592000);

if ($message === NULL)
{
	// Pobieranie ustawień z bazy danych oraz umieszczenie go w cache
	$message = $_pdo->getField('SELECT `message` FROM [cookies]');
	$_system->cache('cookies_msg', $message, 'cookies');
}

if ($message)
{
	$_head->set('<script src="'.ADDR_SITE.'modules/cookies/templates/javascripts/cookies.js"></script>'.PHP_EOL.'	<script src="'.ADDR_COMMON_JS.'jquery.cookie.js"></script>'.PHP_EOL.'	<link href="'.ADDR_SITE.'modules/cookies/templates/stylesheets/cookies.css" rel="stylesheet">');
}