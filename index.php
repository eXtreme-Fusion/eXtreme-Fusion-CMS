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
	// Czy dla podstrony musi istnieć plik TPL?
	defined('TPL_REQUIRED') || define('TPL_REQUIRED', FALSE);

	require_once 'system/sitecore.php';

	// Routing class
	$_route = new Router($_request, $_sett, $_system->rewriteAvailable(), 'page', $_system->pathInfoExists(), $_sett->get('opening_page'), TRUE, TRUE, FALSE, 'admin');

	if ($_request->get('ef5')->show())
	{
		exit('eXtreme-Fusion '.$_sett->get('version'));
	}

	if ($_user->bannedByIP())
	{
		$_route->trace(array('controller' => 'error', 'action' => 403, 'params' => NULL));
	}

	StaticContainer::register('route', $_route);

	// Tryb prac na serwerze
	if ($_user->get('id') !== '1')
	{
		// Tryb maintenance?
		if ($_sett->get('maintenance') === '1')
		{
			// Jeśli jest się na innej podstronie niz maintenance
			if ($_route->getFileName() !== 'maintenance')
			{
				// Jeśli nie ma się uprawnień do bycia na innej podstronie
				if (! $_user->hasAccess($_sett->get('maintenance_level')))
				{
					$_route->redirect(array('controller' => 'maintenance'));
				}
			}
			else
			{	// Jesli ma się prawa do bycia na innej podstronie
				if ($_user->hasAccess($_sett->get('maintenance_level')))
				{
					// Przekierowanie na strone główną
					HELP::redirect(ADDR_SITE);
				}
			}
		}
		elseif ($_route->getFileName() === 'maintenance')
		{
			HELP::redirect(ADDR_SITE);
		}
	}

	/** Konfiguracja obiektu szablonu dla podstron **/
	$_tpl = new Site($_route);

	// Nie usuwać
	/**
	 * Pobieranie linków definiowanych przez administratora
	 */
	/*$query = $_pdo->getData('SELECT * FROM [links] WHERE `link`=:link',
		array(':link', $_route->getFileName(), PDO::PARAM_STR)
	);

	if ($query)
	{
		foreach($query as $row)
		{
			// Ustawia administracyjną ścieżkę odczytu pliku
			$_route->setAdminFile($row['file']);
		}
	}*/


	// Scieżki, w których jest wyszukiwany plik wg kolejności przeszukiwania
	$folders = array(
		'modules' 	=> DIR_SITE.'modules'.DS.$_route->getFileName().DS.'pages'.DS,
		'pages' 	=> DIR_SITE.'pages'.DS
	);

	// Przesyła ściezkę do katalogu modułów
	$_route->setInstalledModules($ec->modules->getInstalled());

	$_route->setFolders($folders);

	if ( ! $_route->getExitFile())
	{
		$row = $_pdo->getRow('SELECT full_path FROM [links] WHERE short_path =:short_path ORDER BY `datestamp` DESC LIMIT 1',
			array(':short_path', substr(PATH_INFO, 0, 1) === '/' ? substr(PATH_INFO, 1) : PATH_INFO, PDO::PARAM_STR)
		);

		if ($row)
		{
			$_route->setNewConfig($row['full_path']);
		}
		else
		{
			/**
			 * Ustawia ostateczną sciężkę odczytu pliku
			 * W przypadku gdy wykonała się metoda setAdminFile(), sprawdzi czy plik istnieje.
			 * Jeśli nie, wyszuka go w lokalizacjach podanych parametrem
			 */
			$_route->setExitFile();
		}
	}

	if ($_sett->get('visits_counter_enabled'))
	{
		$ec->statistics->saveUniqueVisit($_user->getIP());
	}
	
	// Ładowanie pliku startowego modułu
	if ($row = $ec->modules->getModuleBootstrap($_system))
	{
		foreach ($row as $name)
		{
			include_once DIR_MODULES.$name.DS.'autoload'.DS.'__autoload.php';
		}
	}

	/**
	 * Szablon systemowy (theme)
	 */

	// Załączanie klasy szablonu
	require_once DIR_THEME.'view.php';

	$_theme = new Theme($_sett, $_system, $_user, $_pdo, $_request, $_route, $_head, $_route->getTplFileName());

	$_theme->setStatisticsInst($ec->statistics);

	Parser::setThemeInst($_theme);

	/******* Koniec sekcji szablonu systemowego */

	/* GENEROWANIE STRONY Z PLIKU PHP */

	// Sprawdzanie, czy plik istnieje
	if ( ! $_route->getExitFile())
	{
		$_route->trace(array('controller' => 'error', 'action' => 404, 'params' => NULL));
	}

	$trace = $_route->getExitFile();
	ob_start();
	// Ładowanie pliku PHP wybranej podstrony
	require $_route->getExitFile();

	// Sprawdzanie, czy w załadowanym pliku nastąpiła zmana trace'a (błąd 404, przymus logowania etc.).
	if ($trace !== $_route->getExitFile())
	{
		// Załączanie pliku z trace'a. Zmienił się też TPL.
		require $_route->getExitFile();

	}
	$render = ob_get_contents();
	ob_clean();
	// Załączanie predefiniowanych elementów szablonu systemu (panele)
	if ($_route->getFileName() !== 'maintenance')
	{
		require_once DIR_SYSTEM.'panels.php';
	}
	else
	{
		define('LEFT', ''); define('TOP_CENTER', ''); define('BOTTOM_CENTER', ''); define('RIGHT', '');
	}

	/**
	 * Konfiguracja sekcji HEAD
	 */

	if ( ! isset($theme['Title']))
	{
		$theme['Title'] = $_sett->get('site_name');
	}
	if ( ! isset($theme['Keys']))
	{
		$theme['Keys'] = $_sett->get('keywords');
	}
	if ( ! isset($theme['Desc']))
	{
		$theme['Desc'] = HELP::cleanDescription($_sett->get('description'));
	}

	$theme['Tags'] = $_head->get();

	$_tpl->assign('Theme', $theme);

	/******* Koniec konfiguracji sekcji HEAD */

	// Załączanie sekcji HEAD
	if (file_exists(DIR_THEME.'templates'.DS.'pre'.DS.'header'.$_route->getExt('tpl')))
	{
		$_tpl->template('header'.$_route->getExt('tpl'), DIR_THEME.'templates'.DS.'pre'.DS);
	}
	else
	{
		$_tpl->template('header'.$_route->getExt('tpl'), DIR_TEMPLATES.'pre'.DS);
	}

	ob_start();

	/* PREZENTACJA STRONY Z PLIKU TPL */
	echo $render;
	if ( ! isset($exc_error))
	{
		if (! $_tpl->getPageCompileDir())
		{
			// Wyświetlanie podstrony TPL z katalogu szablonu
			if (( ! defined('THIS') || ! THIS) && $_theme->tplExists())
			{
				// Zmienia ścieżkę, skąd jest odczytywany szablon
				$_tpl->setCustomRoot(DIR_THEME.'templates'.DS.'pages'.DS);
			}

			// Sprawdzanie, czy szablon istnieje
			if (file_exists($_tpl->root.$_route->getTplFileName()))
			{
				if (isset($theme['cache']))
				{
					$_tpl->cache($theme['cache']);
				}

				$_tpl->template($_route->getTplFileName());
			}
			elseif (TPL_REQUIRED)
			{
				throw new systemException('Szablon dla podstrony <span class="italic">'.$_route->getTplFileName().'</span> nie istnieje.');
			}
		}
		else
		{
			$_tpl->template($_route->getTplFileName(), $_tpl->getPageCompileDir());
		}

		$_tpl->setDefaultRoot();
	}

	// Wyświetlanie komunikatu o błędzie w pliku PHP
	else
	{
		$_tpl->assign('Message', $exc_error['Message']);
		unset($exc_error['Message']);
		$_tpl->assign('Error', $exc_error);

		$_tpl->template('pre'.DS.'exception'.$_route->getExt('tpl'));
	}

	// Wyświetlanie dodatkowych plików szablonu
	if (isset($theme['parse']) && is_array($theme['parse']))
	{
		foreach($theme['parse'] as $file)
		{
			$_tpl->template($file);
		}
	}

	defined('CONTENT') || define('CONTENT', ob_get_contents());
	ob_end_clean();

	if ($_route->getFileName() === 'maintenance')
	{
		// Renderowanie strony bez menu, paneli bocznych i stopki
		$_theme->page(TRUE, FALSE, FALSE, FALSE, FALSE);
	}
	else
	{
		$_theme->page();
	}

	// Załączanie szablonu zamykającego stronę
	$_tpl->template('pre'.DS.'footer'.$_route->getExt('tpl'));

	session_write_close();
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
