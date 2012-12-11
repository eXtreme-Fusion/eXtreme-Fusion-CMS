<?php defined('EF5_SYSTEM') || exit;
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This product is licensed under the BSD License.
| http://extreme-fusion.org/ef5/license/
***********************************************************/

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

	// todo: sprawdzac czy katalog install istnieje na serwerze i dopiero wtedy słowo "ponownej instalacji" powinno być linkowane. Jeśli katalogu nie ma, to trzeba w komunikacie napisać że należy wrzucić pliki instalacyjne
	
	// todo: przerobić poniższe na locale
	
	if ($_system->detectBrowserLanguage() === 'pl')
	{
		throw new systemException('<div style="text-align:center"><p style="font-weight:bold">PL: Błąd! W bazie danych nie znaleziono poniższych, wymaganych tabel systemowych:</p><br /><p>'.implode(', ', $tables).'</p><br /> <p style="font-weight:bold">Dokonaj <a href="'.ADDR_SITE.'install/" title="eXtreme-Fusion 5 reinstallation">ponownej instalacji</a> systemu <a href="http://pl.extreme-fusion.org/" title="eXtreme-Fusion CMS Support">eXtreme-Fusion 5</a> lub przywróć kopię bazy danych.</p></div>');
	}
	elseif ($_system->detectBrowserLanguage() === 'cz')
	{
		throw new systemException('<div style="text-align:center"><p style="font-weight:bold">CZ: Error! The following required database tables do not exists:</p><br /><p>'.implode(', ', $tables).'</p><br /> <p style="font-weight:bold">Dokonaj <a href="'.ADDR_SITE.'install/" title="eXtreme-Fusion 5 reinstallation">Make re-installation</a> of <a href="http://pl.extreme-fusion.org/" title="eXtreme-Fusion CMS Support">eXtreme-Fusion 5</a> or restore a copy of the database.</p></div>');
	}
	else
	{
		throw new systemException('<div style="text-align:center"><p style="font-weight:bold">EN: Error! The following required database tables do not exists:</p><br /><p>'.implode(', ', $tables).'</p><br /> <p style="font-weight:bold">Dokonaj <a href="'.ADDR_SITE.'install/" title="eXtreme-Fusion 5 reinstallation">Make re-installation</a> of <a href="http://pl.extreme-fusion.org/" title="eXtreme-Fusion CMS Support">eXtreme-Fusion 5</a> or restore a copy of the database.</p></div>');
	}
}