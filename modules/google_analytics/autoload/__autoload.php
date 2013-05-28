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
$row = $_system->cache('install_status', NULL, 'google_analytics', 60);

if ($row === NULL)
{
	// Sprzwdzanie czy moduł znajduje się na liście zainstalowanych modułów oraz umieszczenie go w cache
	$row = $_pdo->getRow('SELECT `id` FROM [modules] WHERE `folder` = :folder',
		array(':folder', 'google_analytics', PDO::PARAM_STR)
	);
	$_system->cache('install_status', $row, 'google_analytics');
}

if ($row)
{
	// Usunięcie z pamięci zmiennej $row przechowującej informacje o poprzednim cache
	unset($row);
	
	// Pobanie z cache zapytania pobierającego ustawienia modułu Google Analytics
	$row = $_system->cache('module_data', NULL, 'google_analytics', 60);

	if ($row === NULL)
	{
		// Pobieranie ustawień z bazy danych oraz umieszczenie go w cache
		$row = $_pdo->getRow('SELECT `status`, `profile_id` FROM [google_analytics_sett]');
		$_system->cache('module_data', $row, 'google_analytics');
	}

	if ($row['status'])
	{
		$_head->set('<script>
			var _gaq = _gaq || [];
			_gaq.push(["_setAccount", "'.$row['profile_id'].'"]);
			_gaq.push(["_trackPageview"]);
			(function() 
			{
				var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
				ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
				var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>');
	}
}