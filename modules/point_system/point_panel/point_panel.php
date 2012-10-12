<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
$_locale->moduleLoad('point_panel', 'point_system');
$_head->set('<link href="'.ADDR_MODULES.'point_system'.DS.'templates'.DS.'stylesheet'.DS.'point_system.css" media="screen" rel="stylesheet" />');

$row = $_pdo->getRow('SELECT `id`, `username`, `role`, `points` FROM [users] WHERE `role` != 1 ORDER BY `points` DESC LIMIT 1');

if($row)
{
	$data = array(
		'id' => $row['id'],
		'username' => $_user->getUsername($row['id']),
		'link' => $_route->path(array('controller' => 'profile', 'action' => $row['id'], HELP::Title2Link($row['username']))),
		'role' => $_user->getRoleName($row['role']),
		'points' => $row['points'],
		'rank' => $_points->showRank($row['id']),
		'avatar' => $_user->getAvatarAddr($row['id'])
	);
}

$_panel->assign('user', $data);

$user = array();
$query = $_pdo->getData('SELECT `id`, `username`, `points` FROM [users] WHERE `role` != 1 ORDER BY `points` DESC, `username` ASC LIMIT 1,6');

$i = 2;
foreach($query as $row)
{
	$user[] = array(
		'id' => $row['id'],
		'username' => $_user->getUsername($row['id']),
		'link' => $_route->path(array('controller' => 'profile', 'action' => $row['id'], HELP::Title2Link($row['username']))),
		'points' => $row['points'],
		'i' => $i
	);
	
	$i++;
}

$_panel->assign('users', $user);