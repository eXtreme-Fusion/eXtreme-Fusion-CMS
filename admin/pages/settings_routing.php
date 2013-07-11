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
	
	$_locale->load('settings_routing');

	if ( ! $_user->hasPermission('admin.settings_routing'))
	{
		throw new userException(__('Access denied'));
	}

	$_fav->setFavByLink('settings_routing.php', $_user->get('id'));
	
	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			'routing' => serialize(array(
				'param_sep' => $_request->post('param_sep')->strip(),
				'main_sep' => $_request->post('main_sep')->strip(),
				'url_ext' => $_request->post('url_ext')->strip(),
				'tpl_ext' => $_request->post('tpl_ext')->strip(),
				'logic_ext' => $_request->post('logic_ext')->strip(),
				'ext_allowed' => $_request->post('ext_allowed')->isNum(TRUE)
			))
		));
		
		$_files->rmDirRecursive(DIR_CACHE, TRUE);
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		'param_sep' => $_sett->getUns('routing', 'param_sep'),
		'main_sep' => $_sett->getUns('routing', 'main_sep'),
		'url_ext' => $_sett->getUns('routing', 'url_ext'),
		'tpl_ext' => $_sett->getUns('routing', 'tpl_ext'),
		'logic_ext' => $_sett->getUns('routing', 'logic_ext'),
		'ext_allowed' => $_sett->getUns('routing', 'ext_allowed')
	));

	$_tpl->template('settings_routing');
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
