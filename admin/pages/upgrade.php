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

	$_locale->load(array('settings', 'upgrade'));

    if ( ! $_user->hasPermission('admin.upgrade'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'updating' => array(
					__('The update has been finished successfully.'), __('Error! The update has not been finished.')
				)
			)
		);
    }

	if (SYSTEM_VERSION !== $_sett->get('version'))
	{
		if (! HELP::validUpVersion($_sett->get('version'), SYSTEM_VERSION))
		{
			$_tpl->printMessage('error', 'Nie możesz skorzystać z tej aktualizacji. Pobierz inną, właściwą dla Twojej wersji systemu CMS: https://github.com/eXtreme-Fusion/EF5-updates');
		}
		else
		{
			if ($_request->post('save')->show())
			{
				/** ZAPYTANIA DLA NOWEJ WERSJI **/

				$_sett->update(array(
					'shortdate' => 'd/m/Y H:i',
					'longdate' => 'd F Y H:i:s',
				));


				/** KONIEC ZAPYTAŃ DLA NOWEJ WERSJI **/

				$_sett->update(array('version' => SYSTEM_VERSION));

				$_log->insertSuccess('updating', __('The update has been finished successfully.'));
				$_files->rmDirRecursive(DIR_CACHE);
				$_request->redirect(FILE_PATH, array('act' => 'updating', 'status' => 'ok'));
			}

			$_tpl->assign('upgrade', TRUE);
		}
	}

	$_tpl->template('upgrade');
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
   echo $exception;
}