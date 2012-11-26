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
		$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news.css" rel="stylesheet">');
	}
	
	! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

	$item_id = $_route->getAction();

	if (isNum($item_id))
	{
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
	
			$keyword = array();
			if ($keys = $_tag->getTagFromSupplementAndSupplementID('NEWS', $data['news_id'])){
				foreach($keys as $var){
					$keyword[] = array(
						'keyword_name' => $var['value'],
						'tag_url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link']))
					);
				}
			}
			
			$keyword = array(); $k = array();
			if ($keys = $_tag->getTagFromSupplementAndSupplementID('NEWS', $data['news_id'])){
				foreach($keys as $var){
					$keyword[] = array(
						'keyword_name' => $var['value'],
						'tag_url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link']))
					);
					$k[] = $var['value'];
				}
			}
			
			$k = implode(', ', $k);
			
			$theme = array(
				'Title' => $data['title'].' &raquo; '.$_sett->get('site_name'),
				'Keys' => $k,
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

			$r = $_pdo->exec('UPDATE [news] SET `reads` = `reads`+1 WHERE `id`= :id', array(array(':id', $item_id, PDO::PARAM_INT)));

			$_tpl->assign('news', $d);
			
			if ($data['allow_comments'] === '1')
			{
				$_comment = $ec->comment;
				$_tpl->assign('comments', $_comment->get($_route->getFileName(), $data['news_id']));

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

	$_sbb = $ec->sbb;
	
	$_tpl->assignGroup(array(
		'bbcode' => $_sbb->bbcodes('post'),
		'smiley' => $_sbb->smileys('post')
	));
}
else
{
	$_locale->load('news');

	if ( ! file_exists(DIR_THEME.'templates'.DS.'pages'.DS.'news.tpl'))
	{
		$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news.css" rel="stylesheet">');
	}

	$title = array(
		'page' => '',
		'table' => __('News')
	);

	! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

	// Sprawdzanie, czy użytkownik ma prawo do zobaczenia jakiegokolwiek newsa
	$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0', 
	//$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0 AND `language` = :lang', 
		array(':lang', $_user->getLang(), PDO::PARAM_STR)
	);

	if ($rows)
	{
		# STRONICOWANIE #
		$items_per_page = intval($_user->get('itemnews') ? $_user->get('itemnews') : $_sett->get('news_per_page'));

		if ( ! $_route->getByID(2))
		{
			$_GET['current'] = 1;
		}
		else
		{
			$_GET['current'] = $_route->getByID(2);
		}

		$_GET['rowstart'] = Paging::getRowStart($_GET['current'], $items_per_page);
		
		# / STRONICOWANIE #
		$cache = $_system->cache('news,'.$_user->getCacheName().',page-'.$_GET['current'], NULL, 'news', $_sett->getUns('cache', 'expire_news'));
		if ($cache === NULL)
		{
			$query = $_pdo->getData('
				SELECT tn.`id` AS `news_id`, tn.`title`, tn.`link`, tn.`category`, tn.`language`, tn.`content`, tn.`content_extended`, tn.`author`, tn.`source`, tn.`breaks`, tn.`datestamp`, tn.`access`, tn.`reads`, tn.`draft`, tn.`sticky`, tn.`allow_comments`, tn.`allow_ratings`, tc.`id` AS `cat_id`, tc.`name`, tc.`image`, tu.`id` AS `user_id`,  tu.`username` AS `username`  FROM [news] tn
				LEFT JOIN [users] tu ON tn.`author`= tu.`id`
				LEFT JOIN [news_cats] tc ON tn.`category`= tc.`id`
				WHERE tn.`draft` = 0 AND tn.`access` IN ('.$_user->listRoles().')
				ORDER BY tn.`sticky` DESC, tn.`datestamp` DESC, tn.`title` ASC LIMIT :rowstart,:items_per_page',
				array(
					array(':rowstart', $_GET['rowstart'], PDO::PARAM_INT),
					array(':items_per_page', $items_per_page, PDO::PARAM_INT),
				)
			);

			if ($_pdo->getRowsCount($query))
			{
				foreach ($query as $data)
				{
					$keyword = array();
					if ($keys = $_tag->getTagFromSupplementAndSupplementID('NEWS', $data['news_id'])){
						foreach($keys as $var){
							$keyword[] = array(
								'keyword_name' => $var['value'],
								'tag_url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link']))
							);
						}
					}

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
						// Dodamy może po ciasteczkach zmienę jezyka newsa i wyswietlanie newsów w danym jezyku^^
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
						'num_comments' => $_pdo->getMatchRowsCount('SELECT `id` FROM [comments] WHERE `content_type` = "news" AND `content_id`='.$data['news_id']),
						'allow_comments' => $data['allow_comments'],
						'sticky' => $data['sticky'],
						'url' => $_route->path(array('controller' => 'news', 'action' => $data['news_id'], HELP::Title2Link($data['title'])))
					);
				}

			}

			$_system->cache('news,'.$_user->getCacheName().',page-'.$_GET['current'], $cache, 'news');
		}

		foreach($cache as &$news)
		{
			if ($_user->hasAccess($news['access']))
			{
				$news['num_comments'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [comments] WHERE `content_type` = "news" AND `content_id`='.$news['id']);
			}
			else
			{
				throw new systemException('Error! The user has material in CACHE memory for which does not have permission.');
			}
		}

		$_pagenav = new PageNav(new Paging($rows, $_GET['current'], $items_per_page), $_tpl, 5, array($_route->getFileName(), 'page', FALSE));
		//$_pagenav = new PageNav(new Paging($rows, $_GET['current'], $items_per_page), $_tpl, 5, array($_route->getFileName(), 'page'.$_route->getByID(2), FALSE)); // old

		if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'news_page_nav.tpl'))
		{
			$_pagenav->get($_pagenav->create(), 'news_page_nav', DIR_THEME.'templates'.DS.'paging'.DS);
		}
		else
		{
			$_pagenav->get($_pagenav->create(), 'page_nav');
			// or //
			//$_pagenav->get($_pagenav->create(), 'news_page_nav');
		}

		$_tpl->assign('news', $cache);
	}

	// Załaczanie pluginów
	if (function_exists('render_news'))
	{
		render_news();

		// Scalanie pluginów ze zmiennymi OPT Routera
		$_tpl->data = array_merge($_tpl->data, TPL::get());
	}
}