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
*********************************************************/
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	$_locale->moduleLoad('admin', 'bridge_phpbb');

    if ( ! $_user->hasPermission('module.bridge_phpbb.admin'))
    {
        throw new userException(__('Access denied'));
    }

	$_tpl = new AdminModuleIframe('bridge_phpbb');

	if ($_request->get(array('status', 'act'))->show())
	{
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'save' => array(
					__('Zapisano ustawienia'), __('B³¹d podczas zapisywania ustawieñ.') 
				)
			)
		);
    }
	
	if ($_request->post('save')->show() && $_request->post('status')->isNum())
	{
		$status = $_request->post('status')->show();
		$prefix = $_request->post('prefix')->filters('trim', 'strip');

		$count = $_pdo->exec('UPDATE [bridge_phpbb] SET `status` = :status, `prefix` = :prefix',
			array(
				array(':status', $status, PDO::PARAM_INT),
				array(':prefix', $prefix, PDO::PARAM_INT)
			)
		);
			
			if ($count)
			{
				$_request->redirect(FILE_PATH, array('act' => 'save', 'status' => 'ok'));
			}
			$_request->redirect(FILE_PATH, array('act' => 'save', 'status' => 'error'));
	}

	$row = $_pdo->getRow('SELECT * FROM [bridge_phpbb]');
		
	$_tpl->assignGroup(array(
		'status' => $row['status'],
		'prefix' => $row['prefix']
	));
	
    $_tpl->template('admin.tpl');
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