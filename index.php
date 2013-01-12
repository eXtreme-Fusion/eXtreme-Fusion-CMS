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
	// Czy dla podstrony musi istnieć plik TPL?
	defined('TPL_REQUIRED') || define('TPL_REQUIRED', FALSE);

	require_once 'system/sitecore.php';

	/**
	 * Szablon systemowy (theme)
	 */

	// Wczytywanie głównej klasy
	require_once DIR_CLASS.'Themes.php';

	/******* Koniec sekcji szablonu systemowego */

	// Routing class
	$_route = new Router($_request, $_sett, $_system->rewriteAvailable(), 'page', $_system->pathInfoExists(), $_sett->get('opening_page'), TRUE, TRUE, FALSE, 'admin');

	if ($_route->getByID(1) === 'ef5')
	{
		exit('eXtreme-Fusion 5 Beta 6');
	}

	StaticContainer::register('route', $_route);

	// Tryb prac na serwerze
	if ($_user->get('id') !== '1')
	{
		if ($_sett->get('maintenance') === '1')
		{
			if ($_route->getFileName() !== 'maintenance')
			{
				if (! $_user->hasAccess($_sett->get('maintenance_level')))
				{
					HELP::redirect(ADDR_SITE.'maintenance.html');
				}
			}
			else
			{
				if ($_user->hasAccess($_sett->get('maintenance_level')))
				{
					HELP::redirect(ADDR_SITE);
				}
			}
		}
		elseif ($_route->getFileName() === 'maintenance')
		{
			HELP::redirect(ADDR_SITE);
		}
	}

	/** Konfiguracja obiektu szablonu **/
	$_tpl = new Site($_route);

	//$_tpl->registerFunction('url', 'Url');

	/**
	 * Pobieranie linków definiowanych przez administratora
	 */
	$query = $_pdo->getData('SELECT * FROM [links] WHERE `link`=:link',
		array(':link', $_route->getFileName(), PDO::PARAM_STR)
	);

	if ($_pdo->getRowsCount($query))
	{
		foreach($query as $row)
		{
			// Ustawia administracyjną ścieżkę odczytu pliku
			$_route->setAdminFile($row['file']);
		}
	}

	// Scieżki, w których jest wyszukiwany plik wg kolejności przeszukiwania
	$folders = array(
		'modules' 	=> DIR_SITE.'modules'.DS.$_route->getFileName().DS.'pages'.DS,
		'pages' 	=> DIR_SITE.'pages'.DS
	);

	// Przesyła ściezkę do katalogu modułów
	$_route->setInstalledModules($ec->modules->getInstalled());

	$_route->setFolders($folders);

	/**
	 * Ustawia ostateczną sciężkę odczytu pliku
	 * W przypadku gdy wykonała się metoda setAdminFile(), sprawdzi czy plik istnieje.
	 * Jeśli nie, wyszuka go w lokalizacjach podanych parametrem
	 */
	$_route->setExitFile();

	if ( ! $_route->getExitFile())
	{
		$row = $_pdo->getRow('SELECT full_path FROM [links] WHERE short_path= :short_path ORDER BY `datestamp` DESC LIMIT 1',
			array(
				array(':short_path', $_route->getRequest(), PDO::PARAM_STR)
			)
		);

		if ($row)
		{
			$_route->setNewConfig($row['full_path']);
		}
	}
	// Tworzenie emulatora statyczności klasy OPT
	TPL::build($_theme = new Theme($_sett, $_system, $_user, $_pdo, $_request, $_route, $_route->getTplFileName()));

	//$_theme->registerFunction('url', 'Url');

	// Ładowanie pliku startowego modułu
	if ($row = $ec->modules->getModuleBootstrap($_system))
	{
		foreach ($row as $name)
		{
			include_once DIR_MODULES.$name.DS.'autoload'.DS.'__autoload.php';
		}
	}

	require_once DIR_THEME.'core'.DS.'theme.php';

	/* GENEROWANIE STRONY Z PLIKU PHP */

	// Sprawdzanie, czy plik istnieje
	if ( ! $_route->getExitFile())
	{
		$_route->trace(array('controller' => 'error', 'action' => 404, 'params' => NULL));
	}

	// Ładowanie pliku PHP wybranej podstrony
	require $_route->getExitFile();

	// Załączanie predefiniowanych elementów szablonu systemu (panele)
	require_once DIR_SYSTEM.'panels.php';

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

	render_page(FALSE);

	// Załączanie szablonu zamykającego stronę
	$_tpl->template('pre'.DS.'footer'.$_route->getExt('tpl'));

	// Usuwanie niepotrzebnych wpisów z tabeli użytkowników online.
	$_pdo->exec('DELETE FROM [users_online] WHERE `last_activity` < '.(time()-60*60*2));

	/*
	$_tree = new Tree($_pdo, 'drzewko');
	$_tree->add(0, 1);
	*/

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
   echo $exception;
}