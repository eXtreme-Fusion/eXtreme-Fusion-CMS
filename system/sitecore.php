<?php preg_match("/sitecore.php/i", $_SERVER['PHP_SELF']) == FALSE || exit;
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
	function optUrl(optClass &$_tpl)
	{
		$value = func_get_args();
		unset($value[0]);
		
		if ($value)
		{	
			$ret = array(); $i = 0; $id = NULL;
			foreach($value as $array)
			{
				$data = explode('=>', $array);
				
				//return count($data);
				if (count($data) == 2)
				{
					$id = trim($data[0]);
					//$val = trim($data[1]);
				}
				else
				{
					if ($id)
					{
						$ret[$id] = trim($data[0]);
					}
					else
					{
						$ret[$i] = trim($data[0]);
						$i++;
					}

					$id = FALSE;
				}
			}
			
			
			if ($ret)
			{
				if (method_exists($_tpl, 'route'))
				{
					return $_tpl->route()->path($ret);
				}
				else
				{
					return $_url->path($ret);
				}
			}
		}
		else
		{
			if (method_exists($_tpl, 'route'))
			{
				return $_tpl->route()->path($value);
			}
			else
			{
				return $_url->path($value);
			}
		}
		
		return '';
	}
	
	error_reporting(E_ALL | E_NOTICE);

	if ( ! defined('__DIR__')) define('__DIR__', dirname(__FILE__));

	if ( ! file_exists(__DIR__.'/../config.php'))
	{
		if (file_exists(__DIR__.'/../install/'.DIRECTORY_SEPARATOR))
		{
			header('Location: install/');
		}
		else
		{
			die('Config file does not exists. Please upload config.php again.');
		}
	}
	else
	{
		require_once __DIR__.'/../config.php';
		require DIR_SITE.'bootstrap.php';
	}
	
	require_once DIR_CLASS.'exception.php';
	
	if( ! extension_loaded('pdo')) 
	{
		throw new systemException('PDO "pdo" extension is required! Please turn it on in your php.ini.');
	}
	
	if( ! extension_loaded('pdo_mysql')) 
	{
		throw new systemException('PDO "pdo_mysql" extension is required! Please turn it on in your php.ini');
	}
	
	require_once DIR_SYSTEM.'helpers/main.php';
	require_once OPT_DIR.'opt.class.php';
	require_once DIR_CLASS.'parser.php';
	require_once DIR_CLASS.'robots.php';

    ob_start();

    $_system = new System;
	
	//$_system->setEnv();

	# PHP Data Object
    $_pdo = new PDO_EXT('mysql:host='.$_dbconfig['host'].';dbname='.$_dbconfig['database'].';port='.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$_dbconfig['charset']));
    $_pdo->config($_dbconfig['prefix']);
	unset($_dbconfig);
	require_once DIR_SYSTEM.'table_list.php';
    $_sett = new Sett($_system, $_pdo);
    $_user = new User($_sett, $_pdo);
    $_locale = new Locales($_sett->get('locale'), DIR_LOCALE);

	// $_robots = New detectRobots();

	// Fetch the Site Settings from the database and store them in the $settings variable
    $settings = $_sett->get();

	define('URL_REQUEST', isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '' ? HELP::cleanurl($_SERVER['REQUEST_URI']) : $_SERVER['SCRIPT_NAME']);
	define('URL_QUERY', isset($_SERVER['QUERY_STRING']) ? HELP::cleanurl($_SERVER['QUERY_STRING']) : '');

	# Requests
	$_request = new Request;

	# Files class
	$_files = new Files;
	
	$_url = new URL($_sett->getUns('routing', 'url_ext'), $_sett->getUns('routing', 'main_sep'), $_sett->getUns('routing', 'param_sep'), $_system->rewriteAvailable(), $_system->pathInfoExists());
		
	# Helper class
	HELP::init($_pdo, $_sett, $_user, $_url);

	if ($_request->post('login')->show() && $_request->post('username')->show() && $_request->post('password')->show())
	{
		// Sprawdzanie danych logowania
		if ( ! $_user->userLogin($_request->post('username')->show(), $_request->post('password')->show(), $_request->post('remember_me')->show() ? TRUE : FALSE))
		{
			$_user->setTryLogin(TRUE);
		}

		HELP::reload(0);
	}

	// Załączenie wymaganych plików
	require_once DIR_SYSTEM.'table_list.php';

    if ( ! count($settings))
    {
		if (file_exists('../install'.DIRECTORY_SEPARATOR))
		{
			HELP::redirect('../install/');
		}
		else
		{
			die('The "Settings" table does not exist. Please reinstall.');
		}
    }

	#Parser
	Parser::config($_pdo, $_sett, $_user, $_request);

    // Settings dependent functions
    date_default_timezone_set($_sett->get('timezone'));

	# User login session
	if (strpos($_SERVER['PHP_SELF'], 'admin') === FALSE)
	{
		if (isset($_SESSION['user']))
		{
			$_user->userLoggedInBySession($_SESSION['user']['id'], $_SESSION['user']['hash']);
		}
		elseif (isset($_COOKIE['user']))
		{
			$_user->userLoggedInByCookie($_COOKIE['user']);
		}
	}

	if ($_user->iUSER())
	{
		if ($_user->get('theme') !== 'Default' && $_sett->get('userthemes') === 1)
		{
			if ( ! HELP::theme_exists($_user->get('theme')))
			{
				throw new systemException('Szablon <span class="italic">'.$_user->get('theme').'</span> nie istnieje.');
			}
		}
		else
		{
			if ( ! HELP::theme_exists($_sett->get('theme')))
			{
				throw new systemException('Szablon <span class="italic">'.$_sett->get('theme').'</span> nie istnieje.');
			}
		}

		// Aktualizacja aktywności w profilu użytkownika
		$_user->updateVisitTime($_user->get('id'));
	}
	else
	{
		if ( ! HELP::theme_exists($_sett->get('theme')))
		{
			throw new systemException('Szablon <span class="italic">'.$_sett->get('theme').'</span> nie istnieje.');
		}
	}

	// Aktualizacja czasu ostatniej wizyty
	$_user->updateActivity();

    define('iGUEST', $_user->iGUEST());
    define('iUSER', $_user->iUSER());
    define('iADMIN', $_user->iADMIN());

	$_head  = new Header;

	# Comments
	$_comment = new Comment(new Basic, $_pdo, $_user, $_sett, $_head);

    // Zadania Cron
    // Cron-Jobs
    require_once DIR_SITE.'cronjob.php';
}
catch(systemException $exception)
{
    die(systemErrorHandler($exception));
}
catch(userException $exception)
{
    die(userErrorHandler($exception));
}
catch(PDOException $exception)
{
	die(PDOErrorHandler($exception));
}