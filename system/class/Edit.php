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
				throw new systemException('Nieprawidłowy typ danych. Oczekiwano wartości numerycznej, a otrzymano: '.$this->_var);
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

	//==================================
	//PL: Zmiana tytułów dla linków
	//EN: Changing the title for links
	//==================================
	public function setTitleForLinks()
	{
		$a = array("Ą","Ś","Ę","Ó","Ł","Ż","Ź","Ć","Ń","ą","ś","ę","ó","ł","ż","ź","ć","ń","ü","&quot"," - "," ",".","!",";",":","(",")","[","]","{","}","|","?",",","/","+","=","#","@","$","%","^","&","*");
		$b = array("A","S","E","O","L","Z","Z","C","N","a","s","e","o","l","z","z","c","n","u","","-","_","","","","","","","","","","","","","","","","","","","","","","","");
		$c = array("--","---","__","___");
		$d = array("-","-","_","_");
		$e = strtolower(str_replace($a,$b,$this->_var));
		$f = str_replace($c,$d,$e);
		return $f;
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