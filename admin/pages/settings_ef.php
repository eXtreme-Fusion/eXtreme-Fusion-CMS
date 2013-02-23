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

	$_locale->load('settings_ef');

	if ( ! $_user->hasPermission('admin.settings_ef'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			'cookie_domain' => $_request->post('cookie_domain')->filters('trim', 'strip'),
			'cookie_patch' => $_request->post('cookie_patch')->filters('trim', 'strip'),
			'cookie_secure' => $_request->post('cookie_secure')->isNum(TRUE),
			'cache_active' => $_request->post('cache_active')->isNum(TRUE),
			'cache_expire' => $_request->post('cache_expire')->isNum() ? HELP::DayExport($_request->post('cache_expire')->show()) : $_sett->get('cache_expire')
		));
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		'cookie_domain' => $_sett->get('cookie_domain'),
		'cookie_patch' => $_sett->get('cookie_patch'),
		'cookie_secure' => $_sett->get('cookie_secure'),
		'cache_active' => $_sett->get('cache_active'),
		'cache_expire' => HELP::DayImport($_sett->get('cache_expire'))
	));

	$_tpl->template('settings_ef');
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
