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

	$_locale->load('settings');

	if ( ! $_user->hasPermission('admin.settings'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new Iframe;

	if ($_request->post('save')->show())
	{
		$new_lang = NULL;

		if ($_request->post('locale_set')->strip() !== $_sett->get('locale'))
		{
			$new_lang = TRUE;
		}

		$_sett->update(array(
			'site_name' => $_request->post('site_name')->strip(),
			'site_banner' => $_request->post('site_banner')->strip(),
			'contact_email' => $_request->post('siteemail')->strip(),
			'site_username' => $_request->post('siteusername')->strip(),
			'site_intro' => $_request->post('siteintro')->show(),
			'description' => $_request->post('description')->strip(),
			'keywords' => $_request->post('keywords')->strip(),
			'footer' => $_request->post('footer')->strip(),
			'opening_page' => $_request->post('opening_page')->strip(),
			'default_search' => $_request->post('default_search')->strip(),
			'language_detection' => $_request->post('language_detection')->isNum(TRUE),
		));

		$_files->rmDirRecursive(DIR_CACHE);

		/**
		 * Poniższe sprawdzanie czy szablon istnieje jest zabezpieczeniem przed następującą sytuacją:
		 * Administrator wchodzi w Ustawienia główne. Nie zamyka przeglądarki i przechodzi na serwer FTP.
		 * Nadaje nową nazwę jednemu z katalogów, które przechowują szablon.
		 * Następnie wraca do Ustawień głównych i zmienia szablon strony na nieistniejący (bo zmienił jego nazwę na serwerze FTP).
		 * Poniższy kod spowoduje, że wyświetlony zostanie komunikat o błędzie, zamiast blokady całej strony z powodu braku szablonu.
		 *
		 * W podobny sposób działa zabezpieczenie języka systemowego.
		 */

		// Czy wybrany język istnieje?
		if (in_array($_request->post('locale_set')->strip(), $_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders')))
		{
			$_sett->update(array('locale' => $_request->post('locale_set')->strip()));
		}
		else
		{
			throw new userException('The language which has been chosen is not available.');
		}

		// Czy wybrany szablon istnieje?
		if (in_array($_request->post('theme_set')->strip(), $_files->createFileList(DIR_SITE.'themes', array('templates'), TRUE, 'folders')))
		{
			$_sett->update(array('theme' => $_request->post('theme_set')->strip()));
		}
		else
		{
			throw new userException('The template chosen is not available.');
		}

		if ($new_lang)
		{
			$_tpl->printMessage('valid', __('Data has been saved. Please refresh the page to see changes.'));
			$_log->insertSuccess('edit', __('System language has been changed.'));
		}
		else
		{
			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
		}
	}

	$_tpl->assignGroup(array(
		'site_name' => $_sett->get('site_name'),
		'site_banner' => $_sett->get('site_banner'),
		'siteemail' => $_sett->get('contact_email'),
		'siteusername' => $_sett->get('site_username'),
		'siteintro' => $_sett->get('site_intro'),
		'footer' => $_sett->get('footer'),
		'description' => $_sett->get('description'),
		'keywords' => $_sett->get('keywords'),
		'opening_page' => $_sett->get('opening_page'),
		'default_search' => $_sett->get('default_search'),
		//'old_locale' => $_sett->get('locale'),
		'language_detection' => $_sett->get('language_detection'),
		'locale_set' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $_sett->get('locale')),
		'theme_set' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'themes', array(), TRUE, 'folders'), $_sett->get('theme'))
	));

	$handle = opendir(DIR_LOCALE.$_sett->get('locale').DS.'search');
	while (FALSE !== ($entry = readdir($handle)))
	{
		if (substr($entry, 0, 1) != '.' && $entry != 'index.php')
		{
			$_lang = include DIR_LOCALE.$_sett->get('locale').DS.'search'.DS.$entry;

			foreach ($_lang as $key => $value)
			{
				if (preg_match("/400/i", $key))
				{
					$search_opts[] = array(
						'entry' => str_replace('.php', '', $entry),
						'value' => $value
					);
				}
			}

			unset($_lang);
		}
	}
	closedir($handle);

	$_tpl->assign('search_opts', $search_opts);
	
	$_tpl->template('settings_main');
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
