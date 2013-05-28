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
| Co Author: WEC
| Author: Hans Kristian Flaatten {Starefossen}
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

	$_locale->load('panel_editor');

    if ( ! $_user->hasPermission('admin.panels'))
    {
        throw new userException(__('Access denied'));
    }

	require DIR_CLASS.'Panels.php';

    $_tpl = new Iframe;

	$_pnl = new Panels($_pdo, '..'.DS.'..'.DS.'modules'.DS);

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'edit' => array(
					__('The panel has been edited.'), __('Error! The panel has not been edited.')
				),
				'add' => array(
					__('The panel has been added.'), __('Error! The panel has not been added.')
				)
			)
		);
    }

/**	VIEW INTERFACE **

	// Zapis danych
	if ($_request->post('save')->show())
	{
		// Aktualizacja panelu
		if ($_request->get('action') === 'edit' && $_request->get('id')->isNum())
		{

		}
		// Dodanie nowego panelu
		else
		{

		}
	}
	// Podgląd panelu
	elseif ($_request->post('preview'))
	{

	}
	// Formularz tworzenia panelu
	else
	{
		// Edycja panelu
		if ($_request->get('action') === 'edit' && $_request->get('id')->isNum())
		{

		}
		// Tworzenie nowego panelu
		else
		{

		}
	}

**	end of VIEW INTERFACE **/


	// Zapis danych
	if ($_request->post('save')->show())
	{
		$access = $_request->post('panel_access')->show() != array(0 => '0') ? $_request->post('panel_access')->getNumArray() : array(0 => '3');
		// Aktualizacja panelu
		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$_pnl->updatePanel($_request->get('id')->show(), $access, $_request->post('panel_name')->show(), $_request->post('panel_content')->show());
			$_log->insertSuccess('edit', __('The panel has been edited.'));
			$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
		}
		// Dodanie nowego panelu
		else
		{
            $_pnl->insertPanel($_request->post('panel_name')->show(), $_request->post('panel_content')->show(), $_request->post('panel_side')->show(), $access);
			$_log->insertSuccess('add', __('The panel has been added.'));
			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
		}
	}
	// Podgląd panelu
	elseif ($_request->post('preview')->show())
	{
		ob_start();

		eval($_pnl->closePHPSet($_request->post('panel_content')->show(), TRUE));

		$_tpl->assignGroup(array(
			'preview' => ob_get_contents(),
			'name' => $_request->post('panel_name')->show(),
			'content' => $_pnl->closePHPOut($_request->post('panel_content')->show()),
			'access' => $_tpl->getMultiSelect($_user->getViewGroups(), $_request->post('panel_access')->show(), TRUE)
		));

		if ($_request->get('action')->show() !== 'edit')
		{
			$_tpl->assign('side', $_request->post('panel_side')->show());
		}

		ob_end_clean();
	}
	// Formularz tworzenia panelu
	else
	{
		// Edycja panelu
		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$data = $_pnl->get($_request->get('id')->show());

			$_tpl->assignGroup(array(
				'name' => $data['name'],
				'content' => $data['content'],
				'side' => $data['side'],
				'access' => $_tpl->getMultiSelect($_user->getViewGroups(), $data['access'], TRUE),
				'is_file' => $data['type'] === 'file'
			));
		}
		// Tworzenie nowego panelu
		else
		{
			$_tpl->assign('access', $_tpl->getMultiSelect($_user->getViewGroups(), NULL, TRUE));
		}
	}

	$_tpl->template('panel_editor');

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
catch(panel_sideException $exception)
{
    userErrorHandler($exception);
}
