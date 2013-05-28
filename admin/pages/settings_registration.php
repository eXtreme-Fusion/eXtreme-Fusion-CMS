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
	
	$_locale->load('settings_registration');
	
	$_security = new Security($_pdo, $_request);

	if ( ! $_user->hasPermission('admin.registration'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		if ($_request->post('license_agreement')->show() === $_sett->get('license_agreement'))
		{
			$license_last_update = $_sett->get('license_agreement');
		}
		else
		{
			$license_last_update = time();
		}
		
		$_sett->update(array(
			'enable_registration' => $_request->post('enable_registration')->isNum(TRUE),
			'email_verification' => $_request->post('email_verification')->isNum(TRUE),
			'admin_activation' => $_request->post('admin_activation')->isNum(TRUE),
			'validation' => $_sett->serialize('validation', 'register', $_request->post('validation')->strip()),
			'login_method' => $_request->post('login_method')->strip(),
			'enable_terms' => $_request->post('enable_terms')->isNum(TRUE),
			'license_agreement' => $_request->post('license_agreement')->show(),
			'license_lastupdate' => $license_last_update
		));
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		'enable_registration' => $_sett->get('enable_registration'),
		'email_verification' => $_sett->get('email_verification'),
		'admin_activation' => $_sett->get('admin_activation'),
		'login_method' => $_sett->get('login_method'),
		'enable_terms' => $_sett->get('enable_terms'),
		'license_agreement' => $_sett->get('license_agreement'),
		'validation' => $_security->getModulesData($_sett->getUnserialized('validation', 'register'))
	));

	$_tpl->template('settings_registration');
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
