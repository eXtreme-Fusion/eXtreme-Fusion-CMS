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
$_locale->load('news_cats');

$_head->set($_tpl->getHeaders());
$_tpl->assign('all_news_url', $_route->path(array('controller' => 'news')));
$_tpl->assign('all_news_cats_url', $_route->path(array('controller' => 'news_cats')));

if ( ! $_theme->tplExists())
{
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news.css" media="screen" rel="stylesheet">');
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news_cats.css" media="screen" rel="stylesheet">');
}

if ($_route->getAction())
{

	// Sprawdzanie, czy użytkownik ma prawo do zobaczenia jakiegokolwiek newsa
	$rowa = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0', 
	//$rowa = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0 AND `language` = :lang', 
		array(':lang', $_user->getLang(), PDO::PARAM_STR)
	);

	if ($rowa)
	{
		# STRONICOWANIE #
		//$items_per_page = 2;
		$items_per_page = intval($_sett->get('news_cats_iteam_per_page'));

		if ( ! $_route->getByID(3))
		{
			$current = 1;
		}
		else
		{
			$current = $_route->getByID(3);
		}

		$_GET['rowstart'] = Paging::getRowStart($current, $items_per_page);
		
		// Cache dla kategorii newsów opisu, miniaturek, id
		$category = $_system->cache('news_cats,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current, NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));
		if ($category === NULL)
		{
			$rows = $_pdo->getRow('SELECT * FROM [news_cats] WHERE `id` = :id', 
				array(
					array(':id', $_route->getAction(), PDO::PARAM_INT)
				)
			);
			
			$category = array(
				'cat_id'    => $rows['id'],
				'cat_name'  => $rows['name'],
				'cat_image' => $rows['image'],
				'cat_news_count'  => $_pdo->getSelectCount('SELECT COUNT(`id`) FROM [news] WHERE `category` = :category AND `access` IN ('.$_user->listRoles().')',
					array(
						array(':category', $_route->getAction(), PDO::PARAM_INT)
					)
				)
			);

			$_system->cache('news_cats,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current, $category, 'news_cats');
		}
		
		// Cache dla wystęujących newsów w danej kategorii
		$cache = $_system->cache('news,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current, NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));
		if ($cache === NULL)
		{
			$row = $_pdo->getSelectCount('SELECT COUNT(`id`) FROM [news] WHERE `category` = :category  AND `access` IN ('.$_user->listRoles().')',
				array(
					array(':category', $_route->getAction(), PDO::PARAM_INT)
				)
			);
			
			if ($row)
			{
				$query = $_pdo->getData('
					SELECT tn.`id`, tn.`title`, tn.`access`, tn.`link`, tn.`content`, tn.`author`, tn.`reads`, tn.`datestamp`, tu.`id` AS `user_id`, tu.`username`
					FROM [news] tn
					LEFT JOIN [users] tu ON tn.`author`= tu.`id`
					WHERE tn.`category` = :category AND tn.`access` IN ('.$_user->listRoles().')
					ORDER BY tn.`datestamp` ASC, tn.`title` LIMIT :rowstart,:items_per_page',
					array(
						array(':category', $_route->getAction(), PDO::PARAM_INT),
						array(':rowstart', $_GET['rowstart'], PDO::PARAM_INT),
						array(':items_per_page', $items_per_page, PDO::PARAM_INT)
					)
				);

				if ($_pdo->getRowsCount($query))
				{
					$i = 0;
					foreach($query as $data)
					{
						// Przycinanie treści (nie wiem czy tu ma to być, czy gdzieś indziej - ~Lisu)
						  $count = str_word_count(stripslashes($data['content']));
						  $words = 20;
						  if ($count>$words) {
							$body = explode(" ", stripslashes($data['content']));
							$short_content = $body['0']." ";
							for ($n=1; $n < $words; $n++) {
								$short_content .= $body[$n]." ";
							}
							$short_content .= "&#8230;";
						  } else {
							$short_content = stripslashes($data['content']);
						  }
						// KONIEC
						
						$cache[] = array(
							'news_title_id'     => $data['id'],
							'news_title_name'   => $data['title'],
							'news_content'      => $short_content,
							'news_author_id'    => $data['user_id'],
							'news_author_name'  => $_user->getUsername($data['user_id']),
							'news_reads'        => $data['reads'],
							'news_datestamp'    => HELP::showDate('shortdate', $data['datestamp']),
							'news_url'          => $_route->path(array('controller' => 'news', 'action' => $data['id'], HELP::Title2Link($data['title']))),
							'profile_url'       => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username']))),
							'news_datetime' 	=> date('c', $data['datestamp'])
						);
						$i++;
					}
				}
				$_system->cache('news,cat-'.$_route->getAction().','.$_user->getCacheName().',page-'.$current, $cache, 'news_cats');
			}
		}
		$_tpl->assign('rows', $cache);
		
		$_tpl->assign('category', $category);
	
		if ($rowa)
		{
			// TO DO
			// Nie wiem czy tak stworzone parametry odpowiadają naszemu stylowi ~Rafik89
			// array($_route->getFileName(), $_route->getByID(1).$_sett->getUns('routing', 'main_sep').$_route->getByID(2), FALSE)
			$_pagenav = new PageNav(new Paging($rowa, $current, $items_per_page), $_tpl, 5, array($_route->getFileName(), $_route->getByID(1).$_sett->getUns('routing', 'main_sep').$_route->getByID(2), FALSE));

			if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'news_cats_nav.tpl'))
			{
				$_pagenav->get($_pagenav->create(), 'news_cats_nav', DIR_THEME.'templates'.DS.'paging'.DS);
			}
			else
			{
				$_pagenav->get($_pagenav->create(), 'news_cats_nav');
			}
		}
	}
}
else
{
	$cache_count = $_system->cache('news_cats_count', NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));
	if ($cache_count === NULL)
	{
		$count = $_pdo->getData('SELECT category, access FROM [news]');		
		
		$cache_count = array();
		if ($_pdo->getRowsCount($count))
		{
			//Cache dla zliczen
			foreach ($count as $row)
			{
				$cache_count[] = $row;
			}
		}
		$_system->cache('news_cats_count', $cache_count, 'news_cats');
	}
	
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
	
	$cache = $_system->cache('news_cats_list', NULL, 'news_cats', $_sett->getUns('cache', 'expire_news_cats'));
	if ($cache === NULL)
	{
		$query = $_pdo->getData('
			SELECT `id`, `name`, `image`
			FROM [news_cats]
		');
		
		$cache = array();
		if ($_pdo->getRowsCount($query))
		{
			foreach ($query as $row)
			{
				$cache[] = $row;
			}
		}
		$_system->cache('news_cats_list', $cache, 'news_cats');
	}
	
	$i = 0;  $d = array();
	foreach($cache as $data)
	{
		$d[] = array(
			'cat_id'        	=> $data['id'],
			'cat_title_name' 	=> $data['name'],
			'cat_image'    		=> $data['image'],
			'cat_count_news' 	=> isset($array[$data['id']]) ? $array[$data['id']] : 0,
			'url'		   		=> $_route->path(array('controller' => 'news_cats', 'action' => $data['id'], HELP::Title2Link($data['name'])))
		);
		$i++;
	}
	
	$_tpl->assign('i', $d);
}