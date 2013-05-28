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
	
	$_locale->load('settings_logs');

	if ( ! $_user->hasPermission('admin.settings-logs'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	// Czy mam zapisać dane z formularza?
	if ($_request->post('save')->show())
	{
		$active = NULL;

		//Czy Rejestr zadzarzeń jest wyłączony i użytkownik próbuje go włączyć?
		if ( ! $_sett->get('logger_active') && $_request->post('active')->show())
		{
			$_sett->update(array(
				'logger_active' => '1'
			));

			$active = 1;
		}
		// A może Rejestr zdarzeń jest włączony a użytkownik próbuje go wyłaczyć?
		else if ($_sett->get('logger_active') && ! $_request->post('active')->show())
		{
			$error = ! $_sett->update(array(
				'logger_active' => '0'
			));

			$active = 0;
		}

		// Czy pakiet optymalizacji ma zostać włączony?
		if ($_request->post('optimize_active')->show())
		{
			$_sett->update(array(
				'logger_optimize_active' => '1',
				'logger_expire_days' => $_request->post('expire_days')->isNum(TRUE)
			));
		}
		else
		{
			$_sett->update(array(
				'logger_optimize_active' => '0'
			));
		}

		$_sett->update(array(
			'logger_save_removal_action' => $_request->post('save_removal_action')->show() === '1' ? '1' : '0'
		));

		if ($active === 1)
		{
			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Logs has been enabled.')));
		}
		else if ($active === 0)
		{
			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Logs has been disabled.')));
		}
		else
		{
			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
		}
	}

	// Prześlę dane z bazy do Systemu szablonów
	$_tpl->assignGroup(array(
		'active' => $_sett->get('logger_active'),
		'optimize_active' => $_sett->get('logger_optimize_active'),
		'expire_days' => $_sett->get('logger_expire_days'),
		'save_removal_action' => $_sett->get('logger_save_removal_action')
	));

	$_tpl->template('settings_logs');
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
