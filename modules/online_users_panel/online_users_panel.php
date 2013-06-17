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
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
$_locale->moduleLoad('lang', 'online_users_panel');

$data = array();

if ($users = $_user->getOnline())
{
	foreach ($users as $user)
	{
		$data[] = HELP::profileLink($user['username'], $user['id']);
	}

	$_panel->assign('online', $data);
}

$member = $_pdo->getRow('SELECT `id`, `username` FROM [users] WHERE `status` != 1 AND `status` !=2 ORDER BY `joined` DESC LIMIT 1');

$_panel->assignGroup(array(
	'members' => count($users),
	'guests'  => $_user->getGuests(),
	'total'   => number_format($_pdo->getMatchRowsCount('SELECT `id` FROM [users] WHERE status = 0')),
	'member'  => HELP::profileLink($member['username'], $member['id']),
));

if ($_sett->get('admin_activation') === '1' && $_user->hasPermission('admin.members'))
{
	$_panel->assign('inactive', $_pdo->getMatchRowsCount('SELECT `id` FROM [users] WHERE status<=2'));
}