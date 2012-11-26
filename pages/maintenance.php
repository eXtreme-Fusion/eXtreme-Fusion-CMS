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

$theme = array(
	'Title' => 'Tryb prac na serwerze. Zapraszamy wkrótce &raquo; '.$_sett->get('site_name'),
	'Keys' => 'Tryb serwisowy, przerwa techniczna, usterka, aktualizacja',
	'Desc' => ''
);

// Blokuje wykonywanie pliku TPL z katalogu szablonu.
define('THIS', TRUE);

$_tpl->assign('maintenance', 
	array(
		'sitebanner' => ADDR_SITE.'templates/'.$_sett->get('site_banner'),
		'sitename' => $_sett->get('site_name'),
		'message' => stripslashes(nl2br($_sett->get('maintenance_message'))),
		'year' => date('Y')
	)
);

/*
	Nale¿y zablokowaæ wyœwietlanie siê wszystkiego poza plikiem maintenance
	Mianowicie wszystkie panele, top, stopka do usuniêcia z podgl¹du.
*/
