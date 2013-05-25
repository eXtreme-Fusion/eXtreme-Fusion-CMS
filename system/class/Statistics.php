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
class Statistics
{
	protected $_pdo;
	protected $_system;
	
	public function __construct($_pdo, $_system)
	{
		$this->_pdo = $_pdo;
		$this->_system = $_system;
	}
	
	// Zapisuje unikaln¹ wizytê u¿ytkownika
	// Adres IP musi byæ filtrowany wczeœniej!
	public function saveUniqueVisit($ip)
	{
		// Odczytywanie informacji z cache
		$data = $this->_system->cache(base64_encode($ip), NULL, 'statistics', 86400*365);
		
		if ($data === NULL)
		{
			$bind = array(':ip', $ip, PDO::PARAM_STR);

			// Sprawdza czy istnieje rekord w bazie o podanym $ip
			$count = $this->_pdo->getSelectCount('SELECT Count(`id`) FROM [statistics] WHERE `ip` = :ip', $bind);

			if ($count === '0')
			{
				return (bool) $this->_pdo->exec('INSERT INTO [statistics] (`ip`) VALUES (:ip)', $bind);
			}
			
			// Zapis informacji do cache
			$this->_system->cache(base64_encode($ip), TRUE, 'statistics');
			
			return TRUE;
		}
		
		return FALSE;
	}

	// Zwraca iloœæ unikalnych wizyt na stronie
	public function getUniqueVisitsCount()
	{
		return $this->_pdo->getSelectCount('SELECT Count(`id`) FROM [statistics]');
	}
}