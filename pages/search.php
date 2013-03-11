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
**********************************************************/
$_locale->load('search');

$theme = array(
	'Title' => 'Wyszukiwarka',
	'Keys' => 'szukaj, wyszukiwarka',
	'Desc' => 'Podstrona, której można użyć do wyszukiwania informacji na stronie.'
);

if ($_request->post('search')->show() && $_request->post('search_type')->show())
{
	$_locale->setSubDir('search');
	$_locale->load($_request->post('search_type')->show());
	
	$search_type = $_request->post('search_type')->show();
	$search_text = $_request->post('search_text')->show();
	
	if ($search_text)
	{
		if ($search_type === 'news')
		{
			$_request->redirect($_route->path(array('controller' => 'search', 'action' => $search_type, $search_text)));
		}
		elseif ($search_type === 'tags')
		{
			$_request->redirect($_route->path(array('controller' => 'search', 'action' => $search_type, $search_text)));
		}
		elseif ($search_type === 'users')
		{
			$_request->redirect($_route->path(array('controller' => 'search', 'action' => $search_type, $search_text)));
		}
		elseif ($search_type === 'all')
		{
			$_request->redirect($_route->path(array('controller' => 'search', 'action' => $search_type, $search_text)));
		}
	}
	$_request->redirect($_route->path(array('controller' => 'search', 'action' => 'error')));
}

if ($_route->getByID(1) === 'news' || $_route->getByID(1) === 'all')
{
	$text = HELP::decodingURL($_route->getByID(2));

	$query = $_pdo->getData('
		SELECT tn.`id` AS `news_id`, tn.`title`, tn.`link`, tn.`category`, tn.`language`, tn.`content`, tn.`content_extended`, tn.`author`, tn.`datestamp`, tc.`name` AS `category_name`, tu.`id` AS `user_id`,  tu.`username` AS `username`  FROM [news] tn
		LEFT JOIN [users] tu ON tn.`author`= tu.`id`
		LEFT JOIN [news_cats] tc ON tn.`category`= tc.`id`
		WHERE tn.`draft` = 0 AND tn.`access` IN ('.$_user->listRoles().')
		AND (tn.`title` LIKE "%":text"%" OR tn.`content` LIKE "%":text"%" OR tn.`content_extended` LIKE "%":text"%")
		ORDER BY tn.`datestamp` DESC, tn.`title` ASC',
		array(':text', $text, PDO::PARAM_STR)
	);
	
	$i = 0; $news = array();
	foreach ($query as $row)
	{
		$news[] = array(
			'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'i' => $i+1,
			'title' => HELP::highlight($row['title'], $text),
			'news_link' => $_route->path(array('controller' => 'news', 'action' => $row['news_id'], HELP::Title2Link($row['title']))),
			'content' => HELP::highlight(substr($row['content'], 0, 350), $text).'...',
			'content_extended' => HELP::highlight(substr($row['content_extended'], 0, 350), $text).'...',
			'date' => HELP::showDate('shortdate', $row['datestamp']),
			'author' => $_user->getUsername($row['user_id']),
			'author_link' => $_route->path(array('controller' => 'profile', 'action' => $row['user_id'], HELP::Title2Link($row['username']))),
			'category' => $row['category_name'],
			'category_link' => $_route->path(array('controller' => 'news_cats', 'action' => $row['category'], HELP::Title2Link($row['category_name'])))
		);
		$i++;
	}

	$_tpl->assign('news', $news);
}

if ($_route->getByID(1) === 'tags' || $_route->getByID(1) === 'all')
{
	$tag = HELP::decodingURL($_route->getByID(2));
	
	$query = $_pdo->getData('SELECT * FROM [tags] WHERE `value` LIKE "%":value"%" ORDER BY `value` ASC',
		array(':value', $tag, PDO::PARAM_STR)
	);

	$i = 0; $tags = array();
	foreach ($query as $row)
	{
		$tags[] = array(
			'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'i' => $i+1,
			'value' => HELP::highlight($row['value'], $tag),
			'link' => $_route->path(array('controller' => 'tags', 'action' => $row['value_for_link']))
		);
		$i++;
	}

	$_tpl->assign('tags', $tags);
}

if ($_route->getByID(1) === 'users' || $_route->getByID(1) === 'all')
{
	$username = HELP::decodingURL($_route->getByID(2));
	
	$query = $_pdo->getData('SELECT * FROM [users] WHERE `status` = 0 AND `username` LIKE "%":username"%" ORDER BY `username` ASC',
		array(':username', $username, PDO::PARAM_STR)
	);

	$i = 0; $users = array();
	foreach ($query as $row)
	{
		$users[] = array(
			'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'i' => $i+1,
			'username' => $_user->getUsername($row['id']),
			'link' => $_route->path(array('controller' => 'profile', 'action' => $row['id'], HELP::Title2Link($row['username']))),
			'role' => $_user->getRoleName($row['role']),
			'visit' => HELP::showDate('shortdate', $row['lastvisit'])
		);
		$i++;
	}

	$_tpl->assign('users', $users);
}

if ($_route->getByID(1) === 'all')
{
	$_tpl->assign('all', TRUE);
}

if ($_route->getByID(1) === 'error')
{
	$_tpl->printMessage('error', 'Nie wpisano tekstu do wyszukiwarki');
}

$_tpl->assignGroup(array(
	'search_text' => $_route->getByID(2) ? HELP::decodingURL($_route->getByID(2)) : '',
	'search_type' => $_route->getByID(1) ? $_route->getByID(1) : $_sett->get('default_search'),
	'searched_type' => $_route->getByID(1) ? $_route->getByID(1) : NULL
));
