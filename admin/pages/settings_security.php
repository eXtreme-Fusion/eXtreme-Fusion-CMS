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

	$_locale->load('settings_security');

	if ( ! $_user->hasPermission('admin.security'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			//'flood_interval' => $_request->post('flood_interval')->isNum(TRUE),
			//'flood_autoban' => $_request->post('flood_autoban')->isNum(TRUE),
			'bad_words_enabled' => $_request->post('bad_words_enabled')->isNum(TRUE),
			'bad_words' => $_request->post('bad_words')->strip(),
			'bad_word_replace' => $_request->post('bad_word_replace')->strip(),
			'maintenance_level' => $_request->post('maintenance_level')->getNumArray() ? HELP::implode($_request->post('maintenance_level')->show()) : $_sett->get('maintenance_level'),
			'maintenance' => $_request->post('maintenance')->isNum(TRUE),
			'maintenance_form' => $_request->post('maintenance_form')->isNum(TRUE),
			'maintenance_message' => $_request->post('maintenance_message')->show()
		));

		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		//'flood_interval' => $_sett->get('flood_interval'),
		//'flood_autoban' => $_sett->get('flood_autoban'),
		'bad_words_enabled' => $_sett->get('bad_words_enabled'),
		'bad_words' => $_sett->get('bad_words'),
		'bad_word_replace' => $_sett->get('bad_word_replace'),
		'maintenance_level' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($_sett->get('maintenance_level')), TRUE),
		'maintenance' => $_sett->get('maintenance'),
		'maintenance_form' => $_sett->get('maintenance_form'),
		'maintenance_message' => $_sett->get('maintenance_message')
	));

	$_tpl->template('settings_security');
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
