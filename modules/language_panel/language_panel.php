<?php
/***********************************************************
| eXtreme-Fusion 5.0
| Content Management System
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This product is licensed under the BSD License.
| http://extreme-fusion.org/ef5/license/
***********************************************************/
$_locale->moduleLoad('lang', 'language_panel');

if ($_request->post('set_language')->show())
{
	if ($_request->post('language')->show())
	{
		if ($_user->isLoggedIn())
		{
			$cookie_time = 60*60;
		}
		else
		{
			$cookie_time = 60*60*24;
		}
		
		setcookie('lang', $_request->post('language')->show(), time()+$cookie_time);
		$_locale->setLang($_request->post('language')->show());
	}
}

$lang = '';
if (isset($_COOKIES['lang']))
{
	if ($_sett->get('locale') !== $_COOKIES['lang'])
	{
		$lang = $_COOKIES['lang'];
	}
}

$_panel->assign('locales', $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $lang, FALSE, TRUE));