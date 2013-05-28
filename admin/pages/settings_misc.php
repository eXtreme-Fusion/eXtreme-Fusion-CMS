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
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->load('settings_misc');

	if ( ! $_user->hasPermission('admin.settings_misc'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			//'tinymce_enabled' => $_request->post('tinymce_enabled')->isNum(TRUE),
			'smtp_host' => $_request->post('smtp_host')->strip(),
			'smtp_port' => $_request->post('smtp_port')->isNum(TRUE),
			'smtp_username' => $_request->post('smtp_username')->strip(),
			'smtp_password' => $_request->post('smtp_password')->strip(),
			//'comments_enabled' => $_request->post('comments_enabled')->isNum(TRUE),
			//'ratings_enabled' => $_request->post('ratings_enabled')->isNum(TRUE),
			'visits_counter_enabled' => $_request->post('visits_counter_enabled')->isNum(TRUE),
		));
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		//'tinymce_enabled' => $_sett->get('tinymce_enabled'),
		'smtp_host' => $_sett->get('smtp_host'),
		'smtp_port' => $_sett->get('smtp_port'),
		'smtp_username' => $_sett->get('smtp_username'),
		'smtp_password' => $_sett->get('smtp_password'),
		//'comments_enabled' => $_sett->get('comments_enabled'),
		//'ratings_enabled' => $_sett->get('ratings_enabled'),
		'visits_counter_enabled' => $_sett->get('visits_counter_enabled')
	));

	$_tpl->template('settings_misc');
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
