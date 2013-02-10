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
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->load('settings_ipp');

	if ( ! $_user->haspermission('admin.settings_ipp'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			'news_per_page' => $_request->post('news_per_page')->isNum(TRUE),
			'news_cats_per_page' => $_request->post('news_cats_per_page')->isNum(TRUE),
			'news_cats_item_per_page' => $_request->post('news_cats_item_per_page')->isNum(TRUE),
			'users_per_page' => $_request->post('users_per_page')->isNum(TRUE),
			'notes_per_page' => $_request->post('notes_per_page')->isNum(TRUE)
		));
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		'news_per_page' => $_sett->get('news_per_page'),
		'news_cats_per_page' => $_sett->get('news_cats_per_page'),
		'news_cats_item_per_page' => $_sett->get('news_cats_item_per_page'),
		'users_per_page' => $_sett->get('users_per_page'),
		'notes_per_page' => $_sett->get('notes_per_page')
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