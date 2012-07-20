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
	
	// Sprawdzenie czy aktualnie znajdujemy się w galerii
	if ($_route->getFileName() === 'register')
	{
		include DIR_MODULES.'bridge_phpbb'.DS.'class'.DS.'Phpbb.php';
		
		! class_exists('PhpBB') || $_phpbb = new PhpBB($_pdo, $_system, $_user);
	}
}