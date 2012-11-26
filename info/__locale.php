<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 The eXtreme-Fusion Crew                |
| http://www.extreme-fusion.org/                                 |
+----------------------------------------------------------------+
| The work is provided under the terms of this Creative Commons  |
| public license ("CCPL" or "License"). the work is protected by |
| copyright and/or other applicable law. Any use of the work     |
| other than as authorized under this license or copyright law   |
| is prohibited.                                                 |
|                                                                |
| By exercising any rights to the work provided here, you accept |
| and agree to be bound by the terms of this license. To the     |
| extent this license may be considered to be a contract, the    |
| licensor grants you the rights contained here in consideration |
| of your acceptance of such terms and conditions.               |
+----------------------------------------------------------------+
| http://creativecommons.org/licenses/by/3.0/pl/legalcode        |
+---------------------------------------------------------------*/
require_once 'sitecore.php';

// Ładuje testowy plik z tłumaczeniem - `locale.php`
$_locale->load('locale.php');

/**
 * Jeżeli chcemy załadować więcej niż jeden plik z tlumaczeniami
 * należy użyć tablicy z nazwami plików:
 *
 *     $_locale->load(array('foo.php', 'bar.php'));
 *
 */

// W plikach PHP można użyć nastepującej składni:

echo __('Reset password'); // Dla prostych tekstów

$username = 'webking';
echo __('Hello :username!', array(':username' => $username)); // Dla tekstów z dynamicznymi parametrami

/**
 * W szablonach należy używać wtyczki `i18n` (internalization),
 * do której ładowane są wszystkie teksty w plikach ładowanych
 * metodą `load`, np. $_locale->load('foobar.php');
 *
 *     {i18n('Reset password')}
 *
 *     {i18n('Hello :username!', array(':username' => $user.username))}
 */

/**
 * W tym systemie jednym z zalet jest to, że większość tekstów jest w kluczach
 * np. `Reset password`, `Username`, `Password` - takich tekstów nie trzeba umieszczać
 * w pliku z angielskim tłumaczeniem, natomiast dla długich tekstów jako klucz posłuży
 * krótki opis, aby było wiadomo do czego się odnosi:
 *
 *     // Polska forma
 *     'Forgotten password' => 'Jeżeli zapomniałeś hasła <a href=":link">kliknij tutaj</a>',
 *
 *     // Angielska forma
 *     'Forgotten password' => 'If you forgot your password <a href=":link">click here</a>',
 *
 * Takie teksty muszą być umieszczone w każdym języku.
 */

/**
 * TODO:
 *     - informacje o localu,
 */