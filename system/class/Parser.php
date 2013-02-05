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

class Parser extends optClass
{
	protected static
		$_pdo,
		$_sett,
		$_user,
		$_request,
		$_log;

	public function __construct()
	{
		throw new systemException('For Parser class object can not exists.');
	}

	public static function config(Data $pdo, Sett $sett, User $user, $request, $log = '')
	{
		self::$_pdo = $pdo;
		self::$_sett = $sett;
		self::$_user = $user;
		self::$_log = $log;
		self::$_request = $request;
	}

	protected function loadSystem()
	{
		$this->plugins = OPT_DIR.'plugins'.DS;
		$this->gzipCompression = FALSE;
		$this->registerFunction('i18n', 'Locale');
		if (function_exists('optUrl'))
		{
			$this->registerFunction('url', 'Url');
		}
		$this->httpHeaders(OPT_HTML);
		$this->assignMain();
	}

	public function setHistory($__file__)
	{
		$_SESSION['history']['Page'] = str_replace(array(DIR_SITE, '\\'), array('', '/'), $__file__).($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '');
	}

	private function assignMain()
	{
		$this->assign('FILE_SELF', FILE_SELF);
		$this->assign('URL_REQUEST', URL_REQUEST);

		$this->assign('ADDR_SITE', ADDR_SITE);
		$this->assign('ADDR_ADMIN', ADDR_ADMIN);
		$this->assign('ADDR_IMAGES', ADDR_IMAGES);
		$this->assign('ADDR_ADMIN_IMAGES', ADDR_ADMIN_IMAGES);
		$this->assign('NEWS_CAT_IMAGES', ADDR_IMAGES.'news_cats/'.self::$_sett->get('locale').'/');
		$this->assign('ADDR_JS', ADDR_JS);
		$this->assign('ADDR_COMMON_JS', ADDR_COMMON_JS);

		if (file_exists(DIR_SITE.'themes'.DS.self::$_sett->get('theme').DS.'templates'.DS.'images'.DS.'favicon.ico'))
		{
			$this->assign('ADDR_FAVICON', ADDR_SITE.'themes/'.self::$_sett->get('theme').'/templates/images/favicon.ico');
		}
		else
		{
			$this->assign('ADDR_FAVICON', ADDR_ADMIN_IMAGES.'favicon.ico');
		}

		$this->assign('SystemVersion', 'eXtreme-Fusion '.self::$_sett->get('version'));

		// Zwraca dane zalogowanego użytkownika
		$this->assign('user', self::$_user->get());

		$this->assign('isLoggedIn', self::$_user->isLoggedIn());

		if (self::$_user->iADMIN())
		{
			$this->assignGroup(array(
				'iADMIN' => TRUE,
				'iUSER'  => TRUE
			));
		}
		elseif (self::$_user->iUSER())
		{
			$this->assign('iUSER', TRUE);
		}
		else
		{
			$this->assign('iGUEST', TRUE);
		}

		if (self::$_request->get('action')->show())
		{
			$this->assign('action', self::$_request->get('action')->show());
		}

		if (self::$_request->get('page')->show())
		{
			$this->assign('page', self::$_request->get('page')->show());
		}
	}

	public function getMessage($status, $act = NULL, array $message = array())
	{
		if ($message)
		{
			if ($act)
			{
				if (isset($message[$act]))
				{
					$message = array(
						$message[$act][0], $message[$act][1]
					);
				}
			}
		}
		else
		{
			$message = array(__('Action success'), __('Action error'));
		}

		if ($status === 'ok')
		{
			$this->assignGroup(
				array('class' => 'valid', 'message' => $message[0])
			);
		}
		else
		{
			$this->assignGroup(
				array('class' => 'error', 'message' => $message[1])
			);
		}
	}

	// Parametr trzeci ustawiony na TRUE powoduje, że pomijane są ustawienia aktywności rejestru
	public function logAndShow($status, $act, array $message, $manual_settings = FALSE)
	{
		if (isset($message[$act]))
		{
			$message = array(
				$message[$act][0], $message[$act][1]
			);

			if ($status === 'ok')
			{
				$this->assignGroup(
					array('class' => 'valid', 'message' => $message[0])
				);
			}
			else
			{
				$this->assignGroup(
					array('class' => 'error', 'message' => $message[1])
				);
			}

			self::$_log->insertAdminLog($status, $act, $message, $manual_settings);
		}
	}

	public function printMessage($type, $value)
	{
		$this->assignGroup(array(
			'class' => $type,
			'message' => $value
		));
	}

	// Tworzenie tablicy danych dla listy formularza
	// Parametr trzeci ustawiony na TRUE powoduje, że indeksy w zwróconej tablicy będą takie same, jak w źródłowej.
	// Ustawienie na FALSE powoduje, że indeksem stanie się wartość z tablicy źródłowej.
	public function createSelectOpts($data, $selected = NULL, $key_value = FALSE, $no_select_option = FALSE)
	{
		return Html::createSelectOpts($data, $selected, $key_value, $no_select_option);
	}

	// LISTA MULTI SELECT
	public function getMultiSelect($data, $selected = NULL, $show_default = TRUE)
	{
		return Html::createMultiSelect($data, $selected, $show_default);
	}
}

class pageNavParser extends optClass
{
	private $_ext = '.tpl';

	private $_route;

	public function __construct($_route = NULL)
	{
		$this->plugins = OPT_DIR.'plugins'.DS;
		$this->gzipCompression = FALSE;
		$this->registerFunction('i18n', 'Locale');
		$this->setCompilePrefix('page_nav_');
		if (function_exists('optUrl'))
		{
			$this->registerFunction('url', 'Url');
		}

		$this->httpHeaders(OPT_HTML);

		$this->root = DIR_TEMPLATES.'paging'.DS;
		$this->compile = DIR_CACHE;

		$this->_route = $_route;
	}

	public function route()
	{
		return $this->_route;
	}

	// Parametr drugi to katalog, w którym znajduje się szablon.
	// Doskonałe dla modułów, które mogą w ten sposób definiować własne szablony stronicowania,
	// używając AJAXA lub innych technik.
	public function template($filename, $dir = NULL)
	{
		if ($dir)
		{
			$this->root = $dir;
		}

		// Drugi parametr ustawiony na TRUE powoduje, że plik tymczasowy jest zawsze nadpisywany.
		// W innym przypadku zmiana szablonu stronicowania nie byłaby dynamiczna - trzeba by czekać, aż ważność pliku tpl wygasnie w OPT.
		return $this->parse($filename.$this->_ext, TRUE);
	}
}


class General extends Parser
{
	public function __construct($root)
	{

		parent::loadSystem();
		$this->root = $root;
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;
	}

	public function template($file)
	{
		return $this->parse($file);
	}
}

class Basic extends Parser
{
	public function __construct()
	{
		parent::loadSystem();
	}

	public function template($file)
	{
		$this->assign('ADDR_AJAX', ADDR_AJAX);
		return $this->parse($file);
	}
}

// Generowanie templatek dla routera AJAX.
// Tworzenie instancji dozwolone tylko w pliku /ajax/index.php.
class SiteAjax extends Parser
{
	private
		$_theme,
		$_default;

	public function __construct()
	{

		parent::loadSystem();
		$this->setCompilePrefix('site_ajax_');
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;

		$this->_theme = DIR_THEME.'templates'.DS.'ajax'.DS;
		$this->_default = DIR_AJAX.'templates'.DS;
	}

	// Metoda nie zwraca FALSE jeśli pliku nie znaleziono, ponieważ nie zawsze on istnieje dla AJAX-a
	public function template($file, $theme)
	{
		if ($theme)
		{
			$this->root = $this->_theme;
		}
		else
		{
			$this->root = $this->_default;
		}

		if (file_exists($this->root.$file))
		{
			return $this->parse($file);
		}

	}

	public function themeTplExists($file)
	{
		return file_exists($this->_theme.$file);
	}
}

// Admin pages parser
class Iframe extends Parser
{
	public function __construct()
	{
		// Local OPT configuration loader
		$this->setConfig();

		$_SESSION['history']['Page'] = 'admin/pages/'.FILE_SELF.($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '');

		if (isset($_GET['fromPage']))
		{
			$this->assign('HereIsPage', TRUE);
		}

		// Main OPT configuration && system constants loader
		parent::loadSystem();
	}

	protected function setConfig()
	{
		$this->setCompilePrefix('admin_iframe_');
		$this->root            = DIR_ADMIN_TEMPLATES;
		$this->compile         = DIR_CACHE;
	}

	public function template($iframe)
	{
		$this->assignGroup(array(
			'ADDR_ADMIN_ICONS' => ADDR_ADMIN_IMAGES.'icons/',
			'ADDR_ADMIN_PAGES_JS' => ADDR_ADMIN_TEMPLATES.'javascripts/pages/',
			'ADDR_ADMIN_CSS' => ADDR_ADMIN_TEMPLATES.'stylesheet/',
			'ADDR_ADMIN_JS' => ADDR_ADMIN_TEMPLATES.'javascripts/',
			'ADDR_ADMIN_PAGES' => ADDR_ADMIN.'pages/'
		));

		$this->parse('pre'.DS.'iframe_header'.$this->ext);
		$this->parse($iframe.$this->ext);
		$this->parse('pre'.DS.'iframe_footer'.$this->ext);
	}
}

// Pages and modules parser
class Site extends Parser
{
	protected
		$_default_root,
		$_file_self,
		$_still_active_cache = FALSE,
		$_cached = FALSE,
		$_route,
		$_root = '', // Niestandardowy plik do odpalenia
		$_path_to_theme;

	public function __construct(Router $route)
	{
		// Local OPT configuration loader
		$this->setConfig();

		$this->_default_root = $this->root;
		$this->_route = $route;

		if (isset($_GET['fromPage']))
		{
			$this->assign('HereIsPage', TRUE);
		}

		// Main OPT configuration && system constants loader
		parent::loadSystem();

		$this->_path_to_theme = DIR_THEME;
	}

	public function route()
	{
		return $this->_route;
	}

	public function getHeaders()
	{
		$head_elements = array();
		$css_file = $this->_path_to_theme.'templates/pages/css/'.$this->_route->getFileName().'.css';
		if (file_exists($css_file))
		{
			$head_elements[] = '<link href="'.str_replace(DIR_SITE, ADDR_SITE, $css_file).'" media="screen" rel="stylesheet" />';
		}

		$js_file = $this->_path_to_theme.'templates/pages/js/'.$this->_route->getFileName().'.js';
		if (file_exists($js_file))
		{
			$head_elements[] = '<script src="'.str_replace(DIR_SITE, ADDR_SITE, $js_file).'"></script>';
		}

		return $head_elements;
	}

	protected function setConfig()
	{
		$this->setCompilePrefix('site_');
		$this->root            = DIR_TEMPLATES;
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;
	}

	public function setDefaultRoot()
	{
		$this->root = $this->_default_root;
	}

	public function setCustomRoot($root)
	{
		$this->root = $root;
	}

	public function getPageCompileDir()
	{
		return $this->_root;
	}

	public function setPageCompileDir($root)
	{
		$this->_root = $root;
	}

	public function cache(array $data = array())
	{
		if (isset($data['status']) && isset($data['expire']) && isNum($data['expire']))
		{
			$this->_cached = TRUE;
			$this->cacheStatus($data['status'], $data['expire']);
		}
		if (isset($data['unique']) && $data['unique'])
		{
			$this->cacheUnique(CACHE_PREFIX.$data['unique']);
		}
		if (isset($data['active']) && $data['active'])
		{
			$this->_still_active_cache = TRUE;
		}
	}

	public function isCached($filename, $id = NULL)
	{
		return parent::isCached(CACHE_PREFIX.$filename, $id);
	}

	public function template($iframe, $dir = NULL)
	{
		if ($dir !== NULL)
		{
			$this->root = $dir;
		}

		$this->assign('ADDR_JS', ADDR_JS);
		$this->assign('ADDR_COMMON_JS', ADDR_COMMON_JS);
		$this->assign('ADDR_CSS', ADDR_CSS);
		$this->assign('ADDR_MODULES', ADDR_SITE.'modules/');
		$this->assign('ADDR_INCLUDES', ADDR_SITE.'system/includes/');
		$this->assign('ADDR_ICONS', ADDR_IMAGES.'icons/');
		$this->assign('ADDR_ADMIN_ICONS', ADDR_ADMIN_IMAGES.'icons/');
		$this->assign('ADDR_IMAGES', ADDR_IMAGES);
		$this->assign('THEME_IMAGES', THEME_IMAGES);
		$this->assign('THEME_CSS', THEME_CSS);
		$this->assign('THEME_JS', THEME_JS);
		$this->assign('FILE_SELF', $this->_route->getFileName().$this->_route->getExt('site'));
		$this->assign('FILE_NAME', $this->_route->getFileName());
		$this->assign('SystemVersion', 'eXtreme-Fusion '.parent::$_sett->get('version'));
		$this->assign('ADDR_AJAX', ADDR_AJAX);

		$this->assign('action', $this->_route->getAction());

		$this->parse($iframe);

		if ($this->_cached)
		{
			$this->cacheStatus($this->_still_active_cache);
		}

		if ($dir !== NULL)
		{
			$this->setDefaultRoot();
		}
	}
}

// Panels
class Panel extends Parser
{
	protected
		$_default_root,
		$_file_self,
		$_still_active_cache = FALSE,
		$_cached = FALSE;
	public
		$_root = '';

	public function __construct(Router $route, $root)
	{
		// Local OPT configuration loader
		$this->setConfig();

		$this->root = $root;
		$this->_route = $route;

		//$this->_default_root = $this->root;


		//if (isset($_GET['fromPage']))
		//{
			//$this->assign('HereIsPage', TRUE);
		//}

		// Main OPT configuration && system constants loader
		parent::loadSystem();
	}

	protected function setConfig()
	{
		$this->setCompilePrefix('panels_');
		$this->root            = DIR_TEMPLATES;
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;
	}

	public function setDefaultRoot()
	{
		$this->root = $this->_default_root;
	}

	public function setCustomRoot()
	{
		if ($this->_root)
		{
			$this->root = $this->_root;
		}
	}

	public function cache(array $data = array())
	{
		if (isset($data['status']) && isset($data['expire']) && isNum($data['expire']))
		{
			$this->_cached = TRUE;
			$this->cacheStatus($data['status'], $data['expire']);
		}
		if (isset($data['unique']) && $data['unique'])
		{
			$this->cacheUnique(CACHE_PREFIX.$data['unique']);
		}
		if (isset($data['active']) && $data['active'])
		{
			$this->_still_active_cache = TRUE;
		}
	}

	public function isCached($filename, $id = NULL)
	{
		return parent::isCached(CACHE_PREFIX.$filename, $id);
	}

	public function template($iframe)
	{
		$this->assign('ADDR_JS', ADDR_JS);
		$this->assign('ADDR_COMMON_JS', ADDR_COMMON_JS);
		$this->assign('ADDR_MODULES', ADDR_SITE.'modules/');
		$this->assign('ADDR_INCLUDES', ADDR_SITE.'system/includes/');
		$this->assign('ADDR_ICONS', ADDR_IMAGES.'icons/');
		$this->assign('ADDR_ADMIN_ICONS', ADDR_ADMIN_IMAGES.'icons/');
		$this->assign('ADDR_IMAGES', ADDR_IMAGES);
		$this->assign('ADDR_ADMIN', ADDR_ADMIN);
		$this->assign('THEME_IMAGES', THEME_IMAGES);
		$this->assign('THEME_CSS', THEME_CSS);
		$this->assign('FILE_SELF', $this->_route->getFileName().$this->_route->getExt('site'));
		$this->assign('FILE_NAME', $this->_route->getFileName());
		$this->assign('SystemVersion', 'eXtreme-Fusion '.parent::$_sett->get('version'));
		$this->assign('ADDR_AJAX', ADDR_AJAX);

		$this->parse($iframe);

		if ($this->_cached)
		{
			$this->cacheStatus($this->_still_active_cache);
		}
	}
}

// Panels
class Ajax extends Parser
{
	protected
		$_still_active_cache = FALSE,
		$_cached = FALSE;

	public function __construct($root)
	{
		// Local OPT configuration loader
		$this->setConfig();
		$this->root = $root;

		// Main OPT configuration && system constants loader
		parent::loadSystem();
	}

	protected function setConfig()
	{
		$this->setCompilePrefix('ajax_');
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;
	}

	public function cache(array $data = array())
	{
		if (isset($data['status']) && isset($data['expire']) && isNum($data['expire']))
		{
			$this->_cached = TRUE;
			$this->cacheStatus($data['status'], $data['expire']);
		}
		if (isset($data['unique']) && $data['unique'])
		{
			$this->cacheUnique(CACHE_PREFIX.$data['unique']);
		}
		if (isset($data['active']) && $data['active'])
		{
			$this->_still_active_cache = TRUE;
		}
	}

	public function isCached($filename, $id = NULL)
	{
		return parent::isCached(CACHE_PREFIX.$filename, $id);
	}

	public function template($iframe)
	{
		$this->assign('ADDR_JS', ADDR_JS);
		$this->assign('ADDR_COMMON_JS', ADDR_COMMON_JS);
		$this->assign('ADDR_MODULES', ADDR_SITE.'modules/');
		$this->assign('ADDR_INCLUDES', ADDR_SITE.'system/includes/');
		$this->assign('ADDR_ICONS', ADDR_IMAGES.'icons/');
		$this->assign('ADDR_ADMIN_ICONS', ADDR_ADMIN_IMAGES.'icons/');
		$this->assign('DIR_IMAGES', DIR_IMAGES);
		$this->assign('THEME_IMAGES', THEME_IMAGES);
		$this->assign('THEME_CSS', THEME_CSS);
		$this->assign('SystemVersion', 'eXtreme-Fusion '.parent::$_sett->get('version'));
		$this->assign('ADDR_AJAX', ADDR_AJAX);

		$this->parse($iframe);

		if ($this->_cached)
		{
			$this->cacheStatus($this->_still_active_cache);
		}
	}
}

// Admin Panel for module
class AdminModuleIframe extends Parser
{
	protected
		$_module;

	public function __construct($module)
	{
		$this->_module = $module;

		// Local OPT configuration loader
		$this->setConfig($module);

		if (isset($_GET['fromPage']))
		{
			$this->assign('HereIsPage', TRUE);
		}

		// Main OPT configuration && system constants loader
		parent::loadSystem();
	}

	protected function setConfig()
	{
		$this->setCompilePrefix('modules_').$this->_module;
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;
	}


	public function template($iframe)
	{
		$this->assign('ADDR_MODULES', ADDR_SITE.'modules/');
		$this->assign('ADDR_INCLUDES', ADDR_SITE.'system/includes/');
		$this->assign('ADDR_ADMIN_ICONS', ADDR_ADMIN_IMAGES.'icons/');
		$this->assign('ADDR_ADMIN_CSS', ADDR_ADMIN_TEMPLATES.'stylesheet/');
		$this->assign('ADDR_ADMIN_JS', ADDR_ADMIN_TEMPLATES.'javascripts/');
		$this->assign('ADDR_ADMIN_PAGES', ADDR_ADMIN.'pages/');

		$GetExpire = explode(':', HELP::sec2hms(parent::$_sett->getUns('loging', 'admin_loging_time')-(time()-parent::$_user->get('admin_last_logged_in'))));
		$this->assign('SessionExpire', '+'.$GetExpire[0].'h +'.$GetExpire[1].'m +'.$GetExpire[2].'s');

		$this->root    = DIR_ADMIN_TEMPLATES;
		$this->parse('pre'.DS.'iframe_header.tpl');

		$this->root    = DIR_MODULES.$this->_module.DS.'templates'.DS;
		$this->parse($this->_module.'.'.$iframe);

		$this->root    = DIR_ADMIN_TEMPLATES;
		$this->parse('pre'.DS.'iframe_footer.tpl');
	}
}

// Admin Panel Homepage
class AdminMainEngine extends Parser
{
	public function __construct()
	{
		// Local OPT configuration loader
		$this->setConfig();

		if (isset($_GET['fromPage']))
		{
			$this->assign('HereIsPage', TRUE);
		}

		// Main OPT configuration && system constants loader
		parent::loadSystem();
	}

	protected function setConfig()
	{
		$this->setCompilePrefix('admin_');
		$this->root            = DIR_ADMIN_TEMPLATES;
		$this->compile         = DIR_CACHE;
		$this->cache           = DIR_CACHE;
	}



	public function template($mainengine)
	{
		$this->assign('MOD_ADDRESS', ADDR_SITE.'modules/');
		$this->assign('BASE_IMAGES', ADDR_SITE.'templates/images/');
		$this->assign('ADMIN_TEMPLATE_IMAGES', ADDR_ADMIN.'templates/images/');
		$this->assign('THEME', ADDR_SITE.'themes'.DS.parent::$_sett->get('theme').DS);
		$this->assign('THEME_IMAGE', ADDR_SITE.'themes'.DS.parent::$_sett->get('theme').DS.'templates/images'.DS);
		$this->assign('ADDR_ADMIN_CSS', ADDR_ADMIN_TEMPLATES.'stylesheet/');
		$this->assign('ADDR_ADMIN_JS', ADDR_ADMIN_TEMPLATES.'javascripts/');
		$this->assign('CurrentYear', date('Y'));

		if (iUSER)
		{
			$GetExpire = explode(':', HELP::sec2hms(parent::$_sett->getUns('loging', 'admin_loging_time')-(time()-parent::$_user->get('admin_last_logged_in'))));
			$this->assign('SessionExpire', '+'.$GetExpire[0].'h +'.$GetExpire[1].'m +'.$GetExpire[2].'s');
		}

		$this->parse($mainengine.$this->ext);
	}
}

