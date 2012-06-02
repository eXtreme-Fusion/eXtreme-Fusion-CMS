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
$_locale->moduleLoad('lang', 'welcome_message_panel');

if ($_route->getFileName() === 'news') // Chyba lepiej jak tylko na głównej stronie się to otworzy... Po za tym dobrze by było aby w podglądzię newsa/czego kolwiek innego już się to nie wyświetlało.
{
	$_panel->assign('welcome_message', stripslashes($_sett->get('site_intro')));
}