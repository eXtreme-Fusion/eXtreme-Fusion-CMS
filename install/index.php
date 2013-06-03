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
error_reporting(-1);

try
{
	// Instalowana wersja systemu - wyświetlana w nagłówku nawigacji
	define('VERSION', '5.0.2-unstable');

	$HostURL = explode('install', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

	$charset = 'utf8';
	$collate = 'utf8_general_ci';

	define('DS', DIRECTORY_SEPARATOR);

	define('DIR_BASE', dirname(__FILE__).DS);
	define('DIR_SITE', realpath(DIR_BASE.'..').DS);

	define('ADDR_SITE', 'http://'.$HostURL[0]);

	require DIR_SITE.'bootstrap.php';
	require_once DIR_CLASS.'Exception.php';

	/** Templates init **/
	require_once OPT_DIR.'opt.class.php';

	$_tpl = new optClass;
	$_tpl->compile = DIR_CACHE;
	$_tpl->root = DIR_BASE.'templates'.DS;
	/***/

	require DIR_SITE.'system'.DS.'helpers'.DS.'main.php';

	$_system = new System;

	define('PHP_REQUIRED', Server::getRequiredPHPVersion());

	/**
	 * Można by wybrany z listy język zapisać do $_SESSION['localeset'] i sprawdzić dostępność kodem
	 * obecnie znajdującym się w else, ale byłoby to złe rozwiązanie, gdyż w przypadku,
	 * gdy wybrany język byłby niedostępny, ustawiłby się angielski zamiast pozostać
	 * przy języku sprzed próby jego zmiany.
	 */

	// Sprawdzanie, czy wybrano zmianę języka i jeśli tak, to czy jest on dostępny
	if (isset($_POST['localeset']) && file_exists(DIR_SITE.'locale'.DS.$_POST['localeset']) && is_dir(DIR_SITE.'locale'.DS.$_POST['localeset']))
	{
		$_SESSION['localeset'] = $_POST['localeset'];
	}
	else
	{
		if (!isset($_SESSION['localeset']))
		{
			// Zapisywanie języka na podstawie informacji pochodzących z przeglądarki
			$_SESSION['localeset'] = $_system->detectBrowserLanguage(TRUE);
		}

		if (!file_exists(DIR_SITE.'locale'.DS.$_SESSION['localeset']) || !is_dir(DIR_SITE.'locale'.DS.$_SESSION['localeset']))
		{
			$_SESSION['localeset'] = 'English';
		}
	}

	$_locale = new Locales($_SESSION['localeset'], DIR_LOCALE);
	$_locale->load('setup');

	if (isset($_POST['step']) && $_POST['step'] == '7')
	{
		HELP::removeSession('user', 'admin');
		HELP::removeCookie('user');
		HELP::redirect(ADDR_SITE);
	}

	function getStepHeader()
	{
		static $header = NULL;

		if ($header === NULL)
		{
			if (getStepNum() === 1) {
				$header = __('Step 1: Locale');
			} elseif (getStepNum() === 2) {
				$header = __('Step 2: Checking server configuration');
			} elseif (getStepNum() === 3) {
				$header = __('Step 3: File and Folder Permissions Test');
			} elseif (getStepNum() === 4) {
				$header = __('Step 4: Database Settings');
			} elseif (getStepNum() === 5) {
				$header = __('Step 5: Head Admin Details');
			} elseif (getStepNum() === 6) {
				$header = __('Step 6: Final Settings');
			} else {
				$header = '';
			}
		}

		return $header;
	}

	// Zwraca numerycznie aktualny etap instalacji
	function getStepNum()
	{
		static $step;

		if (!$step)
		{
			if (isset($_SESSION['step']))
			{
				if ($_SESSION['step'] === 'abort')
				{
					$step = 0;
				}
				else
				{
					if (isset($_SESSION['step']) && is_numeric($_SESSION['step']) && $_SESSION['step'])
					{
						$step = intval($_SESSION['step']);
					}
					else
					{
						$step = $_SESSION['step'] = 1;
					}
				}
			}
			else
			{
				$step = $_SESSION['step'] = 1;
			}
		}

		return $step;
	}

	function getStepMenu()
	{
		static $data = array();
		if (!$data)
		{
			$steps = array(1 =>
				explode(':', __('Step 1: Locale')),
				explode(':', __('Step 2: Checking server configuration')),
				explode(':', __('Step 3: File and Folder Permissions Test')),
				explode(':', __('Step 4: Database Settings')),
				explode(':', __('Step 5: Head Admin Details')),
				explode(':', __('Step 6: Final Settings'))
			);

			$data = array(); $i = 0;
			foreach($steps as $id => $name)
			{
				$data[$i] = array(
					'id' => $id,
					'name' => $name[0]
				);

				if (getStepNum() === $id)
				{
					$data[$i]['current'] = TRUE;
				}
				else if (getStepNum() > $id)
				{
					$data[$i]['done'] = TRUE;
				}

				$i++;
			}
		}

		return $data;
	}

	function optLocale(optClass &$_tpl, $key, array $values = array())
	{
		return __($key, $values);
	}

	$_tpl->registerFunction('i18n', 'Locale');

	// Przechodzi do wybranego etapu instalacji z odświeżeniem strony
	function goToStep($step)
	{
		if (is_numeric($step) && $step)
		{
			$_SESSION['step'] = intval($step);

		}
		else
		{
			$_SESSION['step'] = 1;
		}

		exit('<script type="text/javascript">window.location = window.location.href;</script>');
	}

	// Przerywa bieżącą instalację z informacją dla użytkownika
	function abortInstall()
	{
		$_SESSION['step'] = 'abort';
		header('Refresh: 0; url='.ADDR_SITE.'install/');
		exit;
	}

	// Restartuje instalację bez informacji dla użytkownika
	function restartInstall()
	{
		unset($_SESSION['step']);
	}

	// Przechodzi do strony głównej
	function goToPage()
	{
		header('Location: '.ADDR_SITE);
		exit;
	}

	$_tpl->assignGroup(array(
		'title' => __('eXtreme-Fusion :version - Setup', array(':version' => VERSION)),
		'charset' => __('Charset'),
		'ADDR_ADMIN' => ADDR_SITE.'admin/',
		'ADDR_SITE' => ADDR_SITE,
		'ADDR_INSTALL' =>  ADDR_SITE.'install/',
		'step_header' => __('eXtreme-Fusion :version - Setup', array(':version' => VERSION)).(getStepHeader() ? ' &raquo; '.getStepHeader() : ''),
		'step_menu' => getStepMenu(),
		'step' => getStepNum(),
		'php_required' => PHP_REQUIRED
	));

	// Inicjacja przerwania instalacji
	if (isset($_GET['abort']))
	{
		abortInstall();
	}

	// Przerwanie instalacji
	if (getStepNum() === 0)
	{
		restartInstall();
	}
	elseif (getStepNum() === 1)
	{
		if ($_POST && isset($_POST['step']))
		{
			goToStep(2);
		}
		else
		{
			$_files = new Files;
			$_tpl->assign('languages', Html::getSelectOpts($_files->createFileList(DIR_SITE.'locale'.DS, array(), TRUE, 'folders'), $_SESSION['localeset']));
		}
	}
	elseif (getStepNum() === 2)
	{
		if (Server::getPHPVersionID() < Server::createPHPVersionID(PHP_REQUIRED))
		{
			$_tpl->assign('php_version_error', TRUE);
		}
		else
		{
			$extension_error = array();

			if ( ! extension_loaded('pdo'))
			{
				$extension_error[]['name'] = 'pdo';
			}

			if ( ! extension_loaded('pdo_mysql'))
			{
				$extension_error[]['name'] = 'pdo_mysql';
			}

			if ( ! extension_loaded('mcrypt'))
			{
				$extension_error[]['name'] = 'mcrypt';
			}

			if ($extension_error)
			{
				$_tpl->assign('extension_error', $extension_error);
			}
			else
			{
				goToStep(3);
			}
		}
	}
	else if (getStepNum() === 3)
	{
		$config_error = FALSE;

		if ( ! file_exists(DIR_SITE.'config.php'))
		{
			if (file_exists(DIR_SITE.'sample.config.php') && function_exists('rename'))
			{
				@rename(DIR_SITE.'sample.config.php', DIR_SITE.'config.php');
			}
			else
			{
				$handle = fopen(DIR_SITE.'config.php', 'w');
				fclose($handle);
			}

			if (! file_exists(DIR_SITE.'config.php'))
			{
				$config_error = TRUE;
			}
		}

		if ($config_error)
		{
			$_tpl->assign('config_error', TRUE);
		}

		$check_arr = array(
			DIR_SITE.'cache'.DS => FALSE,
			//DIR_SITE.'upload'.DS => FALSE,
			//DIR_SITE.'upload'.DS.'archives'.DS => FALSE,
			//DIR_SITE.'upload'.DS.'documents'.DS => FALSE,
			DIR_SITE.'upload'.DS.'images'.DS => FALSE,
			//DIR_SITE.'upload'.DS.'movies'.DS => FALSE,
			DIR_SITE.'system'.DS.'opt'.DS.'plugins'.DS => FALSE,
			DIR_SITE.'templates'.DS.'images'.DS => FALSE,
			//DIR_SITE.'templates'.DS.'images'.DS.'imagelist.js' => FALSE,
			DIR_SITE.'templates'.DS.'images'.DS.'avatars'.DS => FALSE,
			DIR_SITE.'templates'.DS.'images'.DS.'news'.DS => FALSE,
			DIR_SITE.'templates'.DS.'images'.DS.'news'.DS.'thumbs'.DS => FALSE,
			DIR_SITE.'templates'.DS.'images'.DS.'news_cats'.DS => FALSE,
			DIR_SITE.'tmp'.DS => FALSE,
			DIR_SITE.'config.php' => FALSE
		);

		$write_check = TRUE; $chmod_error = array(); $i = 0;
		foreach ($check_arr as $key => $value)
		{
			$write_check = TRUE;
			if (file_exists($key))
			{
				if (is_writable($key))
				{
					$check_arr[$key] = TRUE;
					$i++;
				}
				else
				{
					if (function_exists('chmod') && @chmod($key, 0777) && is_writable($key))
					{
						$check_arr[$key] = TRUE;
						$i++;
					}
					else
					{
						$write_check = FALSE;
					}
				}
			}
			else
			{
				$write_check = NULL;
			}

			if (! $write_check)
			{
				$chmod_error[] = array(
					'name' => str_replace(array(DIR_SITE, '\\'), array('', '/'), $key),
					'status' => $write_check === NULL ? 1 : 2
				);
			}
		}

		if ($chmod_error)
		{
			$_tpl->assign('chmod_error', $chmod_error);
		}

		if (!$config_error && ! $chmod_error)
		{
			goToStep(4);
		}
	}
	else if (getStepNum() === 4)
	{
		// Sprawdzanie danych w przypadku próby ich zapisania
		if ($_POST)
		{
			function lastChar($string)
			{
				return $string[strlen($string)-1];
			}

			$db_host = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
			// todo: sprawdzanie czy numeryczny
			$db_port = isset($_POST['db_port']) ? trim($_POST['db_port']) : '';
			
			$db_user = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
			$db_pass = isset($_POST['db_pass']) ? trim($_POST['db_pass']) : '';
			$db_name = isset($_POST['db_name']) ? trim($_POST['db_name']) : '';
			
			$db_prefix = isset($_POST['db_prefix']) ? trim(HELP::strip($_POST['db_prefix'])) : '';
			$cookie_prefix = isset($_POST['cookie_prefix']) ? trim(HELP::strip($_POST['cookie_prefix'])) : '';
			$cache_prefix = isset($_POST['cache_prefix']) ? trim(HELP::strip($_POST['cache_prefix'])) : '';
			
			// todo: filtrowanie aresu do strony
			$site_url = isset($_POST['site_url']) ? $_POST['site_url'] : '';

			$custom_rewrite_choice = isset($_POST['custom_rewrite']) ? 'TRUE' : NULL;
			$custom_furl_choice = isset($_POST['custom_furl']) ? 'TRUE' : NULL;

			if ($db_prefix !== '' && lastChar($db_prefix) !== '_')
			{
				$db_prefix = $db_prefix.'_';
			}

			if ($cookie_prefix !== '' && lastChar($cookie_prefix) !== '_')
			{
				$cookie_prefix = $cookie_prefix.'_';
			}

			if ($cache_prefix !== '' && lastChar($cache_prefix) !== '_')
			{
				$cache_prefix = $cache_prefix.'_';
			}

			$success = FALSE;
			
			// db_prefix jest opcjonalny!
			if ($db_host !== '' && $db_user !== '' && $db_name !== '' && $db_port !== '' && $site_url !== '')
			{
				try
				{
					$_pdo = new Data('mysql:host='.$db_host.';dbname='.$db_name.';port='.$db_port, $db_user, $db_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$charset));
					$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
					$_pdo->config($db_prefix);
					
					// For php < 5.3.1: http://stackoverflow.com/a/4348744/1794927
					$_pdo->query('SET NAMES '.$charset, NULL, FALSE);
				
					if (!$d = $_pdo->query("SHOW TABLES LIKE '$db_prefix%'"))
					{
						$table_name = $db_prefix.substr(strrev(time()), 0, 5);
						$can_write = TRUE;

						try
						{
							$result = $_pdo->query('CREATE TABLE `'.$table_name.'` (`test_field` VARCHAR(10) NOT NULL) ENGINE=InnoDB CHARACTER SET '.$charset.' COLLATE '.$collate, NULL, FALSE);
							if (!$result)
							{
								throw new PDOException();
							}

							$result = $_pdo->query('DROP TABLE '.$table_name, NULL, FALSE);
							if (!$result)
							{
								throw new PDOException();
							}

							include_once 'create_config.php';

							$temp = fopen(DIR_SITE.'config.php','w');
							if (fwrite($temp, $config))
							{
								fclose($temp);
								$fail = FALSE;

								try
								{
									$result = $_pdo->query("ALTER DATABASE  `".$db_name."` DEFAULT CHARACTER SET ".$charset." COLLATE ".$collate, NULL, FALSE);

									include_once 'create_db.php';
									if (!$fail)
									{
										$_tpl->assign('success_info', TRUE);

										$success = TRUE;
									}
									else
									{
										throw new PDOException();
									}
								}
								catch (PDOException $e)
								{
									$_tpl->assign('table_creating_error', TRUE);
								}
							}
							else
							{
								$_tpl->assign('config_write_error', TRUE);
							}
						}
						catch (PDOException $e)
						{
							$_tpl->assign('database_permission_error', TRUE);
							$can_write = FALSE;
						}
					}
					else
					{
						$_tpl->assign('table_prefix_error', TRUE);
					}
				}
				catch (PDOException $e)
				{
					$_tpl->assign('database_connection_error', TRUE);
				}
			}
			else
			{
				$_tpl->assign('empty_form_error', TRUE);
			}

			if ($success)
			{
				goToStep(5);
			}
			else
			{
				$_tpl->assignGroup(array(
					'db_name' => $db_name,
					'db_user' => $db_user,
					'db_host' => $db_host,
					'db_port' => $db_port,
					'db_prefix' => $db_prefix,
					'cookie_prefix' => $cookie_prefix,
					'cache_prefix' => $cache_prefix,
					'site_url' => $site_url,
					'custom_rewrite_choice' => $custom_rewrite_choice,
					'custom_furl_choice' => $custom_furl_choice
				));
			}
		}
		// Przygotowanie formularza do uzupełniania
		else
		{
			$db_host = 'localhost';

			// The port may be required! So here we set the default.
			$db_port = 3306;

			$db_prefix = 'extreme_'.substr(md5(uniqid('ef5_db', FALSE)), 13, 7).'_';
			$cookie_prefix = 'extreme_'.substr(md5(uniqid('ef5_cookie', FALSE)), 13, 7).'_';
			$cache_prefix = 'extreme_'.substr(md5(uniqid('ef5_cache', FALSE)), 13, 7).'_';

			$_tpl->assignGroup(array(
				'db_host' => $db_host,
				'db_port' => $db_port,
				'db_prefix' => $db_prefix,
				'cookie_prefix' => $cookie_prefix,
				'cache_prefix' => $cache_prefix,
				'site_url' => ADDR_SITE
			));
		}

		if ($_system->httpServerIs('Apache'))
		{
			if (!$_system->apacheModulesListingAvailable())
			{
				$_tpl->assign('custom_rewrite', TRUE);
			}
		}
		elseif (!$_system->serverPathInfoExists())
		{
			$_tpl->assign('custom_furl', TRUE);
		}
	}
	else if (getStepNum() === 5)
	{
		if ($_POST)
		{
			$username = isset($_POST['username']) ? trim($_POST['username']) : '';
			$password1 = isset($_POST['password1']) ? trim($_POST['password1']) : '';
			$password2 = isset($_POST['password2']) ? trim($_POST['password2']) : '';
			$email = isset($_POST['email']) ? trim(HELP::strip($_POST['email'])) : '';

			$error = FALSE;

			if ($username === '' || $email === '' || $password1 === '' || $password2 === '')
			{
				$_tpl->assign('empty_form_error', TRUE);
				$error = TRUE;
			}
			else
			{
				if (preg_match("#[^\w\d-]+#i", $username))
				{
					$_tpl->assign('username_error', TRUE);
					$error = TRUE;
				}

				if ($password1 !== $password2)
				{
					$_tpl->assign('password_error', TRUE);
					$error = TRUE;
				}

				if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$_tpl->assign('email_error', TRUE);
					$error = TRUE;
				}
			}

			if (!$error)
			{
				require DIR_SITE.'config.php';
				if (file_exists(DIR_SITE.'cache'.DS))
				{
					$_system->clearCache(NULL, array(), DIR_SITE.'cache'.DS);
				}

				$_pdo = new Data('mysql:host='.$_dbconfig['host'].';dbname='.$_dbconfig['database'].';port='.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$_dbconfig['charset']));
				$_pdo->config($_dbconfig['prefix']);
				
				// For php < 5.3.1: http://stackoverflow.com/a/4348744/1794927
				$_pdo->query('SET NAMES '.$_dbconfig['charset'], NULL, FALSE);
				
				$localeset = $_SESSION['localeset'];
				include 'create_settings.php';

				// Sprawdzanie, czy konto admina zostało utworzone
				if ($rows = $_pdo->getField('SELECT `id` FROM [users] WHERE `id` = 1'))
				{
					// Nadawanie bezpiecznych uprawnień dla pliku config.php
					if (function_exists('chmod')) { @chmod(DIR_SITE.'config.php', 0644); }

					goToStep(6);
				}
				else
				{
					$_tpl->assign('account_creating_error', TRUE);
					$error = TRUE;
				}
			}

			if ($error)
			{
				$_tpl->assignGroup(array(
					'username' => $username,
					'email' => $email
				));
			}
		}
		else
		{
			// Komunikat, że baza danych została skonfigurowana
			$_tpl->assign('show_info', TRUE);
		}
	}
	else if (getStepNum() === 6)
	{
		if ($_POST)
		{
			restartInstall();
			goToPage();
		}

	}

	$_tpl->parse('index.tpl');
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
