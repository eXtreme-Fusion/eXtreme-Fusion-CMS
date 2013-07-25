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
| Co-Author: Christian Damsgaard J�rgensen (PMM)
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

	$_locale->load('modules');

    if ( ! $_user->hasPermission('admin.modules'))
    {
        throw new userException(__('Access denied'));
    }

	// Tutaj jest to zbędne bo nie ma indeksu w tabeli admin dla tej zawartości.
	//$_fav->setFavByLink('modules.php', $_user->get('id'));
	
	$_tpl = new Iframe;
	
	$_mod = new Modules($_pdo, $_sett, $_user, New Tag($_system, $_pdo), $_locale, $_system);

	// Wyświetlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		$_system->clearCacheRecursive($_files);
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'install' => array(
					__('Modules have been installed.'), __('Error! Modules have not been installed.')
				),
				'uninstall' => array(
					__('Modules have been uninstalled.'), __('Error! Modules have not been uninstalled.')
				),
			)
		);
    }

	$mod_info = array();
	$new_table = array();
	$new_row = array();
	$drop_table = array();
	$admin_page = array();
	$menu_link = array();

	// Usuwanie lub instalacja
	if ($_request->post('save')->show())
	{
		$installed = $_mod->getInstalled();

		if ($_request->post('mod')->show())
		{
			// Pobierz listę wszystkich modułów z katalogu modules
			$file_list = $_mod->getItems();

			foreach($file_list as $val)
			{

				if (in_array($val[0], $_request->post('mod')->show()) && !in_array($val[0], $installed))
				{
					$_mod->install($val[0]);
				}

				if ( !in_array($val[0], $_request->post('mod')->show()) && in_array($val[0], $installed))
				{
					$_mod->uninstall($val[0]);
				}
			}

			if ($_request->post('update')->show())
			{
				foreach($_request->post('update')->show() as $val)
				{
					$_mod->update($val);
				}
			}

			$_log->insertSuccess('install', __('Modules have been installed.'));
			$_request->redirect(FILE_PATH, array('act' => 'install', 'status' => 'ok'));

		}
		else
		{
			foreach($installed as $val)
			{
				$_mod->uninstall($val);
			}

			$_log->insertSuccess('uninstall', __('Modules have been uninstalled.'));
			$_request->redirect(FILE_PATH, array('act' => 'uninstall', 'status' => 'ok'));

		}
	}

	// Aktualizacja listy zainstalowanych modułów
	$installed = $_mod->getInstalled();

	if (count($mod_list = $_mod->getItems()))
	{
		$mod = array();
		$i = 0;
		foreach($mod_list as $val)
		{
			if ($mod_info = $_mod->getConfig(DIR_MODULES.$val[0].DS.'config.php'))
			{
				$mod[] = array(
					'row_color'		=> $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'id'			=> $i,
					'value'     	=> $val[0],
					'installed'  	=> in_array($val[0], $installed) ? TRUE : FALSE,
					'label'      	=> $val[1],
					'is_to_update' 	=> $_mod->isToUpdate($mod_info['dir']) ? TRUE : FALSE,
					'desc'       	=> $mod_info['description'],
					'version'   	=> $_mod->getItemVersion($mod_info['dir'], $mod_info['version']),
					'webURL'     	=> $mod_info['support'],
					'author'     	=> $mod_info['developer'],
					'development'   => isset($mod_info['development']) ? $mod_info['development'] : FALSE
				);
				$i++;
			}
		}

		$_tpl->assign('mod', $mod);
	}

	$_tpl->template('modules');

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
