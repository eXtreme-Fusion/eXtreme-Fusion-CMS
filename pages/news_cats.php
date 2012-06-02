<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
//$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news_cats.css" media="screen" rel="stylesheet" />');
$_locale->load('news_cats');

$theme = array(
	'Title' => __('News categories'),
	'Keys' => '',
	'Desc' => ''
);

if ( ! $_theme->tplExists())
{
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/news_cats.css" media="screen" rel="stylesheet" />');
}
 
if ($_route->getByID(1) && isNum($_route->getByID(1), FALSE))
{
	$cache = $_system->cache('news_cats_'.$_route->getByID(1), NULL, 86700);
	if ($cache === NULL)
	{
		$_tpl->assign('preview', TRUE);
		
		$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news_cats] WHERE `id` = :id', 
			array(
				array(':id', $_route->getByID(1), PDO::PARAM_INT)
			)
		);
		
		if ($rows)
		{
			$_tpl->assign('rows', TRUE);

			$data = $_pdo->getRow('SELECT * FROM [news_cats] WHERE `id` = :id', 
				array(
					array(':id', $_route->getByID(1), PDO::PARAM_INT)
				)
			);

			$count = $_pdo->getSelectCount('SELECT count(*) FROM [news] WHERE `category` = :category AND `access` IN (:access)',
				array(
					array(':category', $data['id'], PDO::PARAM_INT),
					array(':access', $_user->listRoles(), PDO::PARAM_STR)
				)
			);
			
			$_tpl->assign('c', array(
				'cat_id'    => $data['id'],
				'cat_name'  => $data['name'],
				'cat_image' => $data['image'],
				'cat_news'  => $count
			));

			$query = $_pdo->getData('
				SELECT tn.`id`, tn.`title`, tn.`link`, tn.`content`, tn.`author`, tn.`reads`, tn.`datestamp`, tu.`id` AS `user_id`, tu.`username`
				FROM [news] tn
				LEFT JOIN [users] tu ON tn.`author`= tu.`id`
				WHERE `category` = :category AND tn.`access` IN (:access)
				ORDER BY tn.`datestamp` ASC, tn.`title`',
				array(
					array(':category', $data['id'], PDO::PARAM_INT),
					array(':access', $_user->listRoles(), PDO::PARAM_STR)
				)
			);
			
			$cache = array();
			if ($_pdo->getRowsCount($query))
			{
				foreach ($query as $row)
				{
					$cache[] = $row;
				}
			}
			$_system->cache('news_cats_'.$_route->getByID(1), $cache);
		}
		
		$i = 0; $n = array();
		if ( ! empty($cache))
		{
			foreach($cache as $data)
			{
				$n[] = array(
					'row_color'      => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'news_title_id'   => $data['id'],
					'news_title_name' => $data['title'],
					'news_content'   => substr($data['content'], 0, 85).'...',
					'news_author_id'   => $data['user_id'],
					'news_author_name' => $_user->getUsername($data['user_id']),
					'news_reads'     => $data['reads'],
					'news_datestamp' => HELP::showDate('shortdate', $data['datestamp']),
					'news_url'		=> $_route->path(array('controller' => 'news', 'action' => $data['id'], HELP::Title2Link($data['title']))),
					'profile_url'	=> $_route->path(array('controller' => 'profile', 'action' => $data['user_id'], HELP::Title2Link($data['username'])))
				);
				$i++;
			}
			$_tpl->assign('n', $n);
		}
	}
}
else
{
	$cache_count = $_system->cache('news_cats_count', NULL, 86700);
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
		$_system->cache('news_cats_count', $cache_count);
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
	
	$cache = $_system->cache('news_cats', NULL, 86700);
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
		$_system->cache('news_cats', $cache);
	}
	$i = 0;  $d = array();
	foreach($cache as $data)
	{
		$d[] = array(
			'row_color'     => $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'cat_id'        => $data['id'],
			'cat_title_name' => $data['name'],
			'cat_image'     => $data['image'],
			'cat_count_news' => isset($array[$data['id']]) ? $array[$data['id']] : 0,
			'url'		   => $_route->path(array('controller' => 'news_cats', 'action' => $data['id'], HELP::Title2Link($data['name'])))
		);
		$i++;
	}
	$_tpl->assign('i', $d);
}