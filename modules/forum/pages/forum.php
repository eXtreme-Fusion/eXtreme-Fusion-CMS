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
// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

require __DIR__.DS.'init.php';

// Inicjacja systemu
$_init = new ForumInit;

// Przekazywanie obiektów
$_init->setObjs(array(
	'data' => $_pdo,
	'request' => $_request,
	'router' => $_route,
	'locale' => $_locale
));

// Wybór kontrolera i przekazanie sterowania aplikacj¹
$_init->show();