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
$_locale->moduleLoad('lang', 'user_info_panel');
$_head->set('<link href="'.ADDR_MODULES.'user_info_panel/templates/stylesheet/user_info_panel.css" rel="stylesheet">');

if ($_user->isLoggedIn())
{
	if ($_route->getParam('page'))
	{
		HELP::redirect(base64_decode($_route->getParam('page')));
	}

	$_panel->assign('is_logged_in', TRUE);
	$_panel->assign('avatar', $_user->getAvatarAddr());
	$_panel->assign('username', $_user->getUsername()); 

	$count = $_pdo->getMatchRowsCount('SELECT `id` FROM [messages] WHERE `to` = :id AND `read` = 0',
		array(
			array(':id', $_user->get('id'), PDO::PARAM_INT)
		)
	);

	if ($count)
	{
		$_panel->assign('messages', __('Unread messages: :msg', array(':msg' => $count)));
	}

	if ($_user->hasPermission('admin.login'))
	{
		$_panel->assign('is_admin', TRUE);
	}

	$_panel->assign('url_logout', $_route->path(array('controller' => 'account', 'action' => 'logout')));
	$_panel->assign('url_account', $_route->path(array('controller' => 'account')));
	$_panel->assign('url_messages', $_route->path(array('controller' => 'messages')));
	$_panel->assign('url_users', $_route->path(array('controller' => 'users')));
}
else
{
	if ($_sett->get('enable_registration'))
	{
		$_panel->assign('enable_reg', TRUE);

		$_panel->assign('url_register', $_route->path(array('controller' => 'register')));
		$_panel->assign('url_password', $_route->path(array('controller' => 'password')));
	}
}