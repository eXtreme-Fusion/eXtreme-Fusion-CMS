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
error_reporting(E_ALL | E_STRICT);

// Instalowana wersja systemu - wyświetlana w nagłówku nawigacji
define('VERSION', '5 Beta 6');

$HostURL = explode('install', $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

define('DS', DIRECTORY_SEPARATOR);

define('DIR_BASE', dirname(__FILE__).DS);
define('DIR_SITE', realpath(DIR_BASE.'../').DS);

define('ADDR_SITE', 'http://'.$HostURL[0]);

require DIR_SITE.'bootstrap.php';

/** Templates init **/
require_once OPT_DIR.'opt.class.php';

$_tpl = new optClass;
$_tpl->compile = DIR_CACHE;
$_tpl->root = DIR_BASE.'templates'.DS;
/***/

require DIR_SITE.'system'.DS.'helpers'.DS.'main.php';

$_system = new System;

define('PHP_REQUIRED', Server::getRequiredPHPVersion());

// Zapisywanie języka zapamiętanego dzięki sesji
if (isset($_SESSION['localeset']))
{
	$language = $_SESSION['localeset'];
}
// Zapisywanie języka na podstawie informacji pochodzących z przeglądarki
else
{
	$language = $_system->detectBrowserLanguage(TRUE);
}

/**
 * Można by wybrany z listy język zapisać do $language i sprawdzić dostępność kodem
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
	if (file_exists(DIR_SITE.'locale'.DS.$language) && is_dir(DIR_SITE.'locale'.DS.$language))
	{
		$_SESSION['localeset'] = $language;
	}
	else
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

$charset = 'utf8';
$collate = 'utf8_general_ci';

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
			$header = __('Step 5: Head Admin Datails');
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
			explode(':', __('Step 5: Head Admin Datails')),
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

	header('Refresh: 0');
	exit;
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
else if (getStepNum() === 1)
{
	if ($_POST)
	{
		goToStep(2);
	}
	else
	{
		$_tpl->assign('languages', makefileopts(makefilelist(DIR_SITE.'locale/', '.gitignore|.svn|.|..', TRUE, 'folders'), $language));
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
		DIR_SITE.'upload'.DS => FALSE,
		DIR_SITE.'upload'.DS.'archives'.DS => FALSE,
		DIR_SITE.'upload'.DS.'documents'.DS => FALSE,
		DIR_SITE.'upload'.DS.'images'.DS => FALSE,
		DIR_SITE.'upload'.DS.'movies'.DS => FALSE,
		DIR_SITE.'system'.DS.'opt'.DS.'plugins'.DS => FALSE,
		DIR_SITE.'templates'.DS.'images'.DS => FALSE,
		DIR_SITE.'templates'.DS.'images'.DS.'imagelist.js' => FALSE,
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

		$db_host = (isset($_POST['db_host']) ? stripinput(trim($_POST['db_host'])) : '');
		$db_port = (isset($_POST['db_port']) ? stripinput(trim($_POST['db_port'])) : '');
		$db_user = (isset($_POST['db_user']) ? stripinput(trim($_POST['db_user'])) : '');
		$db_pass = (isset($_POST['db_pass']) ? stripinput(trim($_POST['db_pass'])) : '');
		$db_name = (isset($_POST['db_name']) ? stripinput(trim($_POST['db_name'])) : '');
		$db_prefix = (isset($_POST['db_prefix']) ? stripinput(trim($_POST['db_prefix'])) : '');
		$cookie_prefix = (isset($_POST['cookie_prefix']) ? stripinput(trim($_POST['cookie_prefix'])) : '');
		$cache_prefix = (isset($_POST['cache_prefix']) ? stripinput(trim($_POST['cache_prefix'])) : '');
		$site_url = (isset($_POST['site_url']) ? $_POST['site_url'] : '');

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

		// db_prefix powinien być opcjonalny!
		if ($db_host !== '' && $db_user !== '' && $db_name !== '' && $db_port !== '' && $site_url !== '')
		{
			$db_connect = @mysql_connect($db_host.':'.$db_port, $db_user, $db_pass);
			if ($db_connect)
			{
				$db_select = @mysql_select_db($db_name);
				if ($db_select)
				{
					if (dbrows(dbquery("SHOW TABLES LIKE '$db_prefix%'")) == '0')
					{
						$table_name = uniqid($db_prefix, FALSE);
						$can_write = TRUE;

						$result = dbquery('CREATE TABLE '.$table_name.' (test_field VARCHAR(10) NOT NULL) ENGINE=MyISAM;');
						if (!$result)
						{
							$can_write = FALSE;
						}

						$result = dbquery('DROP TABLE '.$table_name);
						if (!$result)
						{
							$can_write = FALSE;
						}

						if ($can_write)
						{
							include_once 'create_config.php';

							$temp = fopen(DIR_SITE.'config.php','w');
							if (fwrite($temp, $config))

							{
								fclose($temp);
								$fail = FALSE;

								$result = dbquery("ALTER DATABASE  `".$db_name."` DEFAULT CHARACTER SET ".$charset." COLLATE ".$collate);

								include_once 'create_db.php';

								if (!$fail)
								{
									$_tpl->assign('success_info', TRUE);

									$success = TRUE;
									$db_error = 6;
								}
								else
								{
									$_tpl->assign('table_creating_error', TRUE);

									$success = FALSE;
									$db_error = 0;
								}
							}
							else
							{
								$_tpl->assign('config_write_error', TRUE);
								$success = FALSE;
								$db_error = 5;
							}
						}
						else
						{
							$_tpl->assign('database_permission_error', TRUE);

							$success = FALSE;
							$db_error = 4;
						}
					}
					else
					{
						$_tpl->assign('table_prefix_error', TRUE);


						$success = FALSE;
						$db_error = 3;
					}
				}
				else
				{
					$_tpl->assign('database_connection_error', TRUE);


					$success = FALSE;
					$db_error = 2;
				}
			}
			else
			{
				$_tpl->assign('server_connection_error', TRUE);


				$success = FALSE;
				$db_error = 1;
			}
		}
		else
		{
			$_tpl->assign('empty_form_error', TRUE);

			$success = FALSE;
			$db_error = 7;
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
				'db_error' => $db_error,
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
		$db_port = '3306';

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
		$username = (isset($_POST['username']) ? stripinput(trim($_POST['username'])) : '');
		$password1 = (isset($_POST['password1']) ? stripinput(trim($_POST['password1'])) : '');
		$password2 = (isset($_POST['password2']) ? stripinput(trim($_POST['password2'])) : '');
		$email = (isset($_POST['email']) ? stripinput(trim($_POST['email'])) : '');


		$error = FALSE;

		if ($username === '' || $email === '' || $password1 === '' || $password2 === '')
		{
			$_tpl->assign('empty_form_error', TRUE);
			$error = TRUE;
		}
		else
		{
			if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username))
			{
				$_tpl->assign('username_error', TRUE);
				$error = TRUE;
			}

			if ($password1 != $password2)
			{
				$_tpl->assign('password_error', TRUE);
				$error = TRUE;
			}

			if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email))
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

			$dbconnect = dbconnect($_dbconfig['host'].':'.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], $_dbconfig['database']);

			$localeset = $_SESSION['localeset'];
			include 'create_settings.php';

			// Sprawdzanie, czy konto admina zostało utworzone
			if ($rows = rowCount($_dbconfig['prefix'].'users', '`id`'))
			{
				// Nadawanie bezpie3cznych uprawnień dla pliku config.php
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

// mySQL database functions
function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	global $db_connect;

	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	dbquery('SET NAMES utf8');
	if (!$db_connect) {
		return FALSE;
	} else {
		return TRUE;
	}
}

function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return FALSE;
	} else {
		return $result;
	}
}

function dbrows($query) {
	$result = @mysql_num_rows($query);
	return $result;
}

function rowCount($table, $field, $conditions = '')
{
	$cond = ($conditions ? ' WHERE '.$conditions : '');
	$result = mysql_query('SELECT Count('.$field.') FROM '.$table.$cond);

	return mysql_result($result, 0);
}

/**
 * Dodawanie rekordów
 *
 *@copyright Clear-PHP.com
 *
 * Przykład użycia:
 * 		$result = $sql_manager->insert('test', array('fgfg' => '34', 'baza' => 'sdsd'));
 *
 * @param string $table, array $fields
 * @return number of modified records
 */

function insert($table = null, $fields = null)
{
	// Sprawdzanie, czy parametry nie zostały pominięte, a zmienna $fields jest tablicą
	if (is_null($table) || is_null($fields) || !is_array($fields))
	{
		return FALSE;
	}

	$keys = implode('`, `', array_keys($fields));
	$values = implode("', '", array_values($fields));

	return dbquery("INSERT INTO ".$table." (`".$keys."`) VALUES ('".$values."')");

} // end of insert();


// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=TRUE, $type='files') {
	$res = array();
	$filter = explode('|', $filter);
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == 'files' && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == 'folders' && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = '') {
	$res = '';
	for ($i=0; $i < count($files); $i++) {
		$sel = ($selected == $files[$i] ? ' selected="selected"' : '');
		$res .= '<option value="'.$files[$i].'"'.$sel.'>'.$files[$i].'</option>\n';
	}
	return $res;
}

if (isset($db_connect) && $db_connect != FALSE) { mysql_close($db_connect); }
