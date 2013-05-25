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

class Logger
{
	protected
		$_pdo,				// Obiekt bazy danych
		$_user,				// Obiekt obsługi użytkowników
		$_active = NULL;	// Ustawienia aktywności modułu

	/**
	 * Przygotowuje API Logger'a do zapisywania systemowych logów.
	 *
	 * @param   Database  silnik bazy danych
	 * @param   User      silnik obsługi użytkowników
	 * @return  void
	 */

	public function __construct(User $user, Data $pdo, $active)
	{
		$this->_user = $user;
		$this->_pdo = $pdo;
		$this->_active = $active;
	}

	/**
	 * Zapisuje logi od strony panelu administracyjnego.
	 *
	 * @param   string   wykonana akcja
	 * @param   string   status powodzenia akcji
	 * @param   string   miejsce jej wykonania
	 * @param   array    opis statusu
	 * @return  void
	 */
	protected function adminLog($status, $action, $where, $message = NULL)
	{
		// Przygotowuje dane do zapisania
		$data = serialize(array($action, $status, $where, $message, $this->_user->get('id'), getenv('REMOTE_ADDR')));

		// Zapisuje log w bazie danych
		if ($count = $this->_pdo->exec('INSERT INTO [logs] (`action`, `datestamp`) VALUES (\''.$data.'\', '.time().')'))
		{
			return $count;
		}

		throw new systemException('Wpis do Rejestru zdarzeń nie mógł zostać dodany.');
	}

	/**
	 * Generuje dane do zapisu w bazie
	 *
	 * Parametr czwarty ustawiony na TRUE powoduje, że pomijane są ustawienia aktywności rejestru
	 *
	 * @param   string   wykonana akcja
	 * @param   string   status powodzenia akcji
	 * @param   array    opis statusu
	 * @param   array    dozwolone akcje
	 * @return  mixed
	 */
	public function insertAdminLog($status, $action, array $message = array(), $manual_settings = FALSE, array $values = array())
	{
		if ($manual_settings || $this->_active)
		{
			if ($message)
			{
				if (isset($message[$action]))
				{
					$message = array(
						$message[$action][0], $message[$action][1]
					);
				}
			}
			if ( ! $values)
			{
				$values = array('add', 'edit', 'delete', 'install', 'uninstall');
			}
			if (in_array($action, $values))
			{
				if ($status === 'ok')
				{
					$this->adminLog($action, 'valid', FILE_SELF, isset($message[0]) ? $message[0] : NULL);
				}
				else
				{
					$this->adminLog($action, 'error', FILE_SELF, isset($message[1]) ? $message[1] : NULL);
				}
			}
		}

		return FALSE;
	}

	// Zamieszczanie pozytywnego wpisu w bazie
	public function insertSuccess($action, $message)
	{
		$this->adminLog($action, 'valid', FILE_SELF, $message);
		return $message;
	}

	// Zamieszczanie negatywnego wpisu w bazie
	public function insertFail($action, $message)
	{
		$this->adminLog($action, 'error', FILE_SELF, $message);
		return $message;
	}

	// Usuwanie przestarzałych wpisów
	public function deleteOld($days)
	{
		return $this->_pdo->exec('DELETE FROM [logs] WHERE `datestamp` < '.(time()-$days*86400));
	}
}