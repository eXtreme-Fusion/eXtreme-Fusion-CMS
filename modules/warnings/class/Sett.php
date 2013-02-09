<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
class WarningsSett {

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
		$this->_cache = $this->_system->cache('settings', NULL, 'warnings');
		if ($this->_cache === NULL)
		{
			$query = $this->_pdo->getData('SELECT * FROM [warnings_sett]');
			foreach($query as $data)
			{
				$this->_cache[$data['key']] = $data['value'];
			}

			$this->_system->cache('settings', $this->_cache, 'warnings');
		}
		return $this->_cache;
	}

	/**
	 * Czyści pamięc podręczną ustawień
	 *
	 * @return  void
	 */
	public function clearCache()
	{	
		$this->_system->clearCache('warnings', 'settings');
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
	public function update(array $warnings_sett)
	{
		foreach ($warnings_sett as $key => $value)
		{
			if (isset($this->_cache[$key]))
			{
				// Zapisuje nową zawartość ustawienia
				$this->_cache[$key] = $value;

				// Zapisuje ustawienie w bazie danych
				$count = $this->_pdo->exec('UPDATE [gallery_warnings] SET `value` = :value WHERE `key` = :key', array(
					array(':key', $key, PDO::PARAM_STR),
					array(':value', $value, PDO::PARAM_STR)
				));

				if (! $count)
				{
					throw new systemException(__('Update error', array(':key' => $key)));
				}

				// Czyści pamięć podręczną
				$this->clearCache();
			}
			else
			{
				throw new systemException(__('Update error', array(':key' => $key)));
			}
		}
		// Ustawienia zostały zapisane
		return TRUE;
	}

	/**
	 * Wyszukuje ustawienia pod kluczem `$key`. Jeżeli nie istnieje,
	 * to rzucany jest wyjątek. Opuszczenie argumentu `$key` powoduje,
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
			throw new systemException(__('Error while getting setting', array(':key' => $key)));
		}

		return FALSE;
	}
}