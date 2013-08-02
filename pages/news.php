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

// Konkretny news
if ($_route->getAction() && $_route->getAction() !== 'page')
{
	$_locale->load('news');

	##
	# Zamieszcza w nagłówku strony style i skrypty dla tej podstrony,
	# jeśli istnieją w katalogu szablonu.
	##
	$_head->set($_tpl->getHeaders());

	if ( ! file_exists(DIR_THEME.'templates'.DS.'pages'.DS.'news.tpl'))
	{
		$_head->set('	<link href="'.ADDR_CSS.'news.css" rel="stylesheet">');
	}

	$item_id = $_route->getAction();

	if (isNum($item_id))
	{
		$r = $_pdo->exec('UPDATE [news] SET `reads` = `reads`+1 WHERE `id`= :id', array(array(':id', $item_id, PDO::PARAM_INT)));

		// nazwa pliku bez rozszerzenia, dane do zapisu (jeśli brak to funkcja zwraca dane o ile plik istnieje), czas użyteczności pliku (nadpisanie w przypadku zbyt starej wersji)
		$data = $_system->cache('news_'.$item_id, NULL, 'news', $_sett->getUns('cache', 'expire_news'));
		if ($data === NULL)
		{
			$data = $_pdo->getRow('
					SELECT tn.`id` AS `news_id`, tn.`title`, tn.`link`, tn.`category`, tn.`content`, tn.`content_extended`, tn.`author`, tn.`source`, tn.`description`, tn.`breaks`, tn.`datestamp`, tn.`access`, tn.`reads`, tn.`draft`, tn.`sticky`, tn.`allow_comments`, tn.`allow_ratings`, tc.`id` AS `cat_id`, tc.`name`, tc.`image`, tu.`id` AS `user_id`, tu.`username` AS `username` FROM [news] tn
					LEFT JOIN [users] tu ON tn.`author` = tu.`id`
					LEFT JOIN [news_cats] tc ON tn.`category` = tc.`id`
					WHERE tn.`draft` = 0 AND tn.`id` = :id', array(':id', $item_id, PDO::PARAM_INT)
			);

			$_system->cache('news_'.$item_id, $data, 'news');
		}
		if ($_user->hasAccess($data['access']))
		{
			$_tpl->assign('rows', TRUE);

			$keyword = array(); $k = array();
			if ($keys = $_tag->getTag('NEWS', $data['news_id'])){
				foreach($keys as $var){
					$keyword[] = array(
						'name' => $var['value'],
						'url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link'])),
						'title' => $var['title']
					);
					$k[] = $var['value'];
				}
			}

			$keyword = Html::arrayToLinks($keyword, ', ');

			$theme = array(
				'Title' => $data['title'].' » '.$_sett->get('site_name'),
				'Keys' => implode(', ', $k),
				'Desc' => $data['description']
			);

			$d = array(
				'title_id' => $data['news_id'],
				'title_name' => $data['title'],
				'category_id' => $data['cat_id'],
				'category_name' => $data['name'],
				'category_link' => $_route->path(array('controller' => 'news_cats', 'action' => $data['cat_id'], HELP::Title2Link($data['name']))),
				'author_id' => $data['user_id'],
				'author_name' => $_user->getUsername($data['user_id']),
				'author_link' => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username']))),
				'date' => HELP::showDate('shortdate', $data['datestamp']),
				'datetime' => date('c', $data['datestamp']),
				'source' => $data['source'],
				'keyword' => $keyword,
				'content' => $data['content'],
				'content_ext' => $data['content_extended'],
				'reads' => $data['reads'],
				'num_comments' => $_pdo->getMatchRowsCount("SELECT `id` FROM [comments] WHERE content_type = 'news' AND content_id = '".$data['news_id']."'"),
				'allow_comments' => $data['allow_comments'],
				'sticky' => $data['sticky']
			);

			if ($_user->hasPermission('admin.news'))
			{
				$_tpl->assign('access_edit', TRUE);
			}

			$_tpl->assign('news', $d);

			if ($data['allow_comments'] === '1')
			{
				$_comment = new CommentPageNav($ec, $_pdo, $_tpl);
				$_comment->create($data['news_id'], $_route->getByID(3), $ec->comment->getLimit(), 5, $_route->getFileName());

				if (isset($_POST['comment']['save']))
				{
					$comment = array_merge($comment, array(
						'action'  => 'add',
						'author'  => $_user->get('id'),
						'content' => $_POST['comment']['content']
					));

					// Usuwania cache po dodaniu komentarza
					$_system->clearCache('news');
				}
			}
		}
	}
	else
	{
		HELP::redirect(ADDR_SITE);
	}
}
// Wszystkie newsy
else
{
	$_locale->load('news');

	if ( ! file_exists(DIR_THEME.'templates'.DS.'pages'.DS.'news.tpl'))
	{
		$_head->set('	<link href="'.ADDR_TEMPLATES.'stylesheet/news.css" rel="stylesheet">');
	}

	$title = array(
		'page' => '',
		'table' => __('News')
	);

	$_tag = $ec->tag;

	// Sprawdzanie, czy użytkownik ma prawo do zobaczenia jakiegokolwiek newsa
	$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0');

	if ($rows)
	{
		// Newsów na stronie
		$items_per_page = intval($_user->get('itemnews') ? $_user->get('itemnews') : $_sett->get('news_per_page'));

		// Aktualna strona
		$current_page = $_route->getByID(2, 1);

		// Pobieranie danych z cache
		$cache = $_system->cache('news,'.$_user->getCacheName().',page-'.$current_page, NULL, 'news', $_sett->getUns('cache', 'expire_news'));

		if ($cache === NULL)
		{
			$query = $_pdo->getData('
				SELECT tn.`id` AS `news_id`, tn.`title`, tn.`link`, tn.`category`, tn.`language`, tn.`content`, tn.`content_extended`, tn.`author`, tn.`source`, tn.`breaks`, tn.`datestamp`, tn.`access`, tn.`reads`, tn.`draft`, tn.`sticky`, tn.`allow_comments`, tn.`allow_ratings`, tc.`id` AS `cat_id`, tc.`name`, tc.`image`, tu.`id` AS `user_id`,  tu.`username` AS `username`  FROM [news] tn
				LEFT JOIN [users] tu ON tn.`author`= tu.`id`
				LEFT JOIN [news_cats] tc ON tn.`category`= tc.`id`
				WHERE tn.`draft` = 0 AND tn.`access` IN ('.$_user->listRoles().')
				ORDER BY tn.`sticky` DESC, tn.`datestamp` DESC, tn.`title` ASC LIMIT :rowstart,:items_per_page',
				array(
					array(':rowstart', Paging::getRowStart($current_page, $items_per_page), PDO::PARAM_INT),
					array(':items_per_page', $items_per_page, PDO::PARAM_INT),
				)
			);

			if ($_pdo->getRowsCount($query))
			{
				foreach ($query as $data)
				{
					$keyword = array();
					if ($keys = $_tag->getTag('NEWS', $data['news_id'])){
						foreach($keys as $var){
							$keyword[] = array(
								'name' => $var['value'],
								'url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link'])),
								'title' => $var['value']
							);
						}
					}

					$keyword = Html::arrayToLinks($keyword, ', ');

					$cache[] = array(
						'id' => $data['news_id'],
						'access' => $data['access'],
						'title_id' => $data['news_id'],
						'title_name' => $data['title'],
						'title_link' => HELP::Title2Link($data['title']),
						'category_id' => $data['cat_id'],
						'category_name' => $data['name'],
						'category_link' => $_route->path(array('controller' => 'news_cats', 'action' => $data['cat_id'], HELP::Title2Link($data['name']))),
						'category_image' => $data['image'],
						'language' => __($data['language']),
						// todo: Dodamy może po ciasteczkach zmienę jezyka newsa i wyswietlanie newsów w danym jezyku^^
						'author_id' => $data['user_id'],
						'author_name' => $_user->getUsername($data['user_id']),
						'author_link' => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username']))),
						'date' => HELP::showDate('shortdate', $data['datestamp']),
						'datetime' => date('c', $data['datestamp']),
						'source' => $data['source'],
						'keyword' => $keyword,
						'content' => $data['content'],
						'content_ext' => $data['content_extended'],
						'reads' => $data['reads'],
						'allow_comments' => $data['allow_comments'],
						'sticky' => $data['sticky'],
						'url' => $_route->path(array('controller' => 'news', 'action' => $data['news_id'], HELP::Title2Link($data['title'])))
					);
				}

			}

			$_system->cache('news,'.$_user->getCacheName().',page-'.$current_page, $cache, 'news');
		}

		$comments = $_pdo->getData('SELECT Count(`id`) AS count, `content_id` AS news_id FROM [comments] WHERE `content_type` = \'news\' GROUP BY `content_id`');

		foreach($cache as $key => $news)
		{
			foreach($comments as $comment)
			{
				if ($comment['news_id'] === $news['id'])
				{
					$cache[$key]['num_comments'] = intval($comment['count']);
					continue 2;
				}
			}

			$cache[$key]['num_comments'] = 0;
		}

		$ec->paging->setPagesCount($rows, $current_page, $items_per_page);
		if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'news_page_nav.tpl'))
		{
			$ec->pageNav->get($ec->pageNav->create($_tpl, 5), 'news_page_nav', DIR_THEME.'templates'.DS.'paging'.DS);
		}
		else
		{
			$ec->pageNav->get($ec->pageNav->create($_tpl, 5), 'news_page_nav');
		}

		$_tpl->assign('news', $cache);
	}

	// Załączanie pluginów
	if (method_exists($_theme, 'news'))
	{
		$_theme->news();

		// Scalanie pluginów ze zmiennymi OPT Routera
		$_tpl->data = array_merge($_tpl->data, $_theme->get());
	}
}