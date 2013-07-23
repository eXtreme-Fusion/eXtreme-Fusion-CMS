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

require_once '../config.php';
require_once DIR_SITE.'bootstrap.php';





require_once DIR_SYSTEM.'sitecore.php';
class Router
{
	protected
	// OBIEKTY
		$_sett,						// Obiekt klasy Settings
		$_url,
		// USTAWIENIA
		$_sep,
		$_param_sep = '-',			/**********TEST*********/
		$_ext,						// Rozszerzenia plików
		$_rewrite;
		
	public function __construct($_sett, $rewrite, $path_info_exists)
	{
		$this->_sett = $_sett;

		$this->_rewrite = $rewrite;
		$this->_sep = $this->_sett->getUns('routing', 'main_sep');
		$this->_param_sep = $this->_sett->getUns('routing', 'param_sep');

		$this->_ext = array(
			'log' => $this->_sett->getUns('routing', 'logic_ext'),
			'tpl' => $this->_sett->getUns('routing', 'tpl_ext'),
			'url' => $this->_sett->getUns('routing', 'url_ext')
		);

		$this->_url = new URL($this->_ext['url'], $this->_sep, $this->_param_sep, $this->_rewrite, $path_info_exists);
		$this->_ext_allowed = $this->_url->extAllowed();
		
	}
	public function getFilename()
	{
		return 'news';
	}
	
	public function path($ret)
	{
		$this->_url->path($ret);
	}
}

$_route = new Router($_sett, $_system->rewriteAvailable(), $_system->pathInfoExists());
StaticContainer::register('route', $_route);
// Wczytywanie głównej klasy
require_once DIR_CLASS.'Themes.php';

$file = $_request->get('file')->strip();
// Tworzenie emulatora statyczności klasy OPT
TPL::build($_theme = new Theme($_sett, $_system, $_user, $_pdo, $_request, $_route, $_head, $file));
require_once DIR_THEME.'core'.DS.'theme.php';
$_tpl = new SiteAjax;

try
{
	

	if (file_exists(DIR_AJAX.DS.$file.'.php'))
	{
		require_once DIR_AJAX.DS.$file.'.php';
	}
	else
	{
		throw new systemException('Plik '.$file.' nie został odnaleziony w katalogu <span class="italic">/ajax/</span>.');
	}

	// Metoda załaduje plik TPL jeśli istnieje w szablonie lub katalogu ajax.
	// Jeśli nie istnieje, to nie zwróci żadnej wartości.
	$_tpl->template($file.'.tpl', $_tpl->themeTplExists($file.'.tpl'));
}
catch(optException $exception)
{
	optErrorHandler($exception, FALSE);
}
catch(systemException $exception)
{
	systemErrorHandler($exception, FALSE);
}
catch(userException $exception)
{
	userErrorHandler($exception, FALSE);
}
catch(PDOException $exception)
{
    PDOErrorHandler($exception, FALSE);
}
