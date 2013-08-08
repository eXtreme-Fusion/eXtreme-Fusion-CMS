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
$_locale->load('error');

if (isNum($_route->getAction()))
{
	if ($_route->getAction() === 404)
	{
		header("HTTP/1.0 404 Not Found");
		$theme = array(
			'Title' => __('Error 404 - Page not found'),
			'Desc' => __('Not found this pages')
		);
	}
	elseif ($_route->getAction() === 401)
	{
		header("HTTP/1.0 401 Unauthorized");
		$theme = array(
			'Title' => __('Error 401 - Unauthorized'),
			'Desc' => __('Unauthorised access')
		);
	}
	elseif ($_route->getAction() === 403)
	{
		header("HTTP/1.0 403 Forbidden");
		$theme = array(
			'Title' => __('Error 403 - Forbidden'),
			'Desc' => __('Access this page is forbidden')
		);
	}
	elseif ($_route->getAction() === 500)
	{
		header("HTTP/1.0 500 Forbidden");
		$theme = array(
			'Title' => __('Error 500 - Internal Server Error'),
			'Desc' => __('Access this page is forbidden')
		);
	}

	$_tpl->assign('error', $_route->getAction());
}
else
{
	header("location: index.html");
	exit;
}
