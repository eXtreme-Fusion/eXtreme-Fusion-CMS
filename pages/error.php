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
$_head->set('<meta name="robots" content="noindex" />');

if (isNum($_route->getAction()))
{
	if ($_route->getAction() === 404)
	{
		header("HTTP/1.0 404 Not Found");
		$theme = array(
			'Title' => __('Błąd 404 - Nie znaleziono takiej podstrony'),
			'Desc' => __('Nie znaleziono takiej podstrony')
		);
	}

	$_tpl->assign('error', $_route->getAction());
}
else
{
	header("location: index.html");
	exit;
}