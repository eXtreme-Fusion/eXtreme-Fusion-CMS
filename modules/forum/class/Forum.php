<?php defined('EF5_SYSTEM') || exit;
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
class Forum {

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
	}
}