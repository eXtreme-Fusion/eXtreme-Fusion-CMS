<?php
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
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

    if ( ! $_user->isLoggedIn())
    {
        $_request->redirect(ADDR_ADMIN.'index.php', array('action' => 'login'));
    }

	$_tpl = new Iframe;

	// Pobieranie ulubionych podstron zalogowanego admina
	$fav = $_pdo->getData('SELECT a.`image`, a.`title`, a.`link`, a.`page` FROM [admin_favourites] f LEFT JOIN [admin] a ON f.`page_id` = a.`id` WHERE f.`admin_id` = :id ORDER BY f.`count` DESC LIMIT 0,18', array(':id', $_user->get('id'), PDO::PARAM_INT));

	$pages = array();
	foreach($fav as $key => $row)
	{
		if ($row['page'] !== '5')
		{
			$row['url'] = ADDR_ADMIN.'pages'.DS.$row['link'];
		}
		else
		{
			$row['url'] = ADDR_MODULES.$row['link'];
		}

		$row['title'] = __($row['title']);

		$pages[] = $row;
	}

	$_tpl->assign('pages', $pages);
	$_tpl->template('favourites');
}
catch(optException $exception)
{
	optErrorHandler($exception);
}
catch(systemException $exception)
{
	systemErrorHandler($exception);
}
catch(userException $exception)
{
	userErrorHandler($exception);
}