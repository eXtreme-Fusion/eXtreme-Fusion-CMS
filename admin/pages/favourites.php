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

	$_locale->load('favourites');
	$_locale->load('pages');
	
	$_tpl = new Iframe;
	
	// Ilość pobieranych elementów
	$_fav->extend(array('limit' => 18));
	
	// Pobieranie ulubionych podstron zalogowanego admina
	$fav = $_fav->get($_user->get('id'));

	$pages = array();
	foreach($fav as $key => $row)
	{
		if (! $_user->hasPermission($row['permissions']))
		{
			// Usuwanie podstron z ulubionych, do których admin nie ma dostępu
			$_pdo->exec('DELETE FROM [admin_favourites] WHERE `id` = '.intval($row['id']));
			continue;
		}
		if ($row['page'] !== '5')
		{
			$row['url'] = ADDR_ADMIN.'pages/'.$row['link'];
			$row['src'] = ADDR_ADMIN_IMAGES.'pages/'.$row['image'];
		}
		else
		{
			$row['url'] = ADDR_MODULES.$row['link'];
			$row['src'] = ADDR_MODULES.$row['image'];
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