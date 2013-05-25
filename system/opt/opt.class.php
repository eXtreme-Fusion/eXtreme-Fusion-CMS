<?php
  //  --------------------------------------------------------------------  //
  //                        Open Power Template                             //
  //         Copyright (c) 2005-2007 Tomasz "Zyx" Jedrzejewski              //
  //     Copyright (c) 2008 Invenzzia Group, http://www.invenzzia.org/      //
  //  --------------------------------------------------------------------  //
  //  This program is free software; you can redistribute it and/or modify  //
  //  it under the terms of the GNU Lesser General Public License as        //
  //  published by the Free Software Foundation; either version 2.1 of the  //
  //  License, or (at your option) any later version.                       //
  //  --------------------------------------------------------------------  //
  //
  // $Id: opt.class.php 59 2006-08-02 11:29:55Z zyxist $

	define('OPT_HTTP_CACHE', 1);
	define('OPT_NO_HTTP_CACHE', 2);

	define('OPT_HTML', 0);
	define('OPT_XHTML', 1);
	define('OPT_XML', 2);
	define('OPT_WML', 3);
	define('OPT_TXT', 4);
	define('OPT_FORCED_XHTML', 5);

	define('OPT_PREFILTER', 0);
	define('OPT_POSTFILTER', 1);
	define('OPT_OUTPUTFILTER', 2);

	define('OPT_SECTION_MULTI', 0);
	define('OPT_SECTION_SINGLE', 1);
	define('OPT_SECTION_COMPILE', 0);
	define('OPT_SECTION_RUNTIME', 1);
	define('OPT_PRIORITY_NORMAL', 0);
	define('OPT_PRIORITY_HIGH', 1);

	define('OPT_VERSION', '1.1.5');

	define('OPT_E_CONTENT_TYPE', 1);
	define('OPT_E_ARRAY_REQUIRED', 2);
	define('OPT_E_RESOURCE', 3);
	define('OPT_E_FILTER', 4);
	define('OPT_E_RESOURCE_NOT_FOUND', 5);
	define('OPT_E_FILE_NOT_FOUND', 6);
	define('OPT_E_WRITEABLE', 7);
	define('OPT_E_CONFIG_NOT_LOADED', 8);
	define('OPT_E_ENCLOSING_STATEMENT', 101);
	define('OPT_E_UNKNOWN', 102);
	define('OPT_E_FUNCTION_NOT_FOUND', 103);
	define('OPT_E_CONSTANT_NOT_FOUND', 104);
	define('OPT_E_COMMAND_NOT_FOUND', 105);
	define('OPT_E_EXPRESSION', 106);
	define('OPT_E_REQUIRED_NOT_FOUND', 107);
	define('OPT_E_INVALID_PARAMETER', 108);
	define('OPT_E_DEFAULT_MARKER', 109);
	define('OPT_E_UNKNOWN_PARAM', 110);
	define('OPT_E_PARAM_STYLE', 111);
	define('OPT_E_ACCESS_RESTRICTED', 112);
	define('OPT_E_UNKNOWN_CONFIG', 113);
	define('OPT_W_LANG_NOT_FOUND', 151);
	define('OPT_E_IF_ELSEIF', 201);
	define('OPT_E_IF_ELSE', 202);
	define('OPT_E_IF_END', 203);
	define('OPT_E_BIND_NOT_FOUND', 208);
	define('OPT_W_DYNAMIC_OPENED', 301);
	define('OPT_W_DYNAMIC_CLOSED', 302);
	define('OPT_W_SNIPPETS_NOT_DEF', 303);
	define('OPT_W_SHORT_CYCLE', 304);
	define('OPT_E_PREFIX', 305);

	if(!defined('OPT_DIR'))
	{
		define('OPT_DIR', './');
	}

	// Additional things

	function optPostfilterStripWhitespaces(optClass $tpl, $code)
	{
		return preg_replace('/(\r|\n){1,2}[ \t\f]*\<\?(.+)\?\>[ \t\f]*(\r|\n){1,2}/s', '<'.'?$2?'.'>$3', $code);
	} // end optPostfilterStripWhitespaces();

	# COMPONENTS
	interface ioptComponent
	{
		public function __construct($name = '');
		public function setOptInstance(optClass $tpl);
		public function set($name, $value);
		public function push($name, $value, $selected = false);
		public function setDatasource(&$source);
		public function begin();
		public function end();
	}
	# /COMPONENTS

	# OBJECT_I18N
	interface ioptI18n
	{
		public function setOptInstance(optClass $tpl);
		public function put($group, $id);
		public function putApply($group, $id);
		public function apply($group, $id);
	}
	# /OBJECT_I18N

	# PAGESYSTEM
	interface ioptPagesystem
	{
		public function getPage();
		public function nextPage();
		public function prevPage();
		public function firstPage();
		public function lastPage();
	}
	# /PAGESYSTEM

	# DYNAMIC_SECTIONS
	class optDynamicData
	{
		private $callback = NULL;
		private $args = NULL;

		public function __construct($arg1, $arg2, $arg3 = NULL)
		{
			if(is_null($arg3))
			{
				// A function
				$this -> callback = $arg1;
				$this -> args = $arg2;
			}
			else
			{
				$this -> callback = array($arg1, $arg2);
				$this -> args = $arg3;
			}
		} // end __construct();

		public function call()
		{
			return call_user_func_array($this -> callback, $this -> args);
		} // end call();
	} // end optDynamicData;
	# /DYNAMIC_SECTIONS

	// OPT Parser class
	class optClass
	{
		// Configuration
		public $root = NULL;
		public $compile = NULL;
		public $cache = NULL;
		public $plugins = NULL;
		public $compileId = NULL;

		public $gzipCompression = false;
		public $charset = NULL;

		public $alwaysRebuild = false;
		public $showWarnings = true;
		public $debugConsole = false;
		public $performance = false;
		public $cacheExpire = NULL;

		public $xmlsyntaxMode = false;
		public $strictSyntax = false;
		public $entities = false;
		public $sectionStructure = OPT_SECTION_MULTI;
		public $sectionDynamic = OPT_SECTION_COMPILE;
		public $statePriority = OPT_PRIORITY_NORMAL;
		public $useConfigBlocks = false;
		public $useEnvironment = true;

		public $parseintDecPoint = '.';
		public $parseintDecimals = 3;
		public $parseintThousands = ',';

		public $configDirectives = array(0=>
				'root', 'compile', 'cache', 'plugins', 'compileId',
				'gzipCompression', 'charset', 'showWarnings', 'debugConsole', 'alwaysRebuild',
				'performance', 'xmlsyntaxMode', 'strictSyntax', 'entities', 'sectionStructure',
				'statePriority', 'useConfigBlocks', 'useEnvironment', 'parseintDecPoint',
				'parseintDecimals', 'parseintThousands'
			);

		// Parser and compiler data
		protected $init = false;
		protected $outputBufferEnabled = false;
		protected $ext = '.tpl';
		public $contentType = '';
		private $filenames = array();
		public $compiler;
		public $data = array();
		public $vars = array();
		public $capture = array();

		protected $prefix = '%%';
		protected $prefix_changed = FALSE;
		
		public function setCompilePrefix($prefix)
		{
			if ($this->prefix_changed === FALSE)
			{
				if (preg_match('/^[A-Za-z0-9_]+$/', $prefix))
				{
					$this->prefix = $prefix;
					$this->prefix_changed = TRUE;
				}
				else
				{
					$this -> error(E_USER_ERROR, 'Specified value: "'.$prefix.'" is not a valid OPT prefix name.', OPT_E_PREFIX);
				}
			}
			else
			{
				$this -> error(E_USER_ERROR, 'Prefix for compile file has been already changed.', OPT_E_PREFIX);
			}
		}
		// Zmienia rozszerzenie u¿ywanych plików szablonu
		public function setFileExt($ext)
		{
			if (in_array($ext, array('.tpl', '.html', '.htm')))
			{
				$this->ext = $ext;
			}
		}

		# MASTER_TEMPLATES
		protected $compileMasterPages = array();
		# /MASTER_TEMPLATES

		public $functions = array(
								'parse_int' => 'PredefParseInt',
								'wordwrap' => 'PredefWordwrap',
								'apply' => 'PredefApply',
								'cycle' => 'PredefCycle',
								'in_array' => 'PredefInArray'
							);
		public $phpFunctions = array(
								'upper' => 'strtoupper',
								'lower' => 'strtolower',
								'capitalize' => 'ucfirst',
								'trim' => 'trim',
								'length' => 'strlen',
								'count_words' => 'str_word_count',
								'count' => 'sizeof',
								'date' => 'date',
								'array' => 'array',
								'escape' => 'htmlspecialchars',
								'firstof' => 'optPredefFirstof',
								'spacify' => 'optPredefSpacify',
								'indent' => 'optPredefIndent',
								'strip' => 'optPredefStrip',
								'truncate' => 'optPredefTruncate'
							);
		public $control = array(0 =>
			'optSection',
			# PAGESYSTEM
			'optPagesystem',
			# /PAGESYSTEM
			'optInclude',
			'optPlace',
			'optVar',
			'optIf',
			'optFor',
			'optForeach',
			'optCapture',
			'optDynamic',
			'optDefault',
			'optBind',
			'optInsert',
			'optBindEvent',
			'optBindGroup'
		);
		public $nschange = true;
		public $namespaces = array(0 => 'opt');
		# COMPONENTS
		public $components = array(
							# PREDEFINED_COMPONENTS
								'selectComponent' => 1,
								'textInputComponent' => 1,
								'textLabelComponent' => 1,
								'formActionsComponent' => 1
							# /PREDEFINED_COMPONENTS
							);
		# /COMPONENTS
		public $delimiters = array(0 =>
								'\{(\/?)(($$NS$$)\:)?(.*?)(\/?)\}',
								'($$NS$$)(\:)([a-zA-Z0-9\_]*)\=\"(.*?[^\\\\])\"'
							);
		public $filters = array(
								'pre' => array(),
								'preMaster' => array(),
								'post' => array(),
								'output' => array()
							);
		public $instructionFiles = array();

		// I18n
		public $i18n = NULL;
		public $i18nType = 0;

		# DEBUG_CONSOLE
		private $debugOutput = array();
		private $totalTime = 0;
		private $realPath = '';
		# /DEBUG_CONSOLE

		// Output cache
		private $cacheStatus = false;
		# OUTPUT_CACHING
		private $cacheId = NULL;
		private $_cacheExpire = 0;
		private $cacheDynamic = false;
		private $cacheData = array();
		private $startCache = false;
		# /OUTPUT_CACHING
		private $outputBuffer = array();

		// Methods
		public function assign($name, $value)
		{
			$this -> data[$name] = $value;
		} // end assign();

		# DYNAMIC_SECTIONS
		public function assignDynamic($name, $arg1, $arg2 = array(), $arg3 = NULL)
		{
			$this -> data[$name] = new optDynamicData($arg1, $arg2, $arg3);
		} // end assignDynamic();
		# /DYNAMIC_SECTIONS

		public function assignGroup($values)
		{
			if(!is_array($values))
			{
				return false;
			}

			foreach($values as $name => &$value)
			{
				$this -> data[$name] = $value;
			}
		} // end assignGroup();

		public function assignRef($name, &$value)
		{
			$this -> data[$name] = &$value;
		} // end assignRef();

		# HTTP_HEADERS
		public function httpHeaders($content, $cache = OPT_HTTP_CACHE)
		{
			$charset = '';
			if($this -> charset != NULL)
			{
				$charset = ';charset='.$this -> charset;
			}
			if(is_string($content))
			{
				$this -> contentType = $content;
			}
			else
			{
				switch($content)
				{
					case OPT_HTML:
						$this -> contentType = 'text/html';
						break;
					case OPT_XHTML:
						if(preg_match('/application\/xhtml\+xml(?![+a-z])(;q=(0\.\d{1,3}|[01]))?/i', $_SERVER['HTTP_ACCEPT'], $matches))
						{
							$xhtmlQ = isset($matches[2]) ? ($matches[2]+0.2) : 1;
							if(preg_match('/text\/html(;q=(0\.\d{1,3}|[01]))s?/i', $_SERVER['HTTP_ACCEPT'], $matches))
							{
								$htmlQ = isset($matches[2]) ? $matches[2] : 1;
								if($xhtmlQ >= $htmlQ)
								{
									$this -> contentType = 'application/xhtml+xml';
									break;
								}
							}
							else
							{
								$this -> contentType = 'application/xhtml+xml';
								break;
							}
						}
						$this -> contentType = 'text/html';
						break;
					case OPT_FORCED_XHTML:
						if(stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))
						{
							$this -> contentType = 'application/xhtml+xml';
						}
						else
						{
							$this -> contentType = 'text/html';
						}
						break;
					case OPT_XML:
						$this -> contentType = 'application/xml';
						break;
					case OPT_WML:
						$this -> contentType = 'text/vnd.wap.wml';
						break;
					case OPT_TXT:
						$this -> contentType = 'text/plain';
						break;
					default:
						$this -> error(E_USER_ERROR, 'Unknown content type: '.$content, OPT_E_CONTENT_TYPE);
				}
			}
			if($this -> contentType == 'application/xhtml+xml' && $this -> debugConsole)
			{
				$this -> contentType .= ' (text/html used for debug purposes)';
				$this -> header('Content-type: text/html'.$charset);
			}
			else
			{
				$this -> header('Content-type: '.$this -> contentType.$charset);
			}
			if($cache == OPT_NO_HTTP_CACHE)
			{
				$this -> header('Expires: 0');
				$this -> header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
				// HTTP/1.1
				$this -> header('Cache-Control: no-store, no-cache, must-revalidate');
				$this -> header('Cache-Control: post-check=0, pre-check=0', false);
				// HTTP/1.0
				$this -> header('Pragma: no-cache');
			}
		} // end httpHeaders();
		# /HTTP_HEADERS

		public function loadConfig($data)
		{
			if(!is_array($data))
			{
				$fname = $data;
				$data = @parse_ini_file($data);
				if(sizeof($data) == 0)
				{
					if(!file_exists($fname))
					{
						$reason = 'File does not exist';
					}
					else if(!is_readable($fname))
					{
						$reason = 'Permission denied';
					}
					else
					{
						$reason = 'Unknown reason';
					}
					$this -> error(E_USER_ERROR, 'Could not load the configuration from the file "'.$fname.'": '.$reason, OPT_E_CONFIG_NOT_LOADED);
				}
			}

			foreach($this -> configDirectives as $name)
			{
				if(isset($data[$name]))
				{
					$this -> $name = $data[$name];
				}
			}
		} // end loadConfig();

		public function loadPlugins()
		{
			if(is_string($this -> plugins))
			{
				$this -> _loadPlugins($this -> plugins);
			}
			elseif(is_array($this -> plugins))
			{
				foreach($this -> plugins as $path)
				{
					$this -> _loadPlugins($path);
				}
			}
		} // end loadPlugins();

		public function setDefaultI18n(&$lang)
		{
			$this -> i18nType = 0;
			if(is_array($lang))
			{
				$this -> i18n = &$lang;
			}
			else
			{
				$this -> error(E_USER_ERROR, 'First parameter must be an array.', OPT_E_ARRAY_REQUIRED);
			}
		} // end setDefaultI18n();

		# OBJECT_I18N
		public function setObjectI18n(ioptI18n $i18n)
		{
			$this -> i18nType = 1;
			$this -> i18n = $i18n;
		} // end setObjectI18n();
		# /OBJECT_I18N

		# MASTER_TEMPLATES
		public function setMasterTemplate($filename)
		{
			$this -> compileMasterPages[] = $filename;
		} // end setMasterTemplate();

		public function setMasterPage($filename)
		{
			// bug in the code for 1.1.0 version, so we keep also the buggy method name
			$this -> compileMasterPages[] = $filename;
		} // end setMasterPage();
		# /MASTER_TEMPLATES

		# REGISTER_FAMILY
		public function registerInstruction($class)
		{
			if(is_object($this -> compiler))
			{
				// The compiler is already initialized, we have to translate this call like the compiler does.
				if(!is_array($class))
				{
					$class = array(0 => $class);
				}
				$this -> compiler -> translate($class);
			}
			else
			{
				// OK, the compiler is not used. Just register. If the compiler is needed, it will translate
				// the call on its own.
				if(is_array($class))
				{
					$this -> control = array_merge($this->control, $class);
				}
				else
				{
					$this -> control[] = $class;
				}
			}
		} // end registerInstruction();

		public function registerFunction($name, $callback = NULL)
		{
			if(is_array($name))
			{
				$this -> functions = array_merge($this -> functions, $name);
				return true;
			}
			else
			{
				if(strlen($name) > 0)
				{
					$this -> functions[$name] = $callback;
					return true;
				}
			}
			return false;
		} // end registerFunction();

		public function registerPhpFunction($name, $callback = NULL)
		{
			if(is_array($name))
			{
				$this -> phpFunctions = array_merge($this -> phpFunctions, $name);
				return true;
			}
			else
			{
				if(strlen($name) > 0)
				{
					$this -> phpFunctions[$name] = $callback;
					return true;
				}
			}
			return false;
		} // end registerPhpFunction();
		# CUSTOM_RESOURCES
		public function registerResource($name, $callback)
		{
			if(function_exists('optResource'.$callback))
			{
				$this -> resources[$name] = 'optResource'.$callback;
				return true;
			}
			$this -> error(E_USER_ERROR, 'Specified value: "optResource'.$callback.'" is not a valid resource function name.', OPT_E_RESOURCE);
		} // end registerResource();
		# /CUSTOM_RESOURCES
		public function registerComponent($name)
		{
			if(is_array($name))
			{
				foreach($name as $componentName)
				{
					$this -> components[$componentName] = 1;
				}
			}
			else
			{
				$this -> components[$name] = 1;
			}
		} // end registerComponent();

		public function registerFilter($type, $callback)
		{
			switch($type)
			{
				case 0:
						$prefix = 'optPrefilter';
						$idx = 'pre';
						break;
				case 1:
						$prefix = 'optPostfilter';
						$idx = 'post';
						break;
				case 2:
						$prefix = 'optOutputfilter';
						$idx = 'output';
						break;
				default:
						return false;
			}
			if(function_exists($prefix.$callback))
			{
				$this -> filters[$idx][] = $prefix.$callback;
				return true;
			}
			else
			{
				$this -> error(E_USER_ERROR, 'Specified value: "'.$prefix.$callback.'" is not a valid OPT filter function name.', OPT_E_FILTER);
			}
		} // end registerFilter();

		public function registerInstructionFile($file)
		{
			$this -> instructionFiles[] = $file;
		} // end registerInstructionFile();

		public function registerNamespace($namespace)
		{
			$this -> namespaces[] = $namespace;
			$this -> nschange = true;
		} // end registerNamespace();

		public function unregisterNamespace($namespace)
		{
			if(($id = array_search($namespace, $this -> namespaces)) !== false)
			{
				$this -> nschange = true;
				unset($this -> namespaces[$id]);
				return true;
			}
			return false;
		} // end unregisterNamespace();

		public function unregisterFilter($type, $callback)
		{
			switch($type)
			{
				case 0:
						if(($id = in_array('optPrefilter'.$callback, $this -> filters['pre'])) !== FALSE)
						{
							unset($this -> filters['post'][$id]);
							return true;
						}
						break;
				case 1:
						if(($id = in_array('optPostfilter'.$callback, $this -> filters['post'])) !== FALSE)
						{
							unset($this -> filters['post'][$id]);
							return true;
						}
						break;
				case 2:
						if(($id = in_array('optOutputfilter'.$callback, $this -> filters['output'])) !== FALSE)
						{
							unset($this -> filters['post'][$id]);
							return true;
						}
						break;
			}
			return false;
		} // end unregisterFilter();
		# /REGISTER_FAMILY
		public function parse($filename, $rebuild = false)
		{
			$this -> fetch($filename, true, $rebuild);
		} // end parse();

		public function parseCapture($filename, $destination)
		{
			$this -> capture[$destination] = $this -> fetch($filename);
		} // end parseCapture();

		public function fetch($filename, $display = false, $rebuild = false)
		{
			static $init;
			if(is_null($init))
			{
				$init = 1;
				require_once(OPT_DIR.'opt.functions.php');
				# GZIP_SUPPORT
				if($this -> gzipCompression == true && extension_loaded('zlib') && ini_get('zlib.output_compression') == 0 && !$this -> outputBufferEnabled)
				{
					ob_start('ob_gzhandler');
					ob_implicit_flush(0);
					$this -> outputBufferEnabled = true;
				}
				# /GZIP_SUPPORT
				# PLUGIN_AUTOLOAD
				if($this -> plugins != NULL)
				{
					$this -> loadPlugins();
				}
				# /PLUGIN_AUTOLOAD
				# DEBUG_CONSOLE
				if($this -> debugConsole)
				{
					$this -> realPath = realpath(OPT_DIR.'opt.core.php');
				}
				# /DEBUG_CONSOLE
			}

			array_push($this -> filenames, $filename);

			if(!$display || sizeof($this -> filters['output']) > 0)
			{
				ob_start();
			}
			$cached = false;
			$dynamic = false;
			if($this -> performance)
			{
				# OUTPUT_CACHING
				if($this -> cacheStatus == true)
				{
					if(!$this -> cacheProcess($filename))
					{
						$filename = (!is_null($this->compileId) ? $this -> compileId.'_' : '').optCompileFilenameFull($filename, $this->prefix);
						$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
						include($this -> compile.$filename);
						error_reporting($oldErrorReporting);
						$this -> cacheWrite($filename, $dynamic);
						$cached = true;
					}
				}
				else
				{
				# /OUTPUT_CACHING
					$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
					include($this -> compile.(!is_null($this->compileId) ? $this -> compileId.'_' : '').optCompileFilenameFull($filename, $this->prefix));
					error_reporting($oldErrorReporting);
				# OUTPUT_CACHING
				}
				# /OUTPUT_CACHING
			}
			else
			{
				# DEBUG_CONSOLE
				if($this -> debugConsole)
				{
					$time = microtime(true);
				}
				# /DEBUG_CONSOLE
				# OUTPUT_CACHING
				if($this -> cacheStatus == true)
				{
					if(!$this -> cacheProcess($filename))
					{
						$compiled = $this -> needCompile($filename, false, $rebuild);
						$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
						include($this -> compile.$compiled);
						error_reporting($oldErrorReporting);
						$this -> cacheWrite($compiled, $dynamic);
						$cached = true;
					}
				}
				else
				{
				# /OUTPUT_CACHING
					$compiled = $this -> needCompile($filename, false, $rebuild);
					$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
					include($this -> compile.$compiled);
					error_reporting($oldErrorReporting);
				# OUTPUT_CACHING
				}
				# /OUTPUT_CACHING
				# DEBUG_CONSOLE
				if($this -> debugConsole)
				{
					$this -> totalTime += $time = microtime(true) - $time;
					$this -> debugOutput[] = array(
						'template' => $filename,
						'cached' => $cached,
						'problems' => (!isset($php_errormsg) || strpos($php_errormsg, 'Undefined') === 0 || $php_errormsg == '') ? '&nbsp;' : $php_errormsg,
						'cache' => ($this -> cacheStatus ? 'Yes' : 'No'),
						'exec' => round($time, 5)
					);
				}
				# /DEBUG_CONSOLE
			}

			array_pop($this -> filenames);

			// Parse output filters
			if(sizeof($this -> filters['output']) > 0)
			{
				$content = ob_get_clean();
				foreach($this -> filters['output'] as $filter)
				{
					$content = $filter($this, $content);
				}
				if(!$display)
				{
					return $content;
				}
				echo $content;
			}
			// Return by default
			if(!$display)
			{
				$text = ob_get_clean();
				return $text;
			}
		} // end fetch();

		private function doInclude($filename, $default = false)
		{
			array_push($this -> filenames, $filename);
			if($this -> performance)
			{
				if($default == true)
				{
					if(!file_exists($filename = $this -> compile.(!is_null($this->compileId) ? $this -> compileId.'_' : '').optCompileFilenameFull($filename, $this->prefix)))
					{
						return false;
					}
					$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
					include($filename);
					error_reporting($oldErrorReporting);
				}
				else
				{
					$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
					include($this -> compile.(!is_null($this->compileId) ? $this -> compileId.'_' : '').optCompileFilenameFull($filename, $this->prefix));
					error_reporting($oldErrorReporting);
				}
			}
			else
			{
				$compiled = $this -> needCompile($filename, true);
				if($compiled == NULL)
				{
					return false;
				}
				$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
				include($this -> compile.$compiled);
				error_reporting($oldErrorReporting);
			}
			array_pop($this -> filenames);
			return true;
		} // end doInclude();

		public function compileCacheReset($filename = NULL)
		{
			require_once(OPT_DIR.'opt.core.php');
			optCompileCacheReset($filename, $this -> compile);
		} // end compileCacheReset();
		# OUTPUT_CACHING
		public function cacheReset($filename = NULL, $id = NULL, $expireTime = NULL)
		{
			require_once(OPT_DIR.'opt.core.php');
			optCacheReset($filename, $id, $expireTime, $this -> cache, $this -> root);
		} // end cacheReset();

		public function cacheStatus($status, $expire = 2)
		{
			$this -> cacheStatus = $status;
			$this -> _cacheExpire = $expire;
		} // end cacheReset();

		public function getStatus()
		{
			return $this -> cacheStatus;
		} // end cacheReset();

		public function cacheUnique($id = NULL)
		{
			$this -> cacheId = $id;
		} // end cacheReset();

		public function isCached($filename, $id = NULL)
		{
			$this -> cacheHash = base64_encode($filename.$id);
			$this -> cacheFilename = optCacheFilename($filename, $id);

			if(!isset($this -> cacheBuffer[$this -> cacheHash]))
			{
				$f = @fopen($this -> cache.$this -> cacheFilename, 'r');
				if(!is_resource($f))
				{
					return false;
				}
				$head = fgets($f);
				if($head[0] == '<')
				{
					fclose($f);
					$head = str_replace(array('<'.'?php /*','*/?>'), '', $head);
					$header = @unserialize($head);
					if(!is_array($header))
					{
						return false;
					}
				}
				else
				{
					$header = @unserialize($head);
					if(!is_array($header))
					{
						return false;
					}
					$this -> cacheBuffer[$this -> cacheHash]['h'] = $f;
				}
				if($header['timestamp'] < (time() - (int)$header['expire']))
				{
					$this -> cacheBuffer[$this -> cacheHash]['ok'] = false;
					return false;
				}
				$this -> cacheBuffer[$this -> cacheHash]['ok'] = true;
				return true;
			}
			return $this -> cacheBuffer[$this -> cacheHash]['ok'];
		} // end isCached();
		# /OUTPUT_CACHING

		public function __destruct()
		{
			# GZIP_SUPPORT
			if($this -> outputBufferEnabled)
			{
				ob_end_flush();
			}
			# /GZIP_SUPPORT
			# DEBUG_CONSOLE
			if($this -> debugConsole)
			{
				// Including opt.core.php
				// This solution is used because of PHP bug #36454
				if($this -> realPath != '')
				{
					require_once($this -> realPath);

					optShowDebugConsole(array(
						'Root directory' => $this -> root,
						'Compile directory' => $this -> compile,
						'Plugin directory' => (!is_null($this -> plugins) ? $this -> plugins : '&nbsp;'),
						'Cache directory' => (!is_null($this -> cache) ? $this -> cache : '&nbsp;'),
						'GZip compression' => ($this->gzipCompression==true ? '<font color="green">Yes</font>' : 'No'),
						'Always rebuild' => ($this->alwaysRebuild==true ? '<font color="red">Yes</font> (Please turn off this option to improve performance)' : 'No'),
						'Performance tuning' => ($this->performance==true ? '<font color="green">Yes</font>' : 'No'),
						'Charset' => (!is_null($this -> charset) ? $this -> charset : '&nbsp;'),
						'Content-type' => $this -> contentType,
						'Total template time' => round($this -> totalTime, 6).' s'
					),$this -> debugOutput,
					$this -> compileMasterPages);
				}
			}
			# /DEBUG_CONSOLE
		} // end __destruct();

		public function error($type, $message, $code)
		{
			include_once(OPT_DIR.'opt.error.php');
			require_once(OPT_DIR.'opt.core.php');
			optErrorMessage($this, $type, $message, $code, end($this->filenames));
		} // end error();

		protected function needCompile($filename, $noException = false, $rebuild = false)
		{
			$compiled = (!is_null($this->compileId) ? $this -> compileId.'_' : '').optCompileFilename($filename, $this->prefix);
			# CUSTOM_RESOURCES
			$resource = 'file';
			if(strpos($filename, ':') !== FALSE)
			{
				$data = explode(':', $filename);
				$filename = $data[1];
				$resource = $data[0];

				if(!isset($this -> resources[$resource]))
				{
					if($noException)
					{
						return NULL;
					}
					$this -> error(E_USER_ERROR, 'Specified resource type: "'.$resource.'" does not exist.', OPT_E_RESOURCE_NOT_FOUND);
				}
				$callback = $this -> resources[$resource];
			}
			# /CUSTOM_RESOURCES
			$compiledTime = @filemtime($this -> compile.$compiled);
			$result = false;
			# CUSTOM_RESOURCES
			if($resource == 'file')
			{
			# /CUSTOM_RESOURCES
				$rootTime = @filemtime($this -> root.$filename);
				if($rootTime === false)
				{
					if($noException)
					{
						return NULL;
					}
					$this -> error(E_USER_ERROR, '"'.$filename.'" not found in '.$this->root.' directory.', OPT_E_FILE_NOT_FOUND);
				}
				if($compiledTime === false || $compiledTime < $rootTime || $this -> alwaysRebuild || $rebuild)
				{
					$result = file_get_contents($this -> root.$filename);
				}
			# CUSTOM_RESOURCES
			}
			else
			{
				$result = $callback($this, $filename, $compiledTime);
			}
			# /CUSTOM_RESOURCES

			if($result === false)
			{
				return $compiled;
			}

			if(!is_object($this -> compiler))
			{
				require_once(OPT_DIR.'opt.compiler.php');
				$this -> compiler = new optCompiler($this);

				# MASTER_TEMPLATES
				if(sizeof($this -> compileMasterPages) > 0)
				{
					// Load master pages now
					foreach($this -> compileMasterPages as $page)
					{
						$this -> getTemplate($page, $this -> compiler, true);
					}
				}
				# /MASTER_TEMPLATES
			}
			$this -> compiler -> parse($this -> compile.$compiled, $result);
			return $compiled;
		} // end needCompile();

		public function getTemplate($filename, $compiler = NULL, $master = false)
		{
			# CUSTOM_RESOURCES
			$resource = 'file';
			if(strpos($filename, ':') !== FALSE)
			{
				$data = explode(':', $filename);
				$filename = $data[1];
				$resource = $data[0];

				if(!isset($this -> resources[$resource]))
				{
					$this -> error(E_USER_ERROR, 'Specified resource type: '.$resource.' does not exist.', OPT_E_RESOURCE_NOT_FOUND);
				}
				$callback = $this -> resources[$resource];
			}
			# /CUSTOM_RESOURCES
			if(is_null($compiler))
			{
				$compiler = new optCompiler($this -> compiler);
			}
			# CUSTOM_RESOURCES
			if($resource == 'file')
			{
			# /CUSTOM_RESOURCES
				$result = file_get_contents($this -> root.$filename);
			# CUSTOM_RESOURCES
			}
			else
			{
				$result = $callback($this, $filename);
			}
			# /CUSTOM_RESOURCES
			return $compiler -> parse(NULL, $result, $master);
		} // end getFilename();

		# OUTPUT_CACHING
		protected function cacheProcess($filename)
		{
			if($this -> isCached($filename, $this -> cacheId))
			{
				if(isset($this -> cacheBuffer[$this -> cacheHash]['h']))
				{
					while($buf = fgets($this -> cacheBuffer[$this -> cacheHash]['h'], 2048))
					{
						echo $buf;
					}
					fclose($this -> cacheBuffer[$this -> cacheHash]['h']);
				}
				else
				{
					$oldErrorReporting = error_reporting(E_ALL ^ E_NOTICE);
					include($this -> cache.$this->cacheFilename);
					error_reporting($oldErrorReporting);
				}
				return true;
			}
			else
			{
				$this -> startCache = true;
				ob_start();
				return false;
			}
		} // end cacheProcess();

		protected function cacheWrite($compiled, $dynamic)
		{
			$this -> startCache = false;
			// generate the file
			$header = serialize(array(
				'timestamp' => time(),
				'expire' => $this -> _cacheExpire
			));
			if(!$dynamic)
			{
				file_put_contents($this->cache.$this -> cacheFilename, $header."\n".ob_get_contents());
			}
			else
			{
				// Build the dynamic source
				$dynamicCodes = unserialize(file_get_contents($this -> compile.$compiled.'.dyn'));
				$content = '';
				foreach($this -> outputBuffer as $id => &$buffer)
				{
					$content .= $buffer;
					if(isset($dynamicCodes[$id]))
					{
						$content .= $dynamicCodes[$id];
					}
				}
				file_put_contents($this->cache.$this -> cacheFilename, '<'.'?php /*'.$header."*/?>\n".$content.ob_get_contents());
			}
		} // end cacheWrite();
		# /OUTPUT_CACHING

		private function _loadPlugins($path)
		{
			$this -> instructionFiles[] = $path.'compile.php';
			if(file_exists($path.'plugins.php'))
			{
				// Load precompiled plugin database
				include_once($path.'plugins.php');
			}
			else
			{
				// Compile plugin database
				if(!is_writeable($path))
				{
					$this -> error(E_USER_ERROR, $path.' is not a writeable directory.', OPT_E_WRITEABLE);
				}

				$code = '';
				$compileCode = '';
				$file = '';
				$dir = opendir($path);
				while($file = readdir($dir))
				{
					if(preg_match('/(component|instruction|function|prefilter|postfilter|outputfilter|resource)\.([a-zA-Z0-9\_]+)\.php/', $file, $matches))
					{
						switch($matches[1])
						{
							# COMPONENTS
							case 'component':
								$code .= "\trequire('".$path.$file."');\n";
								$code .= "\t\$this->components['".$matches[2]."'] = 1;\n";
								break;
							# /COMPONENTS
							case 'instruction':
								$compileCode .= "\trequire('".$path.$file."');\n";
								$compileCode .= "\t\$this->tpl->control[] = '".$matches[2]."';\n";
								break;
							case 'function':
								$code .= "\trequire('".$path.$file."');\n";
								$code .= "\t\$this->functions['".$matches[2]."'] = '".$matches[2]."';\n";
								break;
							case 'prefilter':
								$code .= "\trequire('".$path.$file."');\n";
								$code .= "\t\$this->filters['pre'][] = 'optPrefilter".$matches[2]."';\n";
								break;
							case 'postfilter':
								$code .= "\trequire('".$path.$file."');\n";
								$code .= "\t\$this->filters['post'][] = 'optPostfilter".$matches[2]."';\n";
								break;
							case 'outputfilter':
								$code .= "\trequire('".$path.$file."');\n";
								$code .= "\t\$this->filters['output'][] = 'optOutputfilter".$matches[2]."';\n";
								break;
							case 'resource':
								$code .= "\trequire('".$path.$file."');\n";
								$code .= "\t\$this->resources[".$matches[2]."] = \$".$matches[4].";\n";
								break;
						}
					}
				}
				closedir($dir);
				
				file_put_contents($this -> plugins.'plugins.php', '<'."?php\n".$code.'?'.'>');
				file_put_contents($this -> plugins.'compile.php', '<'."?php\n".$compileCode.'?'.'>');
				
				eval($code);
			}
			return true;
		} // end _loadPlugins();

		# HTTP_HEADERS
		protected function header($header)
		{
			header($header);
		} // end header();
		# /HTTP_HEADERS
	}

	// Functions
	function optCompileFilenameFull($filename, $prefix)
	{
		# CUSTOM_RESOURCES
		if(strpos($filename, ':') !== FALSE)
		{
			$resource = explode(':', $filename);
			$filename = $resource[1];
		}
		# /CUSTOM_RESOURCES
		return $prefix.str_replace(array('/', '\\'), '__', $filename).'.php';
	} // end optCompileFilenameFull();

	function optCompileFilename($filename, $prefix)
	{
		return $prefix.str_replace(array('/', ':', '\\'), '__', $filename).'.php';
	} // end optCompileFilename();

	function optCacheFilename($filename, $id = '')
	{
		$path = explode('/', str_replace(array(':', '\\'), '/', $filename));
		if(($file = end($path)) == '')
		{
			return false;
		}
		unset($path[key($path)]);
		$dir = implode('/', $path);

		return str_replace(array('|', '/', '\\'),'^',$id).'_'.base64_encode($dir).$file;
	} // end cd();
?>
