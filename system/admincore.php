<?php defined('EF5_SYSTEM') || exit;
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
	require_once DIR_CLASS.'Exception.php';
	
	if( ! extension_loaded('pdo')) 
	{
		throw new systemException('PDO "pdo" extension is required! Please turn it on in your php.ini.');
	}
	
	if( ! extension_loaded('pdo_mysql')) 
	{
		throw new systemException('PDO "pdo_mysql" extension is required! Please turn it on in your php.ini');
	}
	
	if ( ! isset($_GET['NoOPT']))
	{
		require_once OPT_DIR.'opt.class.php';
		require_once DIR_CLASS.'Parser.php';
	}

	require_once DIR_SYSTEM.'helpers/main.php';
	
    ob_start();

	# System configuration
    $_system = new System;

    # PHP Data Object
    $_pdo = new Data('mysql:host='.$_dbconfig['host'].';dbname='.$_dbconfig['database'].';port='.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$_dbconfig['charset']));
    
	$_pdo->config($_dbconfig['prefix']);
    unset($_dbconfig);
	require_once DIR_SYSTEM.'table_list.php';
	
	# Settings
    $_sett = new Sett($_system, $_pdo);

	# Members
    $_user = new User($_sett, $_pdo);

	# Admin action logger
    $_log = new Logger($_user, $_pdo, $_sett->get('logger_active'));

	# Language files
    $_locale = new Locales($_sett->get('locale'), DIR_LOCALE);
	$_locale->setSubDir('admin');
	
	# Requests
	$_request = new Request;
	
	# Files class
	$_files = new Files;

	$_url = new Url($_sett->getUns('routing', 'url_ext'), $_sett->getUns('routing', 'main_sep'), $_sett->getUns('routing', 'param_sep'), $_system->rewriteAvailable(), $_system->pathInfoExists());
		
	HELP::init($_pdo, $_sett, $_user, $_url);

	define('URL_REQUEST', isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '' ? HELP::cleanurl($_SERVER['REQUEST_URI']) : $_SERVER['SCRIPT_NAME']);
	define('URL_QUERY', isset($_SERVER['QUERY_STRING']) ? HELP::cleanurl($_SERVER['QUERY_STRING']) : '');
	
    # Timezone settings
    date_default_timezone_set($_sett->get('timezone'));

	# Admin login session
	
    if (isset($_SESSION['admin']))
    {
        $_user->adminLoggedIn($_SESSION['admin']['id'], $_SESSION['admin']['hash']);
		$_locale->load('admin');
    }

	#Parser
	Parser::config($_pdo, $_sett, $_user, $_request, $_log);

	define('iGUEST', $_user->iGUEST());
    define('iUSER', $_user->iUSER());
    define('iADMIN', $_user->iADMIN());
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