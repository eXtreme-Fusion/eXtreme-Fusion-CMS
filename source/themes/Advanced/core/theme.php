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
define("THEME_WIDTH", "100%");
define("THEME_BULLET", "<img class='bullet' src='".ADDR_THEME."images/bullet.gif' alt='->' />"); //bullet image
define('MainBanners', TPL::this()->showbanners());
// Nie istniej¹ca funkcja...
// define('MainDate', TPL::this()->showsubdate());
define('MainMenu', TPL::this()->showSubLinks('', 'menu'));

function render_page($license = FALSE)
{
	// Header
	TPL::this()->assign('THEME_WIDTH', THEME_WIDTH);
	TPL::this()->assign('Menu', MainMenu);
	TPL::this()->assign('Banners', MainBanners);

	// Panels
	if (LEFT)  	TPL::this()->assign('LEFT', LEFT);
	if (RIGHT)  TPL::this()->assign('RIGHT', RIGHT);

	TPL::this()->assign('CONTENT', TOP_CENTER.CONTENT.BOTTOM_CENTER);

	// Footer Desc
	TPL::this()->assign('Footer', stripslashes(TPL::this()->_sett->get('footer')));

	if ( ! $license)
	{
		TPL::this()->assign('Copyright', TPL::this()->showCopyRight());
		TPL::this()->assign('License', TPL::this()->showLicense());
	}

	TPL::this()->template('page.tpl');
}


function render_news()
{
	//	Plugins
}


function opentable($title)
{
	TPL::this()->assign('Begin', 'begin');
	TPL::this()->assign('Title', $title);

	TPL::this()->template('content.tpl');
}


function closetable()
{
	TPL::this()->template('content.tpl');
}


function openside($title, $collapse = FALSE, $state = 'on')
{
	TPL::this()->assign('Begin', 'begin');

	if ($collapse === TRUE)
	{
		$boxname = str_replace(' ', '', $title);
		TPL::this()->assign('Collapse', TPL::this()->panelButton($state,$boxname));
	}

	TPL::this()->assign('Title', $title);

	if ($collapse == TRUE)
	{
		TPL::this()->assign('State', TPL::this()->panelState($state, $boxname));
	}

	TPL::this()->template('panels.tpl');
}


function closeside($collapse = TRUE)
{
	if ($collapse == TRUE)
	{
		TPL::this()->assign('collapse', 'collapse');
	}

	TPL::this()->template('panels.tpl');
}