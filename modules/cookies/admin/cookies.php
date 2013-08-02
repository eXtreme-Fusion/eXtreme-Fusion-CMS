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

	$_locale->moduleLoad('cookies', 'cookies');

    if ( ! $_user->hasPermission('module.cookies.admin'))
    {
        throw new userException(__('Access denied'));
    }
	
	$_fav->setFavByLink('cookies/admin/cookies.php', $_user->get('id'));
	
    $_tpl = new AdminModuleIframe('cookies');
	
	$_tpl->setHistory(__FILE__);

	if ($_request->post('save')->show())
	{
		$_pdo->exec('UPDATE [cookies] SET `message` = :message, `policy` = :policy', array(
			array(':message', $_request->post('message')->show(), PDO::PARAM_STR),
			array(':policy', $_request->post('policy')->show(), PDO::PARAM_STR)
		));
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	if ($row = $_pdo->getRow('SELECT `message`, `policy` FROM [cookies]'))
	{
		$_tpl->assignGroup(array(
			'data' => $row['message'],
			'policy' => $row['policy'],
			'learn_more_url' => $_url->path(array('controller' => 'cookies')),
			'learn_more_url_title' => __('Learn more')
		));
	}

	$_tpl->template('admin.tpl');
}
catch(uploadException $exception)
{
    uploadErrorHandler($exception);
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