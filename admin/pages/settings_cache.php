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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+------------------------------------------------------
| Author: Nick Jones (Digitanium)
+------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+------------------------------------------------------*/
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	$_locale->load('settings_cache');

	if ( ! $_user->hasPermission('admin.settings_cache'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			'cache' => serialize(array(
				//'expire_contact' => $_request->post('expire_contact')->isNum(TRUE),
				'expire_news' => $_request->post('expire_news')->isNum(TRUE),
				'expire_news_cats' => $_request->post('expire_news_cats')->isNum(TRUE),
				'expire_pages' => $_request->post('expire_pages')->isNum(TRUE),
				'expire_profile' => $_request->post('expire_profile')->isNum(TRUE),
				//'expire_rules' => $_request->post('expire_rules')->isNum(TRUE),
				'expire_tags' => $_request->post('expire_tags')->isNum(TRUE),
				'expire_team' => $_request->post('expire_team')->isNum(TRUE),
				'expire_users' => $_request->post('expire_users')->isNum(TRUE)
			))
		));
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		//'cache_contact' => $_sett->getUns('cache', 'expire_contact'),
		'cache_news' => $_sett->getUns('cache', 'expire_news'),
		'cache_news_cats' => $_sett->getUns('cache', 'expire_news_cats'),
		'cache_pages' => $_sett->getUns('cache', 'expire_pages'),
		'cache_profile' => $_sett->getUns('cache', 'expire_profile'),
		//'cache_rules' => $_sett->getUns('cache', 'expire_rules'),
		'cache_tags' => $_sett->getUns('cache', 'expire_tags'),
		'cache_team' => $_sett->getUns('cache', 'expire_team'),
		'cache_users' => $_sett->getUns('cache', 'expire_users'),
	));

	$_tpl->template('settings_cache');
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
