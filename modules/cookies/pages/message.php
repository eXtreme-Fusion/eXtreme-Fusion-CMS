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
require_once '../../../system/sitecore.php';

$_locale->moduleLoad('cookies', 'cookies');

// Pobranie komunikatu z cache
$message = $_system->cache('cookies_msg', NULL, 'cookies', 2592000);

// Brak danych w cache
if ($message === NULL)
{
	// Pobranie komunikatu z bazy
	$message = $_pdo->getField('SELECT `message` FROM [cookies]');
	$_system->cache('cookies_msg', $message, 'cookies');
}

// Sprawdzanie, czy dane istnieją w cache/bazie
if ($message)
{
	$_tpl = new General(dirname(__DIR__).DS.'templates'.DS);

	$_tpl->assign('message', $message);

	// Pobranie Zasad cookies z cache
	$policy = $_system->cache('cookies_policy', NULL, 'cookies', 2592000);

	if ($policy === NULL)
	{
		// Pobranie Zasad cookies z bazy
		$policy = $_pdo->getField('SELECT `policy` FROM [cookies]');
		$_system->cache('cookies_policy', $policy, 'cookies');
	}

	// Sprawdzanie, czy dane istnieją w cache/bazie
	if ($policy)
	{
		$_tpl->assign('policy', $policy);
	}

	$_tpl->template('message.tpl');
}