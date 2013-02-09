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

/* DEBUGOWANIE:
echo 'Akcja ^: '.var_dump($_route->getAction()).'<br />';

echo 'Parametry zdefiniowane ^ : '.var_dump($_route->getParam()).'<br />';
echo 'Parametry niezdefiniowane ^: '.var_dump($_route->getParamVoid()); */

class Router
{
	protected
		// OBIEKTY
		$_sett,						// Obiekt klasy Settings
		$_request,
		$_url,
		// USTAWIENIA
		$_sep,
		$_param_sep = '-',			/**********TEST*********/
		$_main_param,
		$_opening_page,
		$_ext,						// Rozszerzenia plików
		$_ret_default,
		$_search_more,
		$_search_by_admin_file,
		$_searching,
		$_rewrite,
		$_ext_allowed,	  	// Czy linki mogą posiadać rozszerzenie?

		$_path = NULL,				// Żądanie URL
		$_values = array(),			// Parametry żądania URL
		$_file_path = '',			// Ścieżka do pliku, który ma zostać użyty
		$_file_name = '',			// Nazwa pliku bez rozszerzenia, który ma zostać użyty
		$_admin_file = '',			// Nazwa pliku wygenerowana administracyjnie
		$_exit_file = '',			// Końcowy plik do użycia
		$_folders = array(),
		$_params = array(), 	  	/**********TEST*********/
		$_params_str = array(),   	/**********TEST*********/ //Zawiera liste ciągów parametrowych, np. user-5
		$_params_void = array(),  	/**********TEST*********/
		$_params_void_order = array(),
		$_params_merge = array(), 	/**********TEST*********/ // Tablica parametrów zdefiniowanych i niezdefiniowanych (patrz opis metody setMergeParams()).
		$_action = '';			  	/**********TEST*********/

	protected $_path_info_exists;

	protected $_installed_modules = array();

	public function __construct($request, $_sett, $rewrite, $main_param, $path_info_exists, $opening_page, $ret_default = FALSE, $search_more = TRUE, $search_admin = FALSE, $searching = '')
	{
		$this->_sett = $_sett;
		$this->_request = $request;

		$this->_rewrite = $rewrite;
		$this->_sep = $this->_sett->getUns('routing', 'main_sep');
		$this->_param_sep = $this->_sett->getUns('routing', 'param_sep');
		$this->_main_param = $main_param;

		$this->_opening_page = $opening_page;
		$this->_ret_default = $ret_default;
		$this->_search_more = $search_more;		// Czy w przypadku nie znlezienia pliku podanego w PA, ma szukać w innych lokalizacjach
		$this->_search_admin = $search_admin; 	// Czy ma szukać pliku podanego w PA
		$this->_searching = $searching;			// Kolejność wyszukiwania

		$this->_ext = array(
			'log' => $this->_sett->getUns('routing', 'logic_ext'),
			'tpl' => $this->_sett->getUns('routing', 'tpl_ext'),
			'url' => $this->_sett->getUns('routing', 'url_ext')
		);

		$this->_path_info_exists = $path_info_exists;

		$this->setEnv();

		$this->_url = new URL($this->_ext['url'], $this->_sep, $this->_param_sep, $this->_rewrite, $this->_path_info_exists);
		$this->_ext_allowed = $this->_url->extAllowed();

		/**
		 * Metoda zwróci FALSE, jeśli żądanie nie spełnia warunku dotyczącego rozszerzenia.
		 * Wyświetli się błąd 404, więc wykonanie poniższych metod jest niepotrzebne.
		 */
		if ($this->setPath())
		{
			$this->setValues();
			$this->setFileName();
			$this->setParams();
			$this->setAction();
		}

		$this->_url->setController($this->getFileName());
	}

	/**
	 * Konfiguruje środowisko pracy dla Routera.
	 * Tworzy stałą PATH_INFO zawierającą żądanie URL.
	 */
	protected function setEnv()
	{
		if ($this->_path_info_exists)
		{
			$dirname = dirname($_SERVER['SCRIPT_NAME']);

			if ($dirname === $this->_sep)
			{
				$to_replace = $this->_sep.'index.php';
			}
			else
			{
				$to_replace = array($dirname, $this->_sep.'index.php');
			}

			define('PATH_INFO', str_replace($to_replace, '', $_SERVER['REQUEST_URI']));
		}
		else
		{
			define('PATH_INFO', $this->_request->get('q', '')->show());
		}
	}

/*start of**********TEST*********/

	//	NIE USUWAC
	// Zapis parametrów NIE USUWAC!!!
	/*protected function setParams()
	{
		for ($i = 1, $c = count($this->_values); $i < $c; $i++)
		{
			$param = explode($this->_param_sep, $this->_values[$i]);
			// Parametr zdefiniowany (indeks i wartość)
			if (count($param) === 2)
			{
				$this->_params_str[] = $this->_values[$i];
				$this->_params[$param[0]] = $param[1];
			}
			// Nieznany typ parametru (indeks i brak wartości lub kilka)
			else
			{	$val = str_replace($param[0], '', implode($this->_param_sep, $param));
				if (substr($val, 0, 1) === '-') $val = substr($val, 1, strlen($val));

				$this->_params_void[$param[0]] = $val;
			}
		}

		$this->setMergeParams();
	}*/// WERSJA NR 1 NIE USUWAC /

	// wersja nr 2
	protected function setParams()
	{
		$void_i = 1;
		for ($i = 1, $c = count($this->_values); $i < $c; $i++)
		{
			$param = explode($this->_param_sep, $this->_values[$i]);
			$how = count($param);
			// Parametr zdefiniowany (indeks i wartość)
			if ($how === 2)
			{
				$this->_params_str[] = $this->_values[$i];
				$this->_params[$param[0]] = $param[1];
			}
			// Parametr z wartością $this->_param_sep (wartość parametru zawiera separator nazwy i wartości parametru)
			elseif ($how > 2)
			{
				$this->_params_str[] = $this->_values[$i];
				$string = array();
				for($ii = 1; $ii < $how; $ii++)
				{
					$string[] = $param[$ii];
				}
				$this->_params[$param[0]] = implode($this->_param_sep, $string);
			}
			// Parametry puste
			else
			{	$val = str_replace($param[0], '', implode($this->_param_sep, $param));
				if (substr($val, 0, 1) === '-') $val = substr($val, 1, strlen($val));

				$this->_params_void[$param[0]] = $val;
				$this->_params_void_order[$void_i] = $param[0];
				$void_i++;
			}
		}

		$this->setMergeParams();
	}

	// Łaczy tablicę zdefiniowanych parametrów z tablicą niezdefiniowanych.
	// Jeżeli w obu tablicach wystapi ten sam indeks,
	// to w końcowej połączonej tablicy znajdzie się ten, który posiadał wartość.
	protected function setMergeParams()
	{
		$this->_params_merge = $this->_params;

		foreach($this->_params_void as $key => $val)
		{
			if ( ! isset($this->_params_merge[$key]))
			{
				$this->_params_merge[$key] = $val;
			} // przy opcji drugiej setParams() poniższy fragment mozna usunąc, gdyz void params to tylko paremetry puste, a nie takze z separatorem w wartosci
			elseif ( ! $this->_params_merge[$key] && $val)
			{
				$this->_params_merge[$key] = $val;
			}
		}
	}

	// Tworzy cache aktualnej `akcji` z żądania URL
	protected function setAction()
	{
		if (isset($this->_values[1]) && ! in_array($this->_values[1], $this->_params_str))
		{
			$this->_action = $this->_values[1];
		}

		// Usuwanie `akcji` z listy niezdefiniowanych parametrów
		$this->cleanParamsVoid();
	}

	// Usuwanie `akcji` z listy niezdefiniowanych parametrów
	protected function cleanParamsVoid()
	{
		$new = array();
		foreach($this->_params_void as $key => $val)
		{
			if ($key !== $this->_action)
			{
				$new[$key] = $val;
			}
		}

		$this->_params_void = $new;

		$new = array();
		$i = 1;
		if ($this->_params_void_order)
		{
			foreach($this->_params_void_order as $key => $val)
			{
				if ($val !== $this->_action)
				{
					$new[$i] = $val;
					$i++;
				}
			}

			$this->_params_void_order = $new;
		}
	}

	/**
	 * Zwraca wszystkie zdefiniowane parametry (przy pominięciu `$key`)
	 * lub wartość o indeksie `$key`.
	 *
	 * W przypadku nieznalezienia parametru w tablicy zdefiniowanych (poprawnych) parametrów,
	 * czyli takich, które posiadają klucz i wartość rozdzielone $this->_param_sep (domyślnie znak `-`),
	 * przeszukana zostanie tablica indeksów niezdefiniowanych (przeciwieństwo zdefiniowanych).
	 *
	 * Jeżeli parametr nie zostanie odnaleziony w żadnej z tablic,
	 * metoda zwróci wartość zmiennej `$default`.
	 */
	public function getParam($key = NULL, $default = NULL)
	{
		if ($key === NULL)
		{
			return $this->_params_merge;
		}
		// Przeszukiwanie tablicy zdefiniowanych parametrów
		elseif (isset($this->_params[$key]))
		{
			return $this->_params[$key];
		}
		// Przeszukiwanie tablicy niezdefiniowanych parametrów
		// wyłączone: aby dostac sie do parametru z tej grupy nalezy korzystać z getByID() bądź getParamVoid() (zobacz koniecznie opis tej metody).
		/*elseif (isset($this->_params_void[$key]))
		{
			return $this->_params_void[$key];
		}*/

		return $default;
	}

	// Sprawdza czy istnieje "pusty" parametr o podanej wartości
	public function paramVoidExists($key)
	{
		return isset($this->_params_void[$key]);
	}

	// Przeszukuje tablicę parametrów niezdefiniowanych
	//
	// Dla wersji 2 setParams(): jest to odpowiednik isset(),
	// bo w tej grupie są tylko parametry `puste`.
	//
	// Dla wersji 1 setParam() są tu też parametry,
	// które posiadają klucz i wartość rozdzielone $this->_param_sep (domyślnie znak `-`).
	public function getParamVoid($key = NULL, $default = NULL)
	{
		if ($key === NULL)
		{
			return $this->_params_void_order;
		}
		// Przeszukiwanie tablicy niezdefiniowanych parametrów
		elseif (isset($this->_params_void_order[$key]))
		{
			return $this->_params_void_order[$key];
		}

		return $default;
	}


	/**
	 * Zwraca nazwę akcji, jaka ma zostać wykonana.
	 *
	 * Akcja musi być ciągiem znaków.
	 * Nie może w swojej nazwie zawierać $this->_param_sep,
	 * czyli separatora parametrów (domyślnie znak `-`).
	 * Akcję można pominąć w żądaniu URL.
	 */
	public function getAction($default = NULL)
	{
		return $this->_action ? $this->_action : $default;
	}


	// Przekierowanie z pozostaniem na tym samym kontrolerze (pliku).
	public function redirect(array $path)
	{
		HELP::redirect($this->path($path));
	}

	// Przekierowanie na inny kontriker (plik).
	public function goToFile($file, $action = NULL, array $params = array())
	{

		echo 'METODA KLASY ROUTER O NAZWIE GOTOFILE JEST PRZESTARZALA';
		$str_params = '';
		if ($params)
		{
			$temp = array();
			foreach($params as $key => $val)
			{
				if (isNum($key, FALSE, FALSE))
				{
					$temp[] = $val;
				}
				else
				{
					$temp[] = $key.$this->_param_sep.$val;
				}
			}

			$str_params = implode($this->_sep, $temp);

			if ($str_params && $action)
			{
				$str_params = $this->_sep.$str_params;
			}

			if ($action === NULL)
			{
				$action = $this->_sep;
			}
		}

		HELP::redirect($file.$action.$str_params.$this->_ext['url']);
		exit;
	}

/*end of**********TEST*********/

	public function setNewConfig($val)
	{
		$this->setPath($val);
		$this->setValues();
		$this->setFileName();
		$this->setExitFile();
		$this->_search_admin = FALSE;
		$this->_search_more = TRUE;
	}

	public function setFolders(array $folders)
	{
		$this->_folders = $folders;
	}

	protected function setPath()
	{
		$path = PATH_INFO;

		if ($path && $path !== '/')
		{
			$path_len = strlen($path);
			if (substr($path, 0, 1) === '/')
			{
				$path = substr($path, 1, $path_len);
			}

			if ($this->_ext_allowed)
			{
				if (in_array($ext = strrchr($path, '.'), array('.html', '.htm', '.php')))
				{
					$path = substr($path, 0, strpos($path, $ext));
				}
				else
				{
					$elems = explode($this->_sep, $path);
					if ($elems[0] === 'error' && isNum($elems[1]))
					{
						$this->trace(array('controller' => 'error', 'action' => $elems[1]));
					}
					else
					{
						$this->trace(array('controller' => 'error', 'action' => 404));
					}
					return FALSE;
				}
			}
			// Jeżeli nie jest wymagane rozszerzenie, to zakładamy, że nie może go być
			else
			{
				if (in_array($ext = strrchr($path, '.'), array('.html', '.htm', '.php')))
				{
					$this->trace(array('controller' => 'error', 'action' => 404));
					return FALSE;
				}
			}


		}
		else
		{
			$this->_path = 'news';
			return TRUE;
		}

		if ($path === 'index')
		{
			$path = 'news';
		}

		$this->_path = $path;
		return TRUE;
	}

	protected function setValues()
	{
		if ($this->_path)
		{
			$this->_values = explode($this->_sep, $this->_path);
		}
	}

	protected function setFileName()
	{
		if (count($this->_values))
		{
			$this->_file_name = str_replace('..', '', $this->_values[0]);
		}
		elseif ($this->_ret_default)
		{
			$this->_file_name = $this->getOpeningPage();
		}

	}

	/**
	 * Ustawia kontroler i akcję do wywołania
	 *
	 * Może być przydatne gdy na danej podstronie chcemy wyświetlić
	 * najpierw panel logowania przed właściwą treścią bez przekierowania
	 * na podstronę logowania.
	 */
	public function trace(array $trace)
	{
		if (isset($trace['controller']))
		{
			$this->_file_name = $trace['controller'];
			$this->setExitFile(FALSE);
		}

		if (isset($trace['action']))
		{
			$this->_action = $trace['action'];
		}
	}

	public function setInstalledModules(array $installed)
	{
		$this->_installed_modules = $installed;
	}

	public function setExitFile($shared = TRUE)
	{
		if ($this->_exit_file && $shared)
		{
			if (file_exists($this->_exit_file))
			{
				return TRUE;
			}
		}

		if ($this->_search_more)
		{
			if ($this->_search_admin)
			{
				if ($this->_searching == 'admin')
				{
					$file = $this->_admin_file;
					$level = $this->getFileName().$this->getExt('log');
				}
				else
				{
					$file = $this->getFileName().$this->getExt('log');
					$level = $this->_admin_file;
				}
			}
			else
			{
				$file = $this->getFileName().$this->getExt('log');
			}
			foreach($this->_folders as $key => $val)
			{
				if ($key === 'modules' && !in_array($this->getFileName(), $this->_installed_modules))
				{
					continue;
				}

				if (file_exists($val.DS.$file))
				{
					$this->_exit_file = $val.$file;
					return TRUE;
				}
			}
			if (isset($level))
			{
				foreach($this->_folders as $key => $val)
				{
					if ($key === 'modules' && !in_array($this->getFileName(), $this->_installed_modules))
					{
						continue;
					}
					if (file_exists($val.DS.$level))
					{
						$this->_exit_file = $val.$level;
						return TRUE;
					}
				}
			}
		}
		$this->_exit_file = '';
		return FALSE;
	}

	public function setAdminFile($path)
	{
		$val = strrchr($path, '.');
		if ($val)
		{
			$this->_admin_file = str_replace('/', '', strrchr($path, '/'));
			$this->_exit_file = $path;
		}
		else
		{
			$this->_exit_file = $path.$this->getFileName().$this->_ext['log'];
		}
	}

	public function getExitFile()
	{
		return $this->_exit_file ? $this->_exit_file : FALSE;
	}

	public function getFileName()
	{
		return $this->_file_name;
	}

	public function getLogicFileName() {
		return $this->_file_name.$this->_ext['log'];
	}

	public function getTplFileName() {
		return $this->_file_name.$this->_ext['tpl'];
	}

	public function getUrlExt()
	{
		return $this->_ext['url'];
	}

	public function getByID($id)
	{
		return isset($this->_values[$id]) ? $this->_values[$id] : FALSE;
	}

	public function getRequest()
	{
		return $this->_path;
	}

	public function getOpeningPage()
	{
		return $this->_opening_page;
	}

	public function getPathToFile()
	{
		return $this->_file_path;
	}

	public function getExt($key)
	{
		return isset($this->_ext[$key]) ? $this->_ext[$key] : FALSE;
	}

	public function path(array $data)
	{
		return $this->_url->path($data);
	}

}