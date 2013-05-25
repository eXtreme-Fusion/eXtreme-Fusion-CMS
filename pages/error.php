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
