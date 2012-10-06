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
				//'expire_contact' => $_request->post('expire_contact')->isNum() ? $_request->post('expire_contact')->show() : $_sett->get('expire_contact'),
				'expire_news' => $_request->post('expire_news')->isNum() ? $_request->post('expire_news')->show() : $_sett->get('expire_news'),
				'expire_news_cats' => $_request->post('expire_news_cats')->isNum() ? $_request->post('expire_news_cats')->show() : $_sett->get('expire_news_cats'),
				'expire_pages' => $_request->post('expire_pages')->isNum() ? $_request->post('expire_pages')->show() : $_sett->get('expire_pages'),
				'expire_profile' => $_request->post('expire_profile')->isNum() ? $_request->post('expire_profile')->show() : $_sett->get('expire_profile'),
				//'expire_rules' => $_request->post('expire_rules')->isNum() ? $_request->post('expire_rules')->show() : $_sett->get('expire_rules'),
				'expire_tags' => $_request->post('expire_tags')->isNum() ? $_request->post('expire_tags')->show() : $_sett->get('expire_tags'),
				'expire_team' => $_request->post('expire_team')->isNum() ? $_request->post('expire_team')->show() : $_sett->get('expire_team'),
				'expire_users' => $_request->post('expire_users')->isNum() ? $_request->post('expire_users')->show() : $_sett->get('expire_users')
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