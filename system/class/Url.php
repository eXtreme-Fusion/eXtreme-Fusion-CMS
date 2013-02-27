<?php

class URL
{
	private
		$url_ext,
		$main_sep,
		$param_sep,
		$rewrite_loaded,
		$path_info_exists,
		$controller;

	private
		$ext_allowed;

	public function __construct($url_ext, $main_sep, $param_sep, $rewrite_loaded, $path_info_exists, $controller = NULL)
	{
		$this->url_ext = $url_ext;
		$this->main_sep = $main_sep;
		$this->param_sep = $param_sep;
		$this->rewrite_loaded = $rewrite_loaded;
		$this->path_info_exists = $path_info_exists;
		$this->controller = $controller;

		// USTAWIENIA
		$this->ext_allowed = TRUE;
	}

	public function extAllowed()
	{
		return $this->ext_allowed;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
	}

	public function getPathPrefix($not_parse = FALSE)
	{
		if ($this->rewrite_loaded || $not_parse)
		{
			return '';
		}
		elseif ($this->path_info_exists)
		{
			return 'index.php/';
		}

		return 'index.php?q=';
	}

	/**
	 * Generator linków dla plików szablonu.
	 *
	 * Predefiniowane indeksy (niewymagane, mogą zostać pominięte):
	 * - controller
	 * - action
	 * - extension
	 *
	 * Pozostałe to parametry, które mogą mieć nazwę (indeks tablicy)
	 * lub być tylko wartością. Przykład:
	 *
	 *	$_route->path(array('param1', 'param2' => 'value_for_param2'));
	 *
	 * Przykład użycia dla podstrony profile.html:
	 *
	 *	$_route->path(array('controller' => 'profile', 'action' => 'user', 457, 'extension' => 'html'));
	 *
	 * Przy załadowanym "rewrite module" wygenerowany zostanie następujący link:
	 * http://twojastrona/profile/user/457.html
	 */
	public function path(array $data)
	{
		if (isset($data['controller']))
		{
			$ctrl = $data['controller'];
		}
		elseif ($this->controller)
		{
			$ctrl = $this->controller;
		}
		else
		{
			exit('Nie podano kontrolera');
		}

		unset($data['controller']);

		if (isset($data['action']))
		{
			$action = $this->main_sep.$data['action'];
		}
		else
		{
			$action = '';
		}

		unset($data['action']);

			if (isset($data['extension']) && $data['extension'])
			{
				$ext = '.'.str_replace('.', '', $data['extension']);
			}
			elseif ($this->ext_allowed)
			{
				$ext = $this->url_ext;
			}
			else
			{
				$ext = '';
			}


		unset($data['extension']);

		$params = array();
		foreach($data as $key => $val)
		{
			$params[] = !is_int($key) ? $key.$this->param_sep.$val : $val;
		}

		if ($params)
		{
			$params = $this->main_sep.HELP::Title2Link(implode($this->main_sep, $params));
		}
		else
		{
			$params = '';
		}


		$trace = $this->getPathPrefix();

		return ADDR_SITE.$trace.$ctrl.$action.$params.$ext;
	}
}