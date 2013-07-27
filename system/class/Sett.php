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

//TODO: EF6 - połączyć metody get() oraz getUnserialized() w jedną

class Sett {

	// Przechowuje zaserializowane ustawienia
	protected $_cache = array();

	// Przechowuje obiekt bazy danych, do późniejszych zapytań
	protected $_pdo;

	// Przechowuje obiekt głownego silnika systemu
	protected $_system;

	/**
	 * Ładuje obiekt bazy danych, po czym wczytuje ustawienia do tablicy.
	 *
	 * @param   System    silnik systemu
	 * @param   Database  silnik bazy danych
	 * @return  void
	 */
	public function __construct(System $system, Data $pdo)
	{
		$this->_system = $system;
		$this->_pdo = $pdo;
		$this->load();
	}

	/**
	 * Ładuje ustawienia, po czym zapisuje je w pamięci podręcznej systemu.
	 * Dzięki temu rozwiązaniu, ustawienia są wczytywane z bazy danych tylko
	 * raz, po ich aktualizacji w panelu administracyjnym.
	 *
	 * @return  array
	 * @uses    System
	 * @uses    Database
	 */
	public function load()
	{
		$this->_cache = $this->_system->cache('settings', NULL, 'system');
		if ($this->_cache === NULL)
		{
			$query = $this->_pdo->getData('SELECT * FROM [settings]');
			foreach($query as $data)
			{
				$this->_cache[$data['key']] = $data['value'];
			}
			// NIE USUWAC!! Może się przydać. ~Inscure
			//$settings = unserialize(preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $this->_pdo->getField('SELECT value FROM [settings] LIMIT 1')));

			$this->_system->cache('settings', $this->_cache, 'system');
		}
		return $this->_cache;
	}

	/**
	 * Ułatwia aktualizacje ustawień, które są serializowane.
	 *
	 * Jako parametr pierwszy należy podać klucz ustawienia, a następnie
	 * nazwę klucza tablicy, pod jakim na zostać zapisane ustawienie
	 * o wartości przesłanej trzecim parametrem.
	 *
	 * Metoda zwróci zserializowaną tablicę ustawień
	 *
	 * @return  array
	 * @uses    System
	 * @uses    Database
	 */
	public function serialize($setting_key, $array_key, $value)
	{
		if ($data = $this->get($setting_key))
		{
			$data = unserialize($data);
			$data[$array_key] = $value;

			return serialize($data);
		}

		throw new systemException('Błąd przy próbie aktualizacji serializowanego ustawienia o kluczu <strong>'.$setting_key.'</strong>');
	}

	/**
	 * Czyści pamięc podręczną ustawień
	 *
	 * @return  void
	 */
	public function clearCache()
	{
		$this->_system->clearCache('system', 'settings');
	}

	/**
	 * Zapisuje ustawienia w bazie danych oraz w pamięci podręcznej systemu.
	 *
	 *     // Zapisze ustawienie `foo` wartością `bar`
	 *     $_sett->update(array('foo' => 'bar'));
	 *
	 * @param   array  ustawienia do zapisania
	 * @return  boolean
	 * @uses    Database
	 */
	public function update(array $settings)
	{
		foreach ($settings as $key => $value)
		{
			if (isset($this->_cache[$key]))
			{
				// Zapisuje nową zawartość ustawienia
				$this->_cache[$key] = $value;

				// Zapisuje ustawienie w bazie danych
				$count = $this->_pdo->exec('UPDATE [settings] SET `value` = :value WHERE `key` = :key', array(
					array(':key', $key, PDO::PARAM_STR),
					array(':value', $value, PDO::PARAM_STR)
				));

				if (! $count)
				{
					throw new systemException('Błąd aktualizacji: Ustawienie o kluczu <strong>'.$key.'</strong> nie istnieje.');
				}
			}
			else
			{
				throw new systemException('Błąd aktualizacji: Ustawienie o kluczu <strong>'.$key.'</strong> nie istnieje.');
			}
		}

		// Czyści pamięć podręczną
		$this->clearCache('system');
				
		// Ustawienia zostały zapisane
		return TRUE;
	}

	/**
	 * Zapisuje ustawienia w bazie danych oraz w pamięci podręcznej systemu.
	 *
	 *     // Zapisze ustawienie `foo` wartością `bar`
	 *     $_sett->update(array('foo' => 'bar'));
	 *
	 * @param   array  ustawienia do zapisania
	 * @return  boolean
	 * @uses    Database
	 */
	public function insert(array $settings)
	{
		foreach ($settings as $key => $value)
		{
			if ( ! isset($this->_cache[$key]))
			{
				// Zapisuje nową zawartość ustawienia
				$this->_cache[$key] = $value;
				// Zapisuje ustawienie w bazie danych
				$this->_pdo->exec('INSERT INTO [settings] (`key`, `value`) VALUES (:key, :value)', array(
					array(':key', $key, PDO::PARAM_STR),
					array(':value', $value, PDO::PARAM_STR)
				));
				// Czyści pamięć podręczną
				$this->_clearCache();
			}
			else
			{
				// Wystąpił błąd, ustawienie o tej nazwie już istnieje
				return FALSE;
			}
		}

		// Ustawienia zostały zapisane
		return TRUE;
	}

	/**
	 * Wyszukuje ustawienia pod kluczem `$key`. Jeżeli nie istnieje,
	 * zwracacana wartość to `FALSE`. Opuszczenie argumentu `$key` powoduje,
	 * że zwracane są wszystkie ustawienia w postaci tablicy.
	 *
	 * Podanie drugiego parametru jako FALSE spowoduje, że zamiast rzucenia wyjątku,
	 * metoda zwróci FALSE, gdy $key nie zostanie znaleziony w tablicy z ustawieniami.
	 *
	 * @param   string  klucz ustawienia
	 * @param   bool	określanie akcji do wykonania dla niepowodzenia
	 * @return  mixed
	 */
	public function get($key = NULL, $exception = TRUE)
	{
		if ($key === NULL)
		{
			return $this->_cache;
		}

		if (isset($this->_cache[$key]))
		{
			return $this->_cache[$key];
		}

		if ($exception)
		{
			throw new systemException('Błąd przy pobieraniu ustawienia: Ustawienie o kluczu <strong>'.$key.'</strong> nie istnieje.');
		}

		return FALSE;
	}

	public function getUnserialized($setting_key, $array_key = NULL, $exception = TRUE)
	{
		if (isset($this->_cache[$setting_key]))
		{
			if ($array_key)
			{
				$data = unserialize($this->_cache[$setting_key]);
				if (isset($data[$array_key]))
				{
					return $data[$array_key];
				}

				if ($exception)
				{
					throw new systemException('Błąd przy pobieraniu ustawienia serializowanego: Ustawienie o kluczu <strong>'.$setting_key.'</strong> oraz indeksie <strong>'.$array_key.'</strong> nie istnieje.');
				}
			}
			else
			{
				return unserialize($this->_cache[$key]);
			}
		}
		else
		{
			if ($exception)
			{
				throw new systemException('Błąd przy pobieraniu ustawienia: Ustawienie o kluczu <strong>'.$setting_key.'</strong> nie istnieje.');
			}
		}

		return FALSE;
	}
	
	// Alias do Sett::getUnserialized()
	public function getUns($setting_key, $array_key = NULL, $exception = TRUE)
	{
		return $this->getUnserialized($setting_key, $array_key, $exception);
	}
}