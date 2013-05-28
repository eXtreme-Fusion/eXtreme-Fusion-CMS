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
function render_page($banners = TRUE, $menu = TRUE, $left = TRUE, $right = TRUE, $footer = TRUE)
{
	// Logo strony
	if ($banners)	TPL::this()->assign('Logo', trim(TPL::this()->showBanners()));
	
	// Menu strony
	if ($menu)		TPL::this()->assign('Menu', TPL::this()->showSubLinks('', 'menu'));

	// Panels - o ile istnieją na danej pozycji
	if ($left) 		if (LEFT)    TPL::this()->assign('LEFT', LEFT);
	if ($right)		if (RIGHT)   TPL::this()->assign('RIGHT', RIGHT);

	// Część środkowa strony - panele górne, mainbox, dolne
	TPL::this()->assign('CONTENT', TOP_CENTER.CONTENT.BOTTOM_CENTER);

	// Stopka - pobieranie z ustawień
	TPL::this()->assign('RenderFooter', $footer);

	// Stopka - pobieranie z ustawień
	TPL::this()->assign('Footer', TPL::this()->_sett->get('footer'));

	// Wymagane informacje o autorach
	TPL::this()->assign('Copyright', TPL::this()->showCopyright(TRUE));

	// Link do Panelu Admina widoczny dla Administratorów
	TPL::this()->assign('AdminLinks', TPL::this()->showAdminLinks());

	// Licznik strony
	if (TPL::this()->_sett->get('visits_counter_enabled'))
	{
		TPL::this()->assign('VisitsCount', TPL::this()->getVisitsCount());
	}
	
	// Renderowanie pliku szablonu
	TPL::this()->template('page.tpl');
}


function render_news()
{
	// Plugins / Dodatki
}

function opentable($title)
{
	// TODO: wdrozyć by było automatycznie w pliku tpl wrzucane przez klasę odpowiadającą za szablon
	TPL::this()->assign('Begin', 'begin');

	// Tytuł panelu
	TPL::this()->assign('Title', $title);

	// Renderowanie pliku szablonu
	TPL::this()->template('content.tpl');
}

function closetable()
{
	// Renderowanie pliku szablonu
	TPL::this()->template('content.tpl');
}

function openside($title)
{
	// TODO: wdrozyć by było automatycznie w pliku tpl wrzucane przez klasę odpowiadającą za szablon
	TPL::this()->assign('Begin', 'begin');

	// Tytuł panelu
	TPL::this()->assign('Title', $title);

	// Renderowanie pliku szablonu
	TPL::this()->template('panels.tpl');
}

function closeside()
{
	// Renderowanie pliku szablonu
	TPL::this()->template('panels.tpl');
}