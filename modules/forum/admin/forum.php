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
	
	//?
	$_locale->moduleLoad('settings', 'forum');

	// Sprawdza prawa dostêpu do podstrony
    if ( ! $_user->hasPermission('module.forum.admin'))
    {
        throw new userException(__('Access denied'));
    }

	// Tworzy obiekt odpowiedzialny za prezentacjê
    $_tpl = new AdminModuleIframe('forum');
	
	// Zapisuje podstronê do odœwie¿enia
	$_tpl->setHistory(__FILE__);
	
	// Zapis danych
	if ($_request->post('save')->show())
	{
		$_pdo->exec('UPDATE [sign_protection] SET `validation_type` = :type', array(':type', $_request->post('validation_type')->isNum(TRUE), PDO::PARAM_INT));
		
		// Komunikat do wyœwietlenia przez szablon
		$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
	}
	
	// Pobieranie danych
	if ($row = $_pdo->getRow('SELECT `validation_type` FROM [sign_protection]'))
	{
		// Zapisywanie danych w szablonie
		$_tpl->assign('validation_type', $row['validation_type']);
	}
	
	// Renderowanie szablonu
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