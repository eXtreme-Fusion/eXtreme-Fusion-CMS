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
$_locale->load('news_cats');

/*
 * To do
 * Przekazać nagłówki do systemu
 * $theme = array(
		'Title' => '',
		'Keys' => '',
		'Desc' => ''
	);
 */

// Ładowanie styli z szablonu
$_head->set($_tpl->getHeaders());

// Jeśli w szablonie nie istnieje plik news_cats.tpl, to załadowane zostaną domyślne style
if ( ! $_theme->tplExists())
{
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news.css" media="screen" rel="stylesheet">');
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news_cats.css" media="screen" rel="stylesheet">');
}

// Przesyłanie linków do szablonu
$_tpl->assign('all_news_url', $_route->path(array('controller' => 'news')));
$_tpl->assign('all_news_cats_url', $_route->path(array('controller' => 'news_cats')));

// Konkretna kategoria
if ($_route->getAction())
{
	// Przesyłanie do szablonu informacji o położeniu na stronie
	$_tpl->assign('page', 'category');

	// Aktualna podstrona w danej kategorii
	$current_page = $_route->getByID(3, 1);

	/**
	 * Pobieranie danych z cache.
	 * Zawiera: cat_id, cat_name, cat_image, cat_news_count
	 **/
	$category = $_system->cache('news_cats,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current_page, NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));

	// Sprawdzanie, czy cache nie istnieje
	if ($category === NULL)
	{
		// Pobieranie danych z bazy
		$rows = $_pdo->getRow('SELECT `id`, `name`, `image` FROM [news_cats] WHERE `id` = :id',
			array(':id', $_route->getAction(), PDO::PARAM_INT)
		);

		// Zmienna przechowująca dane kategorii newsów, która trafi do cache
		$category = array();

		// Sprawdzanie, czy pobrano dane z bazy - czy kategoria istnieje
		if ($rows)
		{
			$category = array(
				'cat_id'    => $rows['id'],
				'cat_name'  => $rows['name'],
				'cat_image' => $rows['image'],
				'cat_news_count'  => $_pdo->getSelectCount('SELECT COUNT(`id`) FROM [news] WHERE `category` = :category AND `access` IN ('.$_user->listRoles().')',
					array(':category', $_route->getAction(), PDO::PARAM_INT)
				)
			);
		}

		// Zapis danych do cache ze zmiennej $category
		$_system->cache('news_cats,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current_page, $category, 'news_cats');
	}

	// Sprawdzanie, czy kategoria istnieje
	if ($category)
	{
		# STRONICOWANIE #
		$items_per_page = intval($_sett->get('news_cats_item_per_page'));

		// Pobieranie danych z cache dla występujących newsów w danej kategorii, do których użytkownik ma wgląd
		$cache = $_system->cache('news,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current_page, NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));

		// Sprawdzanie, czy cache nie istnieje
		if ($cache === NULL)
		{
			// Pobieranie danych z bazy
			$query = $_pdo->getData('
				SELECT tn.`id`, tn.`title`, tn.`access`, tn.`link`, tn.`content`, tn.`author`, tn.`reads`, tn.`datestamp`, tu.`id` AS `user_id`, tu.`username`
				FROM [news] tn
				LEFT JOIN [users] tu ON tn.`author`= tu.`id`
				WHERE tn.`category` = :category AND tn.`access` IN ('.$_user->listRoles().')
				ORDER BY tn.`datestamp` ASC, tn.`title` LIMIT :rowstart,:items_per_page',
				array(
					array(':category', $_route->getAction(), PDO::PARAM_INT),
					array(':rowstart', Paging::getRowStart($current_page, $items_per_page), PDO::PARAM_INT),
					array(':items_per_page', $items_per_page, PDO::PARAM_INT)
				)
			);

			// Zmienna przechowująca dane newsów, które trafią do cache
			$cache = array();

			// Sprawdzanie, czy pobrano dane
			if ($query)
			{
				foreach($query as $data)
				{
					$cache[] = array(
						'news_title_id'     => $data['id'],
						'news_title_name'   => $data['title'],
						'news_content'      => HELP::truncate($data['content']),
						'news_author_id'    => $data['user_id'],
						'news_author_name'  => $_user->getUsername($data['user_id']),
						'news_reads'        => $data['reads'],
						'news_datestamp'    => HELP::showDate('shortdate', $data['datestamp']),
						'news_url'          => $_route->path(array('controller' => 'news', 'action' => $data['id'], HELP::Title2Link($data['title']))),
						'profile_url'       => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username']))),
						'news_datetime' 	=> date('c', $data['datestamp'])
					);
				}
			}

			// Zapisywanie danych ze zmiennej $cache do cache
			$_system->cache('news,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current_page, $cache, 'news_cats');
		}

		// Sprawdzanie, czy istnieją newsy w danej kategorii
		if ($cache)
		{
			$ec->paging->setPagesCount($category['cat_news_count'], $current_page, $items_per_page);

			if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'news_cats_nav.tpl'))
			{
				$ec->pageNav->get($ec->pageNav->create($_tpl, 5), 'news_cats_nav', DIR_THEME.'templates'.DS.'paging'.DS);
			}
			else
			{
				$ec->pageNav->get($ec->pageNav->create($_tpl, 5), 'page_nav');
			}

			// Przesyłanie do szablonu danych o newsach
			$_tpl->assign('rows', $cache);
		}

		// Przesyłanie do szablonu danych o kategorii
		$_tpl->assign('category', $category);
	}
	else
	{
		// Kategoria nie istnieje. Błąd 404.
		$_route->trace(array('controller' => 'error', 'action' => 404));
	}

}
// Wszystkie kategorie
else
{
	// Przesyłanie do szablonu informacji o położeniu na stronie
	$_tpl->assign('page', 'overview');

	// Pobieranie z cache informacji o kategoriach i prawach dostępu przypisanych do newsów
	$cache_count = $_system->cache('news_cats_count', NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));

	// Sprawdzanie, czy nie pobrano danych
	if ($cache_count === NULL)
	{
		// Pobieranie danych z bazy
		$cache_count = $_pdo->getData('SELECT category, access FROM [news]');

		// Zapis danych do cache
		$_system->cache('news_cats_count', $cache_count, 'news_cats');
	}

	// Zliczanie ilości newsów w danej kategorii
	foreach($cache_count as $val)
	{
		if ($_user->hasAccess($val['access']))
		{
			if ( ! isset($array[$val['category']]))
			{
				$array[$val['category']] = 1;
			}
			else
			{
				$array[$val['category']]++;
			}
		}
	}

	// Pobieranie z cache danych wszystkich kategorii
	$cache = $_system->cache('news_cats_list', NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));

	// Sprawdzanie, czy nie pobrano danych
	if ($cache === NULL)
	{
		// Pobieranie danych z bazy
		$cache = $_pdo->getData('SELECT `id`, `name`, `image` FROM [news_cats]');

		// Zapis danych do cache
		$_system->cache('news_cats_list', $cache, 'news_cats');
	}

	// Zmienna dla szablonu z danymi o kategoriach
	$d = array();
	foreach($cache as $data)
	{
		$d[] = array(
			'cat_id'        	=> $data['id'],
			'cat_title_name' 	=> $data['name'],
			'cat_image'    		=> $data['image'],
			'cat_count_news' 	=> isset($array[$data['id']]) ? $array[$data['id']] : 0,
			'url'		   		=> $_route->path(array('controller' => 'news_cats', 'action' => $data['id'], HELP::Title2Link($data['name'])))
		);
	}

	// Przesyłanie danych kategorii do szablonu
	$_tpl->assign('i', $d);
}