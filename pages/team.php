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
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$_locale->load('team');
$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/team.css" media="screen" rel="stylesheet" />');

// TO DO Będziesz dodawać cache dla tej strony użyj: $_sett->getUns('cache', 'expire_team') //

$theme = array(
	'Title' => __('Site Team').' &raquo; '.$_sett->get('site_name'),
	'Keys' => 'Ekipa '.$_sett->get('site_name').', skład, właściciele',
	'Desc' => 'Lista osób odpowiedzialnych za działanie i funkcjonowanie strony.'
);
// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

// Site Admin
$row = $_pdo->getRow('SELECT * FROM [users] WHERE `id` = 1');

	if(time() <= $row['lastvisit']+300)
	{
		$last_visit = 1;
	}
	else
	{
		$last_visit = HELP::showDate('shortdate', $row['lastvisit']);
	}

	$admin = array(
		'id' => $row['id'],
		'username' => $_user->getUsername($row['id']),
		'role' => $_user->getRoleName($row['role']),
		'roles' => implode(', ', $_user->getUserRolesTitle($row['id'], 3)),
		'avatar' => $row['avatar'],
		'joined' => HELP::showDate('shortdate', $row['joined']),
		'last_visit' => $last_visit,
		'link' => HELP::profileLink($row['username'], $row['id']),
		'last_visit_time' => ($row['lastvisit'] != 0 ? HELP::showDate('shortdate', $row['lastvisit']) : __('Nie był na stronie')),
		'is_online' => inArray($row['id'], $_user->getOnline(), 'id') ? 1 : 0,
	);

$_tpl->assign('site_admin', $admin);

// Admins
$query = $_pdo->getData('SELECT * FROM [users] WHERE `id` != 1 AND `role` = 1');

if ($_pdo->getRowsCount($query))
{
	foreach($query as $row)
	{
		if(time() <= $row['lastvisit']+300)
		{
			$last_visit = 1;
		}
		else
		{
			$last_visit = HELP::showDate('shortdate', $row['lastvisit']);
		}

		$data[] = array(
			'id' => $row['id'],
			'username' => $_user->getUsername($row['id']),
			'role' => $_user->getRoleName($row['role']),
			'roles' => implode(', ', $_user->getUserRolesTitle($row['id'], 3)),
			'avatar' => $row['avatar'],
			'joined' => HELP::showDate('shortdate', $row['joined']),
			'last_visit' => $last_visit,
			'link' => HELP::profileLink($row['username'], $row['id']),
			'last_visit_time' => ($row['lastvisit'] != 0 ? HELP::showDate('shortdate', $row['lastvisit']) : __('Nie był na stronie')),
			'is_online' => inArray($row['id'], $_user->getOnline(), 'id') ? 1 : 0,
		);
	}

	$_tpl->assign('admin', $data);
}

// Other groups

$groups = $_pdo->getData('SELECT * FROM [groups] WHERE `id` != 1 AND `team` = 1');

// todo: dodać cache, przerobić w bazie roles na po przecinku zamiast serialize, wtedy nie trzeba bedzie pobierać tylu rekordów
$users = $_pdo->getData('SELECT `id`, `roles`, `role`, `joined`, `avatar`, `lastvisit`, `username` FROM [users] WHERE `status` = 0');

$roles = array();
foreach($users as $user)
{
	foreach(unserialize($user['roles']) as $role)
	{
		$roles[$role][] = $user;
	}
}

if($groups)
{
	$all_users = array();

	foreach($groups as $key => $group)
	{
		if (isset($roles[$group['id']]))
		{
			foreach($roles[$group['id']] as $user)
			{
				if(time() <= $user['lastvisit']+300)
				{
					$last_visit = 1;
				}
				else
				{
					$last_visit = HELP::showDate('shortdate', $user['lastvisit']);
				}

				$all_users[$key][] = array(
					'id' => $user['id'],
					'username' => $_user->getUsername($user['id']),
					'role' => $_user->getRoleName($user['role']),
					'roles' => implode(', ', $_user->getUserRolesTitle($user['id'], 3)),
					'avatar' => $user['avatar'],
					'joined' => HELP::showDate('shortdate', $user['joined']),
					'last_visit' => $last_visit,
					'link' => HELP::profileLink($user['username'], $user['id']),
					'last_visit_time' => ($user['lastvisit'] != 0 ? HELP::showDate('shortdate', $user['lastvisit']) : __('Nie był na stronie')),
					'is_online' => inArray($user['id'], $_user->getOnline(), 'id') ? 1 : 0
				);
			}
		}
	}

	$_tpl->assign('users', $all_users);
	$_tpl->assign('groups', $groups);
}