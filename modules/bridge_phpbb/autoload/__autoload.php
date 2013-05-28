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

// Pobranie z cache zapytania sprawdzającego czy moduł jest zainstalowany
$row = $_system->cache('install_status', NULL, 'bridge_phpbb', 60);

if ($row === NULL)
{
	// Sprzwdzanie czy moduł znajduje się na liście zainstalowanych modułów oraz umieszczenie go w cache
	$row = $_pdo->getRow('SELECT `id` FROM [modules] WHERE `folder` = :folder',
		array(':folder', 'bridge_phpbb', PDO::PARAM_STR)
	);
	$_system->cache('install_status', $row, 'bridge_phpbb');
}

if ($row)
{
	// Usunięcie z pamięci zmiennej $row przechowującej informacje o poprzednim cache
	unset($row);
	
	// Sprawdzenie czy aktualnie znajdujemy się na podstronie rejestracji
	if ($_route->getFileName() === 'register')
	{
		include DIR_MODULES.'bridge_phpbb'.DS.'class'.DS.'PhpBB.php';
		
		! class_exists('PhpBB') || $_phpbb = new PhpBB($_pdo, $_system, $_user);
	}
}