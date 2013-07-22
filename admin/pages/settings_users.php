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
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+------------------------------------------------------
| Author: Paul Beuk (muscapaul)
+------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+------------------------------------------------------*/
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	$_locale->load('settings_users');

	if ( ! $_user->hasPermission('admin.settings_users'))
	{
		throw new userException(__('Access denied'));
	}

	$_fav->setFavByLink('settings_users.php', $_user->get('id'));
	
	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		if ( ! $_request->post('enable_deactivation')->show())
		{
			$_pdo->exec('UPDATE [users] SET `status` = 0 WHERE `status` = 5');
		}

		$_sett->update(array(
			//'enable_deactivation' => $_request->post('enable_deactivation')->isNum(TRUE),
			//'deactivation_period' => $_request->post('deactivation_period')->isNum(TRUE),
			//'deactivation_response' => $_request->post('deactivation_pesponse')->isNum(TRUE),
			//'deactivation_action' => $_request->post('deactivation_action')->isNum(TRUE),
			//'hide_userprofiles' => $_request->post('hide_user_profiles')->isNum(TRUE),
			'avatar_filesize' => $_request->post('avatar_filesize')->isNum(TRUE)*1024,
			'avatar_width' => $_request->post('avatar_width')->isNum(TRUE),
			'avatar_height' => $_request->post('avatar_height')->isNum(TRUE),
			//'avatar_ratio' => $_request->post('avatar_ratio')->isNum(TRUE),
			//'userthemes' => $_request->post('user_themes')->isNum(TRUE),
			//'change_name' => $_request->post('change_name')->isNum(TRUE)
		));

		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		//'enable_deactivation' => $_sett->get('enable_deactivation'),
		//'deactivation_period' => $_sett->get('deactivation_period'),
		//'deactivation_pesponse' => $_sett->get('deactivation_response'),
		//'deactivation_action' => $_sett->get('deactivation_action'),
		//'hide_user_profiles' => $_sett->get('hide_userprofiles'),
		'avatar_filesize' => $_sett->get('avatar_filesize')/1024,
		'avatar_width' => $_sett->get('avatar_width'),
		'avatar_height' => $_sett->get('avatar_height'),
		//'avatar_ratio' => $_sett->get('avatar_ratio'),
		//'user_themes' => $_sett->get('userthemes'),
		//'change_name' => $_sett->get('change_name')
	));

	$_tpl->template('settings_users');
}
catch(optException $exception)
{
	optErrorHandler($exception);
}
catch(systemException $exception)
{
	systemErrorHandler($exception);
}
catch(userException $exception)
{
	userErrorHandler($exception);
}
catch(PDOException $exception)
{
	PDOErrorHandler($exception);
}
