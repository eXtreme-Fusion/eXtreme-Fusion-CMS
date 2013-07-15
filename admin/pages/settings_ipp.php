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
| Author: Hans Kristian Flaatten (Starefossen)
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

	$_locale->load('settings_ipp');

	if ( ! $_user->haspermission('admin.settings_ipp'))
	{
		throw new userException(__('Access denied'));
	}

	$_fav->setFavByLink('settings_ipp.php', $_user->get('id'));
	
	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			'news_per_page' => $_request->post('news_per_page')->isNum(TRUE),
			//'news_cats_per_page' => $_request->post('news_cats_per_page')->isNum(TRUE),
			'news_cats_item_per_page' => $_request->post('news_cats_item_per_page')->isNum(TRUE),
			'users_per_page' => $_request->post('users_per_page')->isNum(TRUE),
			//'notes_per_page' => $_request->post('notes_per_page')->isNum(TRUE),
			'comments_per_page' => $_request->post('comments_per_page')->isNum(TRUE)
		));

		$_system->clearCache(array('news', 'news_cats', 'users'));

		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		'news_per_page' => $_sett->get('news_per_page'),
		//'news_cats_per_page' => $_sett->get('news_cats_per_page'),
		'news_cats_item_per_page' => $_sett->get('news_cats_item_per_page'),
		'users_per_page' => $_sett->get('users_per_page'),
		//'notes_per_page' => $_sett->get('notes_per_page'),
		'comments_per_page' => $_sett->get('comments_per_page')
	));

	$_tpl->template('settings_ipp');
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