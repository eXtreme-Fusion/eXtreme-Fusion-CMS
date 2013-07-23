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
	
	$_locale->load(array(
		'settings',
		'upgrade'
		)
	);

    if ( ! $_user->hasPermission('admin.upgrade'))
	{
		throw new userException(__('Access denied'));
	}
	
	$_fav->setFavByLink('upgrade.php', $_user->get('id'));
	
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

	if ($_sett->get('version') < SYSTEM_VERSION)
	{
		if ($_request->post('save')->show())
		{
			/*
				Zapytania wymagane podczas aktualizacji
			*/
		
			$_pdo->exec('ALTER TABLE [statistics] CHANGE `ip` `ip` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'0.0.0.0\'');
			
			$_pdo->exec("INSERT INTO [admin] (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_synchro', 'synchro.png', 'Synchronization', 'settings_synchro.php', 4)");
			
			$count = $_sett->update(array
				(
					'version' => SYSTEM_VERSION
				)
			);
			
			if ($count)
			{
				$_log->insertSuccess('updating', __('The update has been finished successfully.'));
				$_request->redirect(FILE_PATH, array('act' => 'updating', 'status' => 'ok'));
			}

			$_log->insertFail('updating', __('Error! The update has not been finished.'));
			$_request->redirect(FILE_PATH, array('act' => 'updating', 'status' => 'error'));
		}

		$_tpl->assign('upgrade', TRUE);
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
