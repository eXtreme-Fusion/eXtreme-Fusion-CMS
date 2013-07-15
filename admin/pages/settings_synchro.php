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
*********************************************************/
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	$_locale->load('settings_synchro');

	if ( ! $_user->hasPermission('admin.settings_synchro'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->get('action', NULL)->show() === NULL)
	{
		if ($_request->post('save')->show())
		{
			$_sett->update(array(
				'synchro' => $_request->post('ext_allowed')->isNum(TRUE)
			));

			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
		}

		$_tpl->assign('synchro', $_sett->get('synchro'));
	}
	elseif ($_request->get('action')->show() === 'download')
	{
		$_tpl->assign('url', base64_decode(urldecode($_request->get('url')->show())));
		$_tpl->assign('upgrade', ADDR_ADMIN.'pages/upgrade.php');
	}

	$_tpl->template('settings_synchro');
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
