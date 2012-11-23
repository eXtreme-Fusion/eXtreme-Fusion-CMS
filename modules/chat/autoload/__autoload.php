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

// Pobranie z cache
$row = $_system->cache('cleaning_raport', NULL, 'chat', 86400);

if ($row === NULL)
{
	$_system->cache('cleaning_raport', time(), 'chat');

	// Usuniêcie z pamiêci zmiennej $row przechowuj¹cej informacje o poprzednim cache
	unset($row);

	$settings = $_pdo->getRow('SELECT * FROM [chat_settings]');


	if ($settings['life_messages'] !== '0' && is_numeric($settings['life_messages']))
	{
		$result = $_pdo->exec('DELETE FROM [chat_messages] WHERE datestamp < '.(time() - ($settings['life_messages']*86400)));
	}
}