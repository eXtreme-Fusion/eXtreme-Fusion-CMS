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
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

/*
	Lista systemowych tabeli.
	Ma na celu sprawdzenie czy wszystkie wymagane tabele
	istnieją w bazie danych.
*/
$system_tables = array(
	'admin',
	'admin_favourites',
	'bbcodes',
	'blacklist',
	'comments',
	'groups',
	'links',
	'logs',
	'messages',
	'modules',
	'navigation',
	'news',
	'news_cats',
	'notes',
	'pages',
	'pages_categories',
	'pages_custom_settings',
	'pages_types',
	'panels',
	'permissions',
	'permissions_sections',
	'settings',
	'smileys',
	'statistics',
	'tags',
	'time_formats',
	'users',
	'users_data',
	'users_online',
	'user_fields',
	'user_field_cats'
);

/*
	Sprawdzanie, czy tabele systemowe istnieją w bazie danych.
	Jeśli nie, wyświetl stosowny komunikat.
*/

// Przestawiam na 100, bo zapytanie metody jest mocno czasochłonne
$tables = $_system->cache('tables', NULL, 'system', 100);

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

	// todo: sprawdzac czy katalog install istnieje na serwerze i dopiero wtedy słowo "ponownej instalacji" powinno być linkowane. Jeśli katalogu nie ma, to trzeba w komunikacie napisać że należy wrzucić pliki instalacyjne

	// todo: przerobić poniższe na locale

	if ($_system->detectBrowserLanguage() === 'pl')
	{
		throw new systemException('<div style="text-align:center"><p style="font-weight:bold">PL: Błąd! W bazie danych nie znaleziono poniższych, wymaganych tabel systemowych:</p><br /><p>'.implode(', ', $tables).'</p><br /> <p style="font-weight:bold">Dokonaj <a href="'.ADDR_SITE.'install/" title="eXtreme-Fusion 5 database error" rel="nofollow">ponownej instalacji</a> systemu <a href="http://pl.extreme-fusion.org/" title="eXtreme-Fusion CMS Support">eXtreme-Fusion 5</a> lub przywróć kopię bazy danych.</p></div>');
	}
	elseif ($_system->detectBrowserLanguage() === 'cz')
	{
		throw new systemException('<div style="text-align:center"><p style="font-weight:bold">CZ: Error! The following required database tables do not exists:</p><br /><p>'.implode(', ', $tables).'</p><br /> <p style="font-weight:bold"><a href="'.ADDR_SITE.'install/" title="eXtreme-Fusion 5 database error" rel="nofollow">Make reinstallation</a> of <a href="http://cz.extreme-fusion.org/" title="eXtreme-Fusion CMS Support">eXtreme-Fusion 5</a> or restore a copy of the database.</p></div>');
	}
	else
	{
		throw new systemException('<div style="text-align:center"><p style="font-weight:bold">EN: Error! The following required database tables do not exists:</p><br /><p>'.implode(', ', $tables).'</p><br /> <p style="font-weight:bold"><a href="'.ADDR_SITE.'install/" title="eXtreme-Fusion 5 database error" rel="nofollow">Make reinstallation</a> of <a href="http://en.extreme-fusion.org/" title="eXtreme-Fusion CMS Support">eXtreme-Fusion 5</a> or restore a copy of the database.</p></div>');
	}

	exit;
}