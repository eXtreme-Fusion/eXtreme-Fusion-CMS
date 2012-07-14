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

	class ServiceContainer implements ArrayAccess
	{
		
		public function __construct($parameters = array())
		{
			$this->parameters = $parameters;
		}

		
		// implementation ArrayAccess
		public function hasParameter($name)
		{
			return isset($this->parameters[$name]);
		}
		
		public function getParameter($name)
		{
			if (!isset($this->parameters[$name]))
			{
				return NULL;
			}
			
			return $this->parameters[$name];
		}
		
		public function setParameter($name, $value)
		{
			$this->parameters[$name] = $value;
		}

		
		// implementation ArrayAccess
		public function offsetExists($name)
		{
			return $this->hasParameter($name);
		}
		
		public function offsetGet($name)
		{
			return $this->getParameter($name);
		}
		
		public function offsetSet($name, $value)
		{
			return $this->setParameter($name, $value);
		}
		
		public function offsetUnset($name)
		{
			unset($this->parameters[$name]);
		}
		// end of implementation ArrayAccess
		
		public function __get($id)
		{
			return $this->getService($id);
		}
		
		protected function camelize($string)
		{
			$first = strtoupper($string[0]);
			return $first.substr($string, 1, strlen($string)-1);
		}
		
		public function getService($id)
		{
			if (method_exists($this, $method = 'get'.$this->camelize($id).'Service'))
			{
				return $this->$method();
			}
		}
	}
	

	


/*
 * This file is part of the symfony framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * sfServiceContainerBuilder is a DI container that provides an interface to build the services.
 *
 * @package    symfony
 * @subpackage dependency_injection
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfServiceContainerBuilder.php 269 2009-03-26 20:39:16Z fabien $
 */
class ServiceContainerBuilder extends ServiceContainer
{
  protected
    $definitions = array(),
    $aliases     = array(),
    $loading     = array();

  /**
   * Sets a service.
   *
   * @param string $id      The service identifier
   * @param object $service The service instance
   */
  public function setService($id, $service)
  {
    unset($this->aliases[$id]);

    parent::setService($id, $service);
  }

  /**
   * Returns true if the given service is defined.
   *
   * @param  string  $id      The service identifier
   *
   * @return Boolean true if the service is defined, false otherwise
   */
  public function hasService($id)
  {
    return isset($this->definitions[$id]) || isset($this->aliases[$id]) || parent::hasService($id);
  }

  /**
   * Gets a service.
   *
   * @param  string $id The service identifier
   *
   * @return object The associated service
   *
   * @throw InvalidArgumentException if the service is not defined
   * @throw LogicException if the service has a circular reference to itself
   */
  public function getService($id)
  {
    try
    {
      return parent::getService($id);
    }
    catch (InvalidArgumentException $e)
    {
      if (isset($this->loading[$id]))
      {
        throw new LogicException(sprintf('The service "%s" has a circular reference to itself.', $id));
      }

      if (!$this->hasServiceDefinition($id) && isset($this->aliases[$id]))
      {
        return $this->getService($this->aliases[$id]);
      }

      $definition = $this->getServiceDefinition($id);

      $this->loading[$id] = true;

      if ($definition->isShared())
      {
        $service = $this->services[$id] = $this->createService($definition);
      }
      else
      {
        $service = $this->createService($definition);
      }

      unset($this->loading[$id]);

      return $service;
    }
  }

  /**
   * Gets all service ids.
   *
   * @return array An array of all defined service ids
   */
  public function getServiceIds()
  {
    return array_unique(array_merge(array_keys($this->getServiceDefinitions()), array_keys($this->aliases), parent::getServiceIds()));
  }

  /**
   * Sets an alias for an existing service.
   *
   * @param string $alias The alias to create
   * @param string $id    The service to alias
   */
  public function setAlias($alias, $id)
  {
    $this->aliases[$alias] = $id;
  }

  /**
   * Gets all defined aliases.
   *
   * @return array An array of aliases
   */
  public function getAliases()
  {
    return $this->aliases;
  }

  /**
   * Registers a service definition.
   *
   * This methods allows for simple registration of service definition
   * with a fluid interface.
   *
   * @param  string $id    The service identifier
   * @param  string $class The service class
   *
   * @return sfServiceDefinition A sfServiceDefinition instance
   */
  public function register($id, $class)
  {
    return $this->setServiceDefinition($id, new sfServiceDefinition($class));
  }

  /**
   * Adds the service definitions.
   *
   * @param array $definitions An array of service definitions
   */
  public function addServiceDefinitions(array $definitions)
  {
    foreach ($definitions as $id => $definition)
    {
      $this->setServiceDefinition($id, $definition);
    }
  }

  /**
   * Sets the service definitions.
   *
   * @param array $definitions An array of service definitions
   */
  public function setServiceDefinitions(array $definitions)
  {
    $this->definitions = array();
    $this->addServiceDefinitions($definitions);
  }

  /**
   * Gets all service definitions.
   *
   * @return array An array of sfServiceDefinition instances
   */
  public function getServiceDefinitions()
  {
    return $this->definitions;
  }

  /**
   * Sets a service definition.
   *
   * @param  string              $id         The service identifier
   * @param  sfServiceDefinition $definition A sfServiceDefinition instance
   */
  public function setServiceDefinition($id, sfServiceDefinition $definition)
  {
    unset($this->aliases[$id]);

    return $this->definitions[$id] = $definition;
  }

  /**
   * Returns true if a service definition exists under the given identifier.
   *
   * @param  string  $id The service identifier
   *
   * @return Boolean true if the service definition exists, false otherwise
   */
  public function hasServiceDefinition($id)
  {
    return array_key_exists($id, $this->definitions);
  }

  /**
   * Gets a service definition.
   *
   * @param  string  $id The service identifier
   *
   * @return sfServiceDefinition A sfServiceDefinition instance
   *
   * @throw InvalidArgumentException if the service definition does not exist
   */
  public function getServiceDefinition($id)
  {
    if (!$this->hasServiceDefinition($id))
    {
      throw new InvalidArgumentException(sprintf('The service definition "%s" does not exist.', $id));
    }

    return $this->definitions[$id];
  }

  /**
   * Creates a service for a service definition.
   *
   * @param  sfServiceDefinition $definition A service definition instance
   *
   * @return object              The service described by the service definition
   */
  protected function createService(sfServiceDefinition $definition)
  {
    if (null !== $definition->getFile())
    {
      require_once $this->resolveValue($definition->getFile());
    }

    $r = new ReflectionClass($this->resolveValue($definition->getClass()));

    $arguments = $this->resolveServices($this->resolveValue($definition->getArguments()));

    if (null !== $definition->getConstructor())
    {
      $service = call_user_func_array(array($this->resolveValue($definition->getClass()), $definition->getConstructor()), $arguments);
    }
    else
    {
      $service = null === $r->getConstructor() ? $r->newInstance() : $r->newInstanceArgs($arguments);
    }

    foreach ($definition->getMethodCalls() as $call)
    {
      call_user_func_array(array($service, $call[0]), $this->resolveServices($this->resolveValue($call[1])));
    }

    if ($callable = $definition->getConfigurator())
    {
      if (is_array($callable) && is_object($callable[0]) && $callable[0] instanceof sfServiceReference)
      {
        $callable[0] = $this->getService((string) $callable[0]);
      }
      elseif (is_array($callable))
      {
        $callable[0] = $this->resolveValue($callable[0]);
      }

      if (!is_callable($callable))
      {
        throw new InvalidArgumentException(sprintf('The configure callable for class "%s" is not a callable.', get_class($service)));
      }

      call_user_func($callable, $service);
    }

    return $service;
  }

  /**
   * Replaces parameter placeholders (%name%) by their values.
   *
   * @param  mixed $value A value
   *
   * @return mixed The same value with all placeholders replaced by their values
   *
   * @throw RuntimeException if a placeholder references a parameter that does not exist
   */
  public function resolveValue($value)
  {
    if (is_array($value))
    {
      $args = array();
      foreach ($value as $k => $v)
      {
        $args[$this->resolveValue($k)] = $this->resolveValue($v);
      }

      $value = $args;
    }
    else if (is_string($value))
    {
      if (preg_match('/^%([^%]+)%$/', $value, $match))
      {
        // we do this to deal with non string values (boolean, integer, ...)
        // the preg_replace_callback converts them to strings
        if (!$this->hasParameter($name = strtolower($match[1])))
        {
          throw new RuntimeException(sprintf('The parameter "%s" must be defined.', $name));
        }

        $value = $this->getParameter($name);
      }
      else
      {
        $value = str_replace('%%', '%', preg_replace_callback('/(?<!%)(%)([^%]+)\1/', array($this, 'replaceParameter'), $value));
      }
    }

    return $value;
  }

  /**
   * Replaces service references by the real service instance.
   *
   * @param  mixed $value A value
   *
   * @return mixed The same value with all service references replaced by the real service instances
   */
  public function resolveServices($value)
  {
    if (is_array($value))
    {
      $value = array_map(array($this, 'resolveServices'), $value);
    }
    else if (is_object($value) && $value instanceof sfServiceReference)
    {
      $value = $this->getService((string) $value);
    }

    return $value;
  }

  protected function replaceParameter($match)
  {
    if (!$this->hasParameter($name = strtolower($match[2])))
    {
      throw new RuntimeException(sprintf('The parameter "%s" must be defined.', $name));
    }

    return $this->getParameter($name);
  }
}

	class Container extends ServiceContainer
	{
		protected static $shared = array();
		
		protected function getCommentService()
		{
			
			if (!isset(self::$shared['comment']))
			{
				return self::$shared['comment'] = new Comment(new Basic, $this->getPDOService(), $this->getUserService(), $this->getSettService(), $this->getSBBService(), $this->getHeaderService()); 
			}
			
			return self::$shared['comment'];
		}
		
		protected function getUserService()
		{
			if (!isset(self::$shared['user']))
			{
				
				return self::$shared['user'] = new User($this->getSettService(), $this->getPDOService());
			}
			
			return self::$shared['user']; 
		}
		
		protected function getSettService()
		{
			if (!isset(self::$shared['sett']))
			{
				return self::$shared['sett'] = new Sett($this->getSystemService(), $this->getPDOService());
			}
			
			return self::$shared['sett']; 
		}
		
		protected function getSystemService()
		{
			if (!isset(self::$shared['system']))
			{
				return self::$shared['system'] = new System;
			}
			
			return self::$shared['system'];
		}
		
		protected function getPDOService()
		{
			if (!isset(self::$shared['pdo']))
			{
				require __DIR__.'/../config.php';
	
				self::$shared['pdo'] = new PDO_EXT('mysql:host='.$_dbconfig['host'].';dbname='.$_dbconfig['database'].';port='.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$_dbconfig['charset']));
				self::$shared['pdo']->config($_dbconfig['prefix']);
				
				return self::$shared['pdo'];
			}
			
			return self::$shared['pdo'];
		}
		
		protected function getHeaderService()
		{
			if (!isset(self::$shared['header']))
			{
				return self::$shared['header'] = new Header;
			}
			
			return self::$shared['header'];
		}
		
		protected function getSBBService()
		{
			class_exists('SmileyBBcode', FALSE) || include DIR_CLASS.'sbb.php';
			
			if (!isset(self::$shared['sbb']))
			{
				return self::$shared['sbb'] = SmileyBBcode::getInstance($this->getSettService(), $this->getPDOService(), $this->getLocaleService(), $this->getHeaderService(), $this->getUserService());
			}
			
			return self::$shared['sbb'];
		}
		
		protected function getLocaleService()
		{
			if (!isset(self::$shared['locale']))
			{
				return self::$shared['locale'] = new Locales($this->getSettService()->get('locale'), DIR_LOCALE);
			}
			
			return self::$shared['locale'];
		}
	}
	
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
		require __DIR__.'/../config.php';
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

    $ec = new Container(array());
	
	$_system = $ec->system;

	# PHP Data Object
    $_pdo = $ec->pdo;
	
	require_once DIR_SYSTEM.'table_list.php';
    $_sett = new Sett($_system, $_pdo);
    $_user = new User($_sett, $_pdo);
    $_locale = new Locales($_sett->get('locale'), DIR_LOCALE);


	define('URL_REQUEST', isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '' ? HELP::cleanurl($_SERVER['REQUEST_URI']) : $_SERVER['SCRIPT_NAME']);
	define('URL_QUERY', isset($_SERVER['QUERY_STRING']) ? HELP::cleanurl($_SERVER['QUERY_STRING']) : '');

	# Requests
	$_request = new Request;

	# Files class
	$_files = new Files;
	//var_dump($_system->pathInfoExists()); exit;
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

    if ( ! $_sett->get())
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
		if ($_user->get('theme') !== 'Default' && $_sett->get('userthemes') === '1')
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