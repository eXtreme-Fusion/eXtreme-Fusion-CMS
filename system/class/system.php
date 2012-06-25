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
class System {

	private $_rewrite_available;
	
	/**
	 * Zabezpieczenie przez atakami XSS.
	 *
	 * @return  void
	 * @throws  systemException
	 */
	public function __construct($cleaning = TRUE)
	{
		if ($cleaning && HELP::stripget($_GET))
		{
			throw new systemException(__('Podejrzewany atak XSS po zmiennej $_GET!'));
		}
	}

	/**
	 * Tworzy podstawową obsługę pamięci podręcznej.
	 *
	 *     // Tworzy pamięć podręczną o nazwie `foo`, wartości `bar`, w podkatalogu `dir`.
	 *     $_system->cache('foo', 'bar');
	 *
	 *     // Pobiera pamięć podręczną o nazwie `foo` z podkatalogu `dir`.
	 *     $_system->cache('foo', NULL, 'dir');
	 *
	 * @param   string   nazwa pamięci podręcznej
	 * @param   mixed    zawartość pamięci podręcznej
	 * @param   mixed    nazwa podkatalogu z cache
	 * @param   integer  żywot pamięci podręcznej
	 * @return  mixed
	 */
	public function cache($name, $data = NULL, $dir = NULL, $lifetime = 3600)
	{
		// Wyłączenie szyfrowania cache wersja jedynie dla DEV
		// Pamiętaj o ręcznym usunięciu cache po zmianie parametru FALSE/TRUE
		$code = TRUE;

		// Koduje nazwę pliku pamięci podręcznej
		if ( ! $code)
		{
			$file = CACHE_PREFIX.$name.'.txt';
		}
		else
		{
			$file = sha1(CACHE_PREFIX.$name).'.txt';
		}

		// Tworzy ścieżkę dostępu do katalogu z pamięcią podręczną
		$dir = DIR_SITE.'cache'.DS.$dir.DS;
		if ( ! file_exists($dir))
		{
			mkdir($dir);
			chmod($dir, 0777);
		}

		if ($code)
		{
			// TODO:: czy wartość $key/$string nie powinna być brana z zakodowanej cześci $data zamiast z nazwy pliku?
			// TODO:: bo wydaje mi się że w obecnej formie może się zdarzyć tak, że w zawartości 
			// TODO:: cache'owanego pliku nie znajdzie wyrażenia spod tych zmiennych (które pochodzi z nazwy pliku a nie zawartości) 
			// TODO:: i wtedy łatwo rozkodować taki plik.
			
			// Tworzy klucz który jest mieszany z base64_decode/base64_encode,
			// zabezpiecza przed bezpośrednim odczytaniem danych z przez base64_decode
			$key = substr(sha1($file), 1, 7);
			$string = substr(sha1($file), 5, 1);
		}

		if ($data === NULL)
		{
			if (is_file($dir.$file))
			{
				if ((time() - filemtime($dir.$file)) < $lifetime || $lifetime === NULL)
				{
					if ( ! $code)
					{
						return unserialize(file_get_contents($dir.$file));
					}
					else
					{
						return unserialize(base64_decode(str_replace($key, $string, file_get_contents($dir.$file))));
					}
				}
				else
				{
					if (file_exists($dir.$file))
					{
						unlink($dir.$file);
					}
				}
			}

			// Nie znaleziono pliku
			return NULL;
		}

		if ( ! is_dir($dir))
		{
			// Tworzy nowy katalog z pamięcią
			mkdir($dir, 0777, FALSE);

			// Nadaje prawa zapisu
			chmod($dir, 0777);
		}

		if ( ! $code)
		{
			return (bool) file_put_contents($dir.$file, serialize($data), LOCK_EX);
		}
		else
		{
			return (bool) file_put_contents($dir.$file, str_replace($string, $key, base64_encode(serialize($data))), LOCK_EX);
		}
	}

	/**
	 * Opróżnia katalog pamięci podręcznej szablonów.
	 *
	 *     // Usuwa całą pamięć podręczną szablonów.
	 *     $_system->clearCache();
	 *
	 *     // Usuwa pliki `foo.tpl` oraz `bar.tpl` z pamięci podręcznej.
	 *     $_system->clearCache(NULL, array('foo.tpl', 'bar.tpl'));
	 *
	 *     // Usuwa pamięć podręczną z podkatalogu `dir`.
	 *     $_system->clearCache('dir');
	 *
	 * @param   mixed    nazwa podkatalogu z cache
	 * @param   mixed    pliki pamięci podręcznej do usunięcia
	 * @param   string   ścieżka do głównego katalogu z pamięcią podręczną
	 * @return  boolean  zawsze zwróci TRUE
	 */
	public function clearCache($dir = NULL, array $cache = array(), $path = DIR_CACHE)
	{
	
		if (file_exists($path.$dir))
		{
			// Przeszukuje katalog pamięci podręcznej
			$files = new DirectoryIterator($path.$dir);

			foreach ($files as $file)
			{
				// Sprawdza rozszerzenie pliku pamięci podręcznej
				$extension = pathinfo($file->getPathname(), PATHINFO_EXTENSION);

				if ( ! $file->isDot() && $file->isFile() && ($extension === 'tpl' || $extension === 'txt'))
				{
					if (in_array(preg_replace('/'.CACHE_PREFIX.'/', '', pathinfo($file->getPathname(), PATHINFO_FILENAME)), $cache) || empty($cache))
					{
						if (file_exists($file->getPathname()))
						{
							// Usuwa plik pamięci podręcznej
							unlink($file->getPathname());
						}
					}
				}
			}

			return TRUE;
		}
		
		return FALSE;
	}

	/**
	 * Wykrywa język z jakiego korzysta przeglądarka
	 *
	 * @return  string
	 */
	public function detectBrowserLanguage()
	{
		$langs = array(
			'pl' => 'polish',
			'en' => 'english',
			'cz' => 'czech'
		);

		$var = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$var = explode(',', $var[0]);

		$current = null;
		foreach($var as $data)
		{

			if(isset($langs[$data]))
			{
				$current = $data;
				break;
			}
		}

		if(is_null($current))
		{
			$current = 'en';
		}

		return $current;
	}

	public function getModuleBootstrap($cache_expire = 43200)
	{
		$row = $this->cache('__autoloadModulesList', NULL, 'system', $cache_expire);
		if ($row === NULL)
		{
			$files = new DirectoryIterator(DIR_MODULES);
			foreach ($files as $file)
			{
				if ( ! in_array($file->getFilename(), array('..', '.', '.svn')))
				{
					if (is_dir($file->getPathname()) && file_exists($file->getPathname().DS.'autoload'.DS.'__autoload.php'))
					{
						$row[] = $file->getFilename();
					}
				}
			}
			sort($row);
			$this->cache('__autoloadModulesList', $row, 'system');
		}

		return $row;
	}

	/**
	 * Sprawdza, czy moduł Apache podany parametrem istnieje
	 * lub zwraca tablicę załądowanych modułów Apache
	 */
	public function apacheLoadedModules($name = NULL)
	{
		if (function_exists('apache_get_modules'))
		{
			if ($name !== NULL)
			{
				return in_array($name, apache_get_modules());
			}

			return apache_get_modules();
		}

		if ($name !== NULL)
		{
			/**
			 * Funkcja do sprawdzania załadowanych modułów Apache jest niedostępna.
			 * Zakładamy więc, że mod_rewrite nie jest załadowany.
			 */
			return FALSE;
		}

		throw new systemException('Błąd: Funkcja <span class="bold">apache_get_modules()</span> jest niedostępna!');
	}
	
	public function rewriteAvailable()
	{
		if ($this->_rewrite_available !== NULL)
		{
			return $this->_rewrite_available;
		}
		
		return $this->_rewrite_available = $this->apacheLoadedModules('mod_rewrite');
	}

	// Ładuje systemowy plik htaccess
	public function loadRewrite()
	{
		@rename(DIR_SITE.'sample.htaccess', DIR_SITE.'.htaccess');
	}

	// Dezaktywuje systemowy plik htaccess
	public function removeRewrite()
	{
		if (file_exists(DIR_SITE.'sample.htaccess'))
		{
			unlink(DIR_SITE.'sample.htaccess');
		}
	}
	
	public function pathInfoExists()
	{
		$result = (bool) ($this->rewriteAvailable() || isset($_SERVER['PATH_INFO']) || isset($_SERVER['ORIG_PATH_INFO']));
		
		if ($result === FALSE)
		{
			$data = $this->cache('path_exists', NULL, 'system', 86400);
			if ($data[0] === FALSE)
			{
				return FALSE;
			}
			else
			{//echo 5; exit;
				return TRUE;
			}
		}

		$this->cache('path_exists', array(TRUE), 'system');
		return TRUE;
	}
}