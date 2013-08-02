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
echo 1;
// Jeśli nie jest numeryczne to zostanie rzucony wyjątek.
if ( ! $_request->get('current')->isNum()) exit;

$_locale->load('news');
echo 3;
// Sprawdzanie, czy użytkownik ma prawo do zobaczenia jakiegokolwiek newsa
// Sprawdzanie, czy użytkownik ma prawo do zobaczenia jakiegokolwiek newsa
	$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0');

	if ($rows)
	{
		// Newsów na stronie
		$items_per_page = intval($_user->get('itemnews') ? $_user->get('itemnews') : $_sett->get('news_per_page'));

		// Aktualna strona
		$current_page = $_request->get('current')->show();

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
				$_tag = new Tag($_system, $_pdo);
				
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
echo 2;