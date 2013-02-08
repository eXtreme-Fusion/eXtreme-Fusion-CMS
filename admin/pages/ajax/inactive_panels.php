<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
		
	$_locale->load('panels');

	if ($_user->isLoggedIn())
	{
		if ( ! $_user->hasPermission('admin.panels'))
		{
			throw new userException(__('Access denied'));
		}

		$_tpl = new General(DIR_ADMIN_PAGES.DS.'ajax'.DS.'templates'.DS);
		
		$_tag = new Tag($_system, $_pdo);
		$_modules = new Modules($_pdo, $_sett, $_user, $_tag, $_locale);
		
		$_panels = new Panels($_pdo);
		
		$_panels->setModulesInst($_modules);
		
		$_panels->adminMakeListPanels($_user, TRUE);

		$_tpl->assign('noact_panels', $_panels->adminGetInactivePanels());
		
		$_tpl->template('inactive_panels.tpl');
	}
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
