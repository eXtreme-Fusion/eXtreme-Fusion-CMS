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

class Edit
{
	private $_var = '';

	public function __construct($var)
	{
		$this->_var = $var;
	}

	/*
     * Filtrowanie tablicy danych
	 * $data = new Edit($array);
	 * $data->arr('key')->show()
	 */
	public function arr($key, $exception = TRUE)
	{
		if (isset($this->_var[$key]))
		{
			return new Edit($this->_var[$key]);
		}

		if ($exception)
		{
			throw new systemException('Indeks '.$key.' nie istnieje w tablicy danych.');
		}

		return FALSE;
	}

	// Alias dla metody arr()
	public function db($key, $exception = TRUE)
	{
		return $this->arr($key, $exception);
	}

	// Stripped value
	public function strip()
	{
		return HELP::strip($this->_var);
	}

	// Value
	public function show()
	{
		return $this->_var;
	}

	// HTML decode
	public function html_decode()
	{
		return html_entity_decode($this->_var);
	}

	public function removeSpecial($remove_spaces = TRUE)
	{
		$new = array();
		
		if ($remove_spaces)
		{
			for($i = 0, $c = strlen($this->_var); $i < $c; $i++)
			{
				if ($this->_var[$i] !== ' ' && ctype_alnum($this->_var[$i]))
				{
					$new[] = $this->_var[$i];
				}
			}
		}
		else
		{
			for($i = 0, $c = strlen($this->_var); $i < $c; $i++)
			{
				if (ctype_alnum($this->_var[$i]))
				{
					$new[] = $this->_var[$i];
				}
			}
		}
		
		return implode('', $new);
	}
	
	// Zamienia tablicę na ciąg łącząc poszczególne wartości separatorem.
	public function implode($sep = DBS)
	{
		return implode($sep, $this->_var);
	}

	// Parametr pierwszy ustawiony na TRUE nakazuje zwrócenie wartości w przypadku gdy jest numeryczna
	public function isNum($return_value = FALSE, $exception = TRUE)
	{
		if (is_numeric($this->_var))
		{
			if ($return_value)
			{
				return $this->_var;
			}

			return TRUE;
		}
		else
		{
			if ($exception)
			{
				throw new systemException('Nieprawidłowy typ danych. Oczekiwano wartości numerycznej, a otrzymano: '.($this->_var === FALSE ? 'boolean FALSE' : print_r($this->_var)));
			}

			return FALSE;
		}
	}
	
	public function isEmail()
	{
		return filter_var($this->_var, FILTER_VALIDATE_EMAIL);
	}
	
	public function isIP()
	{
		return filter_var($this->_var, FILTER_VALIDATE_IP);
	}

	// True/False
	public function isArrayNum($exception = TRUE)
	{
		if (is_array($this->_var) && $this->_var)
		{
			foreach($this->_var as $val)
			{
				if ( ! is_numeric($val))
				{
					if ($exception)
					{
						throw new systemException('Nieprawidłowy typ danych. Oczekiwano wartości numerycznej.');
					}

					return FALSE;
				}
			}

			return TRUE;
		}

		if ($exception)
		{
			throw new systemException('Podana zmienna nie jest tablicą, lub tablica jest pusta');
		}

		return FALSE;
	}

	// Działa jak isArrayNum(), z tymże przy braku błędu zwraca tablicę danych zamiast wartości logicznej
	public function getNumArray($exception = TRUE)
	{
		if ($this->isArrayNum($exception))
		{
			return $this->_var;
		}

		/**
         * Skoro tablica nie jest numeryczna, a kod tej metody dalej się wykonuje,
		 * to $exception musiał być równy FALSE i metoda isArrayNum() zwróciła także FALSE,
		 * zamiast rzucić wyjątkiem.
		 */
		return FALSE;
	}

	public function trim()
	{
		return trim($this->_var);
	}
	
	public function filters()
	{
		foreach(func_get_args() as $filter)
		{
			if (! method_exists($this, $filter))
			{
				throw new systemException('Metoda filtracji <strong>'.$filter.'()</strong> nie istnieje.');
			}
			$this->_var = $this->$filter();
		}

		return $this->_var;
	}
}
