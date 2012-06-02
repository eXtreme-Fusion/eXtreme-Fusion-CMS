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
$_locale->moduleLoad('lang', 'user_info_panel');

if ($_user->isLoggedIn())
{
	if ($_route->getParam('page'))
	{
		HELP::redirect(base64_decode($_route->getParam('page')));
	}
	
	$_panel->assign('is_logged_in', TRUE);

	$count = $_pdo->getMatchRowsCount('SELECT `id` FROM [messages] WHERE `to` = :id AND `read` = 0',
		array(
			array(':id', $_user->get('id'), PDO::PARAM_INT)
		)
	);
	
	if ($count)
	{
		$_panel->assign('messages', __('Nieprzeczytanych wiadomości: :msg', array(':msg' => $count)));
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