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

if ( ! $_theme->tplExists())
{
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news_cats.css" media="screen" rel="stylesheet" />');
}
 
if ($_route->getAction())
{
	// Cache dla kategorii newsów opisu, miniaturek, id
	$category = $_system->cache('news_cats,cat-'.$_route->getAction().','.$_user->getCacheName(), NULL, 'news_cats', 60);
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

		$_system->cache('news_cats,cat-'.$_route->getAction().','.$_user->getCacheName(), $category, 'news_cats');
	}
	
	// Cache dla wystęujących newsów w danej kategorii
	$cache = $_system->cache('news,cat-'.$_route->getAction().','.$_user->getCacheName(), NULL, 'news_cats', 60);
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
				ORDER BY tn.`datestamp` ASC, tn.`title`',
				array(
					array(':category', $_route->getAction(), PDO::PARAM_INT)
				)
			);

			if ($_pdo->getRowsCount($query))
			{
				$i = 0;
				foreach($query as $data)
				{
					$cache[] = array(
						'row_color'         => $i % 2 == 0 ? 'tbl1' : 'tbl2',
						'news_title_id'     => $data['id'],
						'news_title_name'   => $data['title'],
						'news_content'      => substr($data['content'], 0, 85).'...',
						'news_author_id'    => $data['user_id'],
						'news_author_name'  => $_user->getUsername($data['user_id']),
						'news_reads'        => $data['reads'],
						'news_datestamp'    => HELP::showDate('shortdate', $data['datestamp']),
						'news_url'          => $_route->path(array('controller' => 'news', 'action' => $data['id'], HELP::Title2Link($data['title']))),
						'profile_url'       => $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username'])))
					);
					$i++;
				}
			}
			$_system->cache('news,cat-'.$_route->getAction().','.$_user->getCacheName(), $cache, 'news_cats');
		}
	}
	
	$_tpl->assign('rows', $cache);
	
	$_tpl->assign('category', $category);
}
else
{
	$cache_count = $_system->cache('news_cats_count', NULL, 'news_cats', 60);
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
	
	$cache = $_system->cache('news_cats_list', NULL, 'news_cats', 60);
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
			'row_color'     	=> $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'cat_id'        	=> $data['id'],
			'cat_title_name' 	=> $data['name'],
			'cat_image'    		=> $data['image'],
			'cat_count_news' 	=> isset($array[$data['id']]) ? $array[$data['id']] : 0,
			'url'		   	=> $_route->path(array('controller' => 'news_cats', 'action' => $data['id'], HELP::Title2Link($data['name'])))
		);
		$i++;
	}
	
	$_tpl->assign('i', $d);
}