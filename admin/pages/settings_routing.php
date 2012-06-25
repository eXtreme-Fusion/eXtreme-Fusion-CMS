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
	
	$_locale->load('settings_routing');

	if ( ! $_user->hasPermission('admin.settings_routing'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$_sett->update(array(
			'routing' => serialize(array(
				'param_sep' => $_request->post('param_sep')->strip(),
				'main_sep' => $_request->post('main_sep')->strip(),
				'url_ext' => $_request->post('url_ext')->strip(),
				'tpl_ext' => $_request->post('tpl_ext')->strip(),
				'logic_ext' => $_request->post('logic_ext')->strip()
			))
		));
		
		// Petmanentne usuwanie cache z wyjatkiem pliku dodanego do wyj¹tku.
		$_files->setOmitExt(array('.htaccess'));
		$_files->rmDirRecursive(DIR_CACHE, TRUE);
		
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}

	$_tpl->assignGroup(array(
		'param_sep' => $_sett->getUns('routing', 'param_sep'),
		'main_sep' => $_sett->getUns('routing', 'main_sep'),
		'url_ext' => $_sett->getUns('routing', 'url_ext'),
		'tpl_ext' => $_sett->getUns('routing', 'tpl_ext'),
		'logic_ext' => $_sett->getUns('routing', 'logic_ext')
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