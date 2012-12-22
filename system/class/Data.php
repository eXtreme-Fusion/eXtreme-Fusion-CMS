<?php defined('EF5_SYSTEM') || exit;
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
/**
 Metody, których nie należy używać na zewnątrz klasy, bo mają swoje rozbudowane odpowiedniki:
	- query() [odpowiednik to np. getData()]
	- prepare() [bindowanie parametrów może się odbyć przez przesłanie danych parametrem do np. getData()]

 Dla query() oraz getData() można wyłaczyć automatyczne fetchowanie().
 Wtedy trzeba bedzie na zewnątrz klasy stosować closeCursor() oraz unset() na zmiennej obiektowej.
 Aby to zrobić, parametr $type należy ustawić na wartość NULL, zaś $fetch na FALSE.
 Ponadto, w takiej sytuacji, aby uzyć metody getRowsCount() trzeba najpierw wszystkie pobrane dane przefetchować,
 np. przez fetchAll() i dopiero przekazać tak utworzoną tablicę do zliczarki wierszy.

 Dla pozostałych metod wyłączanie nie ma sensu, dlatego nie zostało zaimplementowane.
**/
class Data extends PDO
{
	private $_prefix;

	/**
	 Zapisywanie ustawień obiektu
	**/
	public function config($prefix)
	{
		$this->_prefix = $prefix;
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	final protected function isMultiArray($array)
	{
		foreach ($array as $val)
		{
			return is_array($val);
		}
	}

	private function tmpBuild(&$query, array $prepare)
	{
		$query = $this->prepare($query);
		if ($this->isMultiArray($prepare))
		{
			foreach($prepare as $id)
			{
				if (count($id) != 3)
				{
					throw new systemException(__('Too few parameters for function bindValue().'));
				}
				$query->bindValue($id[0], $id[1], $id[2]);
			}
		}
		else
		{
			if (count($prepare) != 3)
			{
				throw new systemException(__('Too few parameters for function bindValue().'));
			}
			$query->bindValue($prepare[0], $prepare[1], $prepare[2]);
		}

		return $query->execute();
	}

	// Zwraca, czy się zapytanie wykonało
	final protected function build(&$query, array $prepare, $close_cursor = TRUE)
	{
		$status = $this->tmpBuild($query, $prepare);

		if ($close_cursor)
		{
			$query->closeCursor();
		}

		return $status;
	}

	// Zwraca ilość przetworzonych elementów
	final protected function buildCount($query, array $prepare, $close_cursor = TRUE)
	{
		if ($this->tmpBuild($query, $prepare))
		{
			$c = $query->rowCount();
			if ($close_cursor)
			{
				$query->closeCursor();
			}

			return $c;
		}

		return 0;
	}
	/**
	 Pobieranie danych wielowierszowych.
	 Metoda nie umożliwia bindowania parametrów. W tym celu należy użyć getData().

	 UWAGA!! Nie zaleca się używania tej metody na zewnątrz klasy.
	 Podstawowe żądania realizują metody zamieszczone niżej.

	 Dla trzeciego parametru tej metody o wartości FALSE:
		*closeCursor() jest wymagany
		*odczyt danych odbywa się przez rozkład PDOStatement object (mozna użyć do tego np. foreach(), fetch() lub fetchAll()).
	 Dla trzeciego parametru tej metody o wartości TRUE (domyślne ustawienie):
		*closeCursor() nie jest wymagany
		*odczyt danych odbywa się przez rozkład tablicy strukturą foreach() lub odwołanie do indeksów poszczególnych wierszy.

	 Drugi parametr określa sposób fetchowania danych. Jest przydatny tylko w przypadku, gdy trzeci został ustawiony na TRUE.
	**/
	public function query($query, $type = PDO::FETCH_ASSOC, $fetch = TRUE)
	{
		$d = parent::query(str_replace(array('[', ']'), array($this->_prefix, ''), $query));
		if ($fetch)
		{
			return $d->fetchAll($type);
		}

		return $d;
	}

	/**
	 Przygotowanie szkieletu zapytania umożliwiającego bindowanie danych.

	 UWAGA!! Nie zaleca się używania tej metody na zewnątrz klasy.
	 Podstawowe żądania realizują metody zamieszczone niżej.
	 Używając metody na zewnątrz, należy pamiętać o closeCursor().
	**/
	public function prepare($query, $drivers = array())
	{
		return parent::prepare(str_replace(array('[', ']'), array($this->_prefix, ''), $query), $drivers);
	}

	/**
	 Metoda dla zapytań INSERT, DELETE, UPDATE - nie pobierających danych.
	 Umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 Zwraca wartość logiczną bool, czy zapytanie się wykonało.

	 closeCursor() nie jest potrzebny. Został zawarty wewnatrz metody.
	**/
	public function exec($query, array $prepare = array())
	{
		if ( ! $prepare)
		{
			return parent::exec(str_replace(array('[', ']'), array($this->_prefix, ''), $query));
		}

		return $this->build($query, $prepare);
	}

	/**
	 Metoda dla zapytań INSERT, DELETE, UPDATE - nie pobierających danych.
	 Umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 Zwraca ilość przetworzonych zapytań.
	 !!Dla UPDATE działa od PHP 5.3.0.

	 closeCursor() nie jest potrzebny. Został zawarty wewnatrz metody.
	**/
	public function execCount($query, array $prepare = array())
	{
		if (preg_match("/update table/i", $query))
		{
			if (floatval(PHP_VERSION) < 5.3)
			{
				throw new systemException('Sorry, your PHP version is too old for method PDO_EXT::execCount(). PHP 5.3 or newer is required.');
			}
		}

		if ( ! $prepare)
		{
			return parent::exec(str_replace(array('[', ']'), array($this->_prefix, ''), $query));
		}

		return $this->buildCount($query, $prepare);
	}

	/**
	 Zwraca ilość pasujących wierszy - dopuszczalne jest tylko zapytanie SELECT.
	 Umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 UWAGA!! Metoda ta wykonuje zapytanie, ale nie zwraca danych z bazy.
	 W przypadku, gdy chcemy z nich skorzystać,
	 należy użyć metody getData() lub query() o domyślnych ustawieniach,
	 a następnie przekazać zmienną pobranych danych metodzie getRowsCount()

	 Stosować tyko dla zapytań SELECT - dla żądań niezwracających danych należy użyć metody exec().

	 closeCursor() nie jest potrzebny. Został zawarty wewnątrz metody.
	**/
	public function getMatchRowsCount($query, array $prepare = array())
	{
		if ( ! $prepare)
		{
			return $this->query($query, NULL, FALSE)->rowCount();
		}

		return $this->buildCount($query, $prepare);
	}

	/**
	 Zwraca ilość pobranych wierszy (zapytania SELECT) przez metodę getData() lub query()
	 (gdy nie zmieniono dla tych metod domyślnej wartości parametru $fetch ustawionego na TRUE).
	 Inaczej mówiąc, metoda działa tylko gdy PDOStatement object został przefetchowany np. przez fetchAll() do postaci tablicy.
	 Zmienną, do której przypisano dane zwrócone przez getData() lub query() (bądź przefetchowane), należy przekazać do tej metody parametrem.
	**/
	public function getRowsCount(array &$statement)
	{
		return count($statement);
	}

	/**
	 Pobieranie danych wielowierszowych.
	 Metoda umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 Dla czwartego parametru tej metody o wartości FALSE:
		*closeCursor() jest wymagany
		*odczyt danych odbywa się przez rozkład PDOStatement object (mozna użyć do tego np. foreach(), fetch() lub fetchAll()).
	 Dla czwartego parametru tej metody o wartości TRUE (domyślne ustawienie):
		*closeCursor() nie jest wymagany
		*odczyt danych odbywa się przez rozkład tablicy strukturą foreach() lub odwołanie do indeksów poszczególnych wierszy.

	 Trzeci parametr określa sposób fetchowania danych. Jest przydatny tylko w przypadku, gdy czwarty został ustawiony na TRUE.
	**/
	public function getData($query, array $prepare = array(), $type = PDO::FETCH_ASSOC, $fetch = TRUE)
	{
		if ( ! $prepare)
		{
			$query = $this->query($query, $type, FALSE);
		}
		else
		{
			$this->build($query, $prepare, FALSE);
		}

		if ($fetch)
		{
			return $query->fetchAll($type);
		}

		return $query;
	}

	/**
	 Pobieranie jednego wiersza danych.
	 Metoda umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 Trzeci parametr określa sposób indeksowania zwracanej tablicy danych.
	 Dane z niej należy wyciagać przez odwołanie do indeksu.

	 closeCursor() nie jest potrzebny. Został zawarty wewnątrz metody.
	**/
	public function getRow($query, array $prepare = array(), $type = PDO::FETCH_ASSOC)
	{
		if ( ! $prepare)
		{
			$query = $this->query($query, NULL, FALSE);
		}
		else
		{
			$this->build($query, $prepare, FALSE);
		}

		$r = $query->fetch($type);
		$query->closeCursor();

		return $r;
	}

	/**
	 Pobieranie danych z jednego pola jednego wiersza.
	 Metoda umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 closeCursor() nie jest potrzebny. Został zawarty wewnątrz metody.
	**/
	public function getField($query, array $prepare = array())
	{
		if ( ! $prepare)
		{
			$query = $this->query($query, NULL, FALSE);
		}
		else
		{
			$this->build($query, $prepare, FALSE);
		}

		$r = $query->fetch(PDO::FETCH_NUM);
		$query->closeCursor();

		return isset($r[0]) ? $r[0] : '';
	}

	/**
	 Pobieranie maksymalnej wartości w danej kolumnie danych.
	 Metoda umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 closeCursor() nie jest potrzebny. Został zawarty wewnątrz metody.
	**/
	public function getMaxValue($query, array $prepare = array())
	{
		$var = $this->getRow($query, $prepare, PDO::FETCH_NUM);
		return intval($var[0]);
	}

	/**
	 Pobieranie maksymalnej wartości w danej kolumnie danych.
	 Metoda umożliwia bindowanie parametrów zapytania, wystarczy podać je drugim parametrem jako tablicę dwuwymiarową.

	 closeCursor() nie jest potrzebny. Został zawarty wewnątrz metody.
	**/
	public function getSelectCount($query, array $prepare = array())
	{
		return current($this->getRow($query, $prepare, PDO::FETCH_NUM));
	}

	/**
	 Sprawdzanie, czy tabela istnieje

	 closeCursor() nie jest potrzebny, gdyż metoda korzysta ze wszystkich pobranych danych.
	**/
	public function tableExists($string)
	{
		$r = parent::query('SHOW TABLES');

		$data = array();
		foreach($r as $e)
		{
			$data[] = $e;
		}
		$table = array();
		foreach($data as $val)
		{
			foreach($val as $d)
			{
				$table[] = $d;
			}
		}

		if ( ! is_array($string))
		{
			return in_array($this->_prefix.$string, $table);
		}
		else
		{
			foreach($string as $row => $key)
			{
				$res[$key] = in_array($this->_prefix.$key, $table);
			}
			return $res;
		}
	}
	
	/**
		Zwraca w postaci numerycznej tablicy identyfikatory 
	 	elementów pobranych z bazy i przesłanych do tej metody parametrem.
	**/
	public function getIDs(array $data, $key = 'id', $exception = TRUE)
	{
		if (!$data && $exception)
		{
			throw new systemException(__('Array is empty.'));
		}
		
		if ($data && !isset($data[0][$key]))
		{
			throw new systemException(__('Required array key not exists.'));
		}
		
		$ret = array();
		foreach($data as $row)
		{
			$ret[] = $row[$key];
		}
		
		return $ret;
	}
	
	/**
		Zwraca w postaci numerycznej tablicy identyfikatory 
	 	elementów pobranych z bazy i przesłanych do tej metody parametrem.
	**/
	public function getIDsQuery(array $data, $key = 'id', $exception = TRUE)
	{
		return implode(',', $this->getIDs($data, $key, $exception));
	}
	
	// Czyści tabelę z przestarzałych wpisów
	function cleanTable($table, $limit)
	{
		if (! is_numeric($limit))
		{
			exit('Błąd w funkcji czyszczącej logi');
		}
		
		$table = HELP::strip($table);
		
		$count = $this->getSelectCount('SELECT Count(`log_id`) FROM ['.$table.']');
		
		$limit = $count-$limit;
		
		if ($limit < 0)
		{
			$limit = 0;
		}
		
		$data = $this->getData('SELECT `log_id` FROM ['.$table.'] ORDER BY `log_id` ASC LIMIT '.$limit);
		$d = array();
		foreach($data as $val)
		{
			$d[] = $val['log_id'];
		}
		
		if ($d) 
		{
			// Usuwanie rekordów
			$this->exec('DELETE FROM ['.$table.'] WHERE `log_id` IN ('.implode(',', $d).')');
		}
		
		return TRUE;
	}
}