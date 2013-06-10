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

	$_locale->moduleLoad('admin', 'forum');

	if ( ! $_user->hasPermission('module.forum.admin'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new AdminModuleIframe('forum');
	
	// Inicjalizacja klas
	! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);
	
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