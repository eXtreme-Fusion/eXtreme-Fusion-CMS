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
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$_locale->load('users');

define('THIS', FALSE);

$theme = array(
	'Title' => __('Lista użytkowników').' &raquo; '.$_sett->get('site_name'),
	'Keys' => 'Użytkownicy '.$_sett->get('site_name').', konta '.$_sett->get('site_name').', profile',
	'Desc' => 'Lista aktywnych kont na stronie '.$_sett->get('site_name')
);

// Aktualne filtrowanie
if ($_route->getAction() && is_string($_route->getAction()))
{
	$data = new Edit($_route->getAction());
	$filter = $data->filters('trim', 'strip');
}
else
{
	$filter = 'all';
	$_route->trace(array('action' => 'all'));
}

// Aktualna podstrona filtrowania 
$current_page = $_route->getByID(2, 1);

$rows = $_pdo->getMatchRowsCount('SELECT * FROM [users] WHERE `status` = 0 '.($filter !== 'all' ? 'AND `username` LIKE "'.$filter.'%"' : '').'');

if ($rows)
{
	$cache = $_system->cache('users,'.$_user->getCacheName().',sort_by-'.$filter.',page-'.$current_page, NULL, 'users', $_sett->getUns('cache', 'expire_pages'));
	if ($cache === NULL)
	{
		$query = $_pdo->getData('
			SELECT `id`, `username`, `role`, `status`, `lastvisit`
			FROM [users] WHERE `status` = 0 '.($filter !== 'all' ? 'AND `username` LIKE "'.$filter.'%"' : '').'
			ORDER BY `role` ASC, `username`
			LIMIT :rowstart,:users_per_page',
			array(
				array(':rowstart', Paging::getRowStart($current_page, $_sett->get('users_per_page')), PDO::PARAM_INT),
				array(':users_per_page', intval($_sett->get('users_per_page')), PDO::PARAM_INT),
			)
		);

		if ($query)
		{
			$i = 0;
			foreach($query as $row)
			{
				$cache[] = array(
					'row' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'id' => $row['id'],
					'visit' => ($row['lastvisit'] != 0 ? HELP::showDate('shortdate', $row['lastvisit']) : __('Nie był na stronie')),
					'link' => HELP::profileLink($row['username'], $row['id']),
					'role' => $_user->getRoleName($row['role']),
					'roles' => implode(', ', $_user->getUserRolesTitle($row['id'], 3))
				);
				$i++;
			}
		}
		$_system->cache('users,'.$_user->getCacheName().',sort_by-'.$filter.',page-'.$current_page, $cache, 'users');
	}

	$ec->paging->setPagesCount($rows, $current_page, $_sett->get('users_per_page'));
	if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'users_page_nav.tpl'))
	{
 		$ec->pageNav->get($ec->pageNav->create($_tpl, 10), 'users_page_nav', DIR_THEME.'templates'.DS.'paging'.DS);
	}
	else
	{
 		$ec->pageNav->get($ec->pageNav->create($_tpl, 10), 'users_page_nav');
	}

	$_tpl->assign('users', $cache);
}

$sort = array(
	'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R',
	'S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9'
);

for ($i = 0, $c = count($sort); $i < $c; $i++)
{
	$sort_sel[$i] = array(
		'disp' => $sort[$i],
		'val' => strtolower($sort[$i]),
		'link' => $_route->path(array('controller' => 'users', 'action' => strtolower($sort[$i])))
	);

	if ($filter === $sort_sel[$i]['val'])
	{
		$sort_sel[$i]['sel'] = TRUE;
	}
}

$_tpl->assign('sort', $sort_sel);
if ($filter !== 'all')
{
	$_tpl->assignGroup(array(
		'show_all' => TRUE,
		'link' => $_route->path(array('controller' => 'users'))
	));
}