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
*********************************************************/

class Request
{
	protected $_post;
	protected $_get;
	protected $_file;
	
	public function __construct()
	{
		$this->_post = $_POST;
		$this->_get = $_GET;
		$this->_file = $_FILES;
		
		$_POST = array();
		$_GET = array();
		$_FILE = array();
	}

	/**
	 Analiza żądań po tablicy $_GET
	 Metoda przy udziale obiektu klasy Edit filtruje zmienne.

	 Dla pierwszego parametru będącego tablicą:
		*Metoda sprawdza, czy wartości z przekazanej tablicy są istniejącymi indeksami zmiennej $_GET.
		*Jeśli któryś indeks nie istnieje, to do obiektu klasy Edit trafia wartość domyślna z drugiego parametru tej metody.
		*Jeśli wszystkie indeksy istnieją, to do obiektu klasy Edit trafia tablica wartości spod tych indeksów.
	 Dla pierwszego parametru będącego typem string:
		*Metoda sprawdza czy istnieje taki indeks w tablicy $_GET.
		*Jeśli istnieje, to wartość z tablicy $_GET trafia do obiektu klasy Edit:
		*Jeśli nie istnieje, to do obiektu klasy Edit trafia wartość domyślna z drugiego parametru tej metody.

	 Operowanie na obiekcie Edit:
		show() - zwraca wartość;
		strip() - usuwa znaki specjalne - blokuje wykonywanie kodu HTML;
	**/
	public function get($keys = NULL, $default = FALSE)
	{
		if (is_array($keys))
		{
			foreach($keys as $key)
			{
				if (isset($this->_get[$key]))
				{
					$data[$key] = $this->_get[$key];
				}
				else
				{
					return new Edit($default);
				}
			}

			return new Edit($data);
		}
		elseif ($keys !== NULL)
		{
			if (isset($this->_get[$keys]))
			{
				return new Edit($this->_get[$keys]);
			}
		}
		else
		{
			return $this->_get;
		}

		return new Edit($default);
	}

	/**
	 Analiza żądań po tablicy $_POST
	 Metoda przy udziale obiektu klasy Edit filtruje zmienne.

	 Dla pierwszego parametru będącego typem string:
		*Metoda sprawdza, czy istnieje w tablicy $_POST indeks o tej wartości
			- Jeśli nie istnieje, to zwraca instancje klasy Edit, której przekazana została wartość domyślna z drugiego parametru tej metody.
			- Jeśli istnieje, to zwracana jest instancja klasy Edit, której przekazana została wartość spod indeksu tablicy $_POST.
	 Dla pierwszego parametru będącego tablicą:
		*Metoda analizuję tablicę $_POST coraz głebiej. Im większy indeks parametru pierwszego tej metody, tym głębsze zagnieżdżenie w $_POST.
		*Gdy poszukiwany indeks nie zostanie znaleziony na odpowiednim poziomie, metoda zwraca instancje klasy Edit,
		 której przekazuje wartość domyślną z drugiego parametru tej metody.
		*Gdy poszukiwany indeks zostanie znaleziony, znajdująca się pod nim wartość tablicy $_POST trafia do obiektu klasy Edit,
		 skąd może zostać zwrócona, podlec filtrowaniu lub obróbce.

	 Operowanie na obiekcie Edit:
		show() - zwraca wartość;
		strip() - usuwa znaki specjalne - blokuje wykonywanie kodu HTML;
	**/
	function post($keys = NULL, $default = FALSE)
	{
		if (is_array($keys))
		{
			$array = $this->_post;
			foreach($keys as $key)
			{
				if (isset($array[$key]))
				{
					$array = $array[$key];
				}
				else
				{
					return new Edit($default);
				}
			}
		}
		elseif ($keys !== NULL)
		{
			if (isset($this->_post[$keys]))
			{
				$array = $this->_post[$keys];
			}
			else
			{
				return new Edit($default);
			}

		}
		else
		{
			return $this->_post;
		}
		
		return new Edit($array);
	}
	
	public function postIsset($key)
	{
		return isset($this->_post[$key]);
	}

	/**
	 Analiza żądań po tablicy $_FILES
	 Metoda przy udziale obiektu klasy Edit filtruje zmienne.

	 Pierwszy parametr to nazwa (atrybut name z HTML) pola typu "file".
	 Metoda sprawdza, czy istnieje indeks o tej wartości.
			- Jeśli nie istnieje, to zwraca instancje klasy Edit, której przekazana została wartość domyślna z trzeciego parametru tej metody.
			- Jeśli istnieje, to zwracana jest instancja klasy Edit, której przekazana zostaje wartość spod indeksu tablicy $_FILES.
	 Drugi parametr to nazwa indeksu tworzonego przez PHP w tablicach FILES.
	 Operowanie na obiekcie Edit:
		show() - zwraca wartość;
		strip() - usuwa znaki specjalne - blokuje wykonywanie kodu HTML;
	**/

	public function file($name, $key = 'name', $default = FALSE)
	{
		if (! empty($this->_file[$name]))
		{
			if (! empty($this->_file[$name][$key]))
			{
				return new Edit($this->_file[$name][$key]);
			}
		}

		return new Edit($default);
	}
	
	// Sprawdza, czy jest przesyłany plik przez pole typu "file" o nazwie $name
	public function upload($name)
	{
		return ! empty($this->_file[$name]) && ! empty($this->_file[$name]['name']);
	}
	
	/**
	 * Metoda zwraca dane z pola typu "file" o nazwie $name.
	 * Można jej używać tylko będąc pewnym, czy metoda Request::upload()
	 * zwróciłaby TRUE dla tego pola.
	 * Przykład użycia:
	 *
	 * if ($_request->upload('image_field'))
	 * {
	 *		$_request->_file('image_field')->show();
	 * }
	 *
	 * Jeżeli nie chcesz dodatkowego warunku, skorzystaj z metody Request::file()
	 *
	 * $_request->file('image_field', 'name')->show();
	 *
	 * Domyślnie zwróci FALSE, jeśli nie jest przez pole $name wykonywany upload. 
	 */
	public function _file($name)
	{
		return new Edit($this->_file[$name]);
	}

	function files($keys, $default = FALSE)
	{
		if (is_array($keys))
		{
			$array = $this->_file;
			foreach($keys as $key)
			{
				if (isset($array[$key]))
				{
					$array = $array[$key];
				}
				else
				{
					return new Edit($default);
				}
			}
		}
		else
		{
			if (isset($this->_file[$keys]))
			{
				$array = $this->_file[$keys];
			}
			else
			{
				return new Edit($default);
			}

		}

		return new Edit($array);
	}

		/**
	 Analiza żądań po tablicy $_POST
	 Metoda przy udziale obiektu klasy Edit filtruje zmienne.

	 Dla pierwszego parametru będącego typem string:
		*Metoda sprawdza, czy istnieje w tablicy $_POST indeks o tej wartości
			- Jeśli nie istnieje, to zwraca instancje klasy Edit, której przekazana została wartość domyślna z drugiego parametru tej metody.
			- Jeśli istnieje, to zwracana jest instancja klasy Edit, której przekazana została wartość spod indeksu tablicy $_POST.
	 Dla pierwszego parametru będącego tablicą:
		*Metoda analizuję tablicę $_POST coraz głebiej. Im większy indeks parametru pierwszego tej metody, tym głębsze zagnieżdżenie w $_POST.
		*Gdy poszukiwany indeks nie zostanie znaleziony na odpowiednim poziomie, metoda zwraca instancje klasy Edit,
		 której przekazuje wartość domyślną z drugiego parametru tej metody.
		*Gdy poszukiwany indeks zostanie znaleziony, znajdująca się pod nim wartość tablicy $_POST trafia do obiektu klasy Edit,
		 skąd może zostać zwrócona, podlec filtrowaniu lub obróbce.

	 Operowanie na obiekcie Edit:
		show() - zwraca wartość;
		strip() - usuwa znaki specjalne - blokuje wykonywanie kodu HTML;
	**/
	function session($keys, $default = FALSE)
	{
		if (is_array($keys))
		{
			$array = $_SESSION;
			foreach($keys as $key)
			{
				if (isset($array[$key]))
				{
					$array = $array[$key];
				}
				else
				{
					return new Edit($default);
				}
			}
		}
		else
		{
			if (isset($_SESSION[$keys]))
			{
				$array = $_SESSION[$keys];
			}
			else
			{
				return new Edit($default);
			}

		}

		return new Edit($array);
	}

	public function redirect($file, array $params = array(), $lonely = NULL)
	{	
		$lonely = (array) $lonely;

		if ($params)
		{
			$params = '?'.http_build_query($params).($lonely ? '&'.implode('&', $lonely) : '');
		}
		else
		{
			$params = $lonely ? '?'.implode('&', $lonely) : '';
		}

		header('Location: '.$file.$params);
		exit;
	}
}