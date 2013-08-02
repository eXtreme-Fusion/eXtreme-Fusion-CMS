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
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
| Author: Marcus Gottschalk (MarcusG)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

// Pobranie z cache
$row = $_system->cache('cleaning_raport', NULL, 'chat', 86400);

if ($row === NULL)
{
	$_system->cache('cleaning_raport', time(), 'chat');

	// Usunięcie z pamięci zmiennej $row przechowującej informacje o poprzednim cache
	unset($row);

	$settings = $_pdo->getRow('SELECT * FROM [chat_settings]');


	if ($settings['life_messages'] !== '0' && is_numeric($settings['life_messages']))
	{
		$result = $_pdo->exec('DELETE FROM [chat_messages] WHERE datestamp < '.(time() - ($settings['life_messages']*86400)));
	}
}