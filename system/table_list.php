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

/*
	Lista systemowych tabeli.
	Ma na celu sprawdzenie czy wszystkie wymagane tabele
	istnieją w bazie danych.
*/
$system_tables = array(
	'admin',
	'bbcodes',
	'blacklist',
	'captcha',
	'comments',
	'flood_control',
	'links',
	'logs',
	'messages',
	'modules',
	'news',
	'news_cats',
	'notes',
	'permissions',
	'permissions_sections',
	'ratings',
	'groups',
	'panels',
	'sessions',
	'settings',
	'settings_inf',
	'navigation',
	'smileys',
	'submissions',
	'user_fields',
	'user_field_cats',
	'users',
	'users_data',
	'users_online'
);

/*
	Sprawdzanie, czy tabele systemowe istnieją w bazie danych.
	Jeśli nie, wyświetl stosowny komunikat.
*/

$tables = $_system->cache('tables', NULL, 'system', 10);

if ($tables === NULL)
{
	$res = $_pdo->tableExists($system_tables);
	$tables = array();
	foreach ($res as $key => $value)
	{
		if ($value === FALSE)
		{
			$tables[] = $key;
		}
	}
	$_system->cache('tables', $tables, 'system');
}

if ($tables)
{
	header('Content-Type: text/html; charset=utf-8');
	
	if ($_system->detectBrowserLanguage() === 'pl')
	{
		throw new systemException('<div style="text-align:center;font-weight:bold;">PL: Tabele systemowe takie jak: <strong>'.implode(', ', $tables).'</strong> nie istnieją. <p>Przywróć kopię bazy danych lub dokonaj ponownej instalacji systemu eXtreme-Fusion 5</p></div>');
	}
	elseif ($_system->detectBrowserLanguage() === 'cz')
	{
		throw new systemException('<div style="text-align:center;font-weight:bold;">CZ: System tables, such as: <strong>'.implode(', ', $tables).'</strong> do not exist. <p> Restore a copy of the database or make re-installation of eXtreme-Fusion 5</p></div>');
	}
	else
	{
		throw new systemException('<div style="text-align:center;font-weight:bold;">EN: System tables, such as: <strong>'.implode(', ', $tables).'</strong> do not exist. <p> Restore a copy of the database or make re-installation of eXtreme-Fusion 5</p></div>');
	}
}