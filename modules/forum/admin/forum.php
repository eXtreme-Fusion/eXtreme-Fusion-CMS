<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
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
	
	
	include DIR_MODULES.'forum'.DS.'config.php';
	
	$_tpl->assign('config', $mod_info);
	
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