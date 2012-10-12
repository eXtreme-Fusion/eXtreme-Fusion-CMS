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
	
	$_locale->load(array(
		'settings',
		'upgrade'
		)
	);

    if ( ! $_user->hasPermission('admin.upgrade'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'update' => array(
					__('The update has been finished successfully.'), __('Error! The update has not been finished.')
				)
			)
		);
    }
	
	// Numer wersji, do której system ma zostać zaktualizowany
	$new_version = '5.0';

	$ver = $_sett->get('version') < $new_version;

	if ($_request->post('save')->show())
	{
		/*
			Zapytania wymagane podczas aktualizacji
		*/
	
		$count = $_sett->update(array
			(
				'version' => $new_version
			)
		);
		
		if ($count)
		{
			$_log->insertSuccess('update', __('The update has been finished successfully.'));
			$_request->redirect(FILE_PATH, array('act' => 'update', 'status' => 'ok'));
		}

		$_log->insertFail('update', __('Error! The update has not been finished.'));
		$_request->redirect(FILE_PATH, array('act' => 'update', 'status' => 'error'));
	}

	$_tpl->assign('ver', $ver);

	$_tpl->template('upgrade');

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