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

/*$theme = array(
	'Title' => $data['title'].' &raquo; '.$_sett->get('site_name'),
	'Keys' => $k,
	'Desc' => $data['description']
);*/

// TO DO Będziesz dodawać cache dla tej strony użyj: $_sett->getUns('cache', 'expire_pages') //


if ($_user->isLoggedIn())
{
	$_tpl->assign('user', $_user->get('username'));
}

// Przegląd typów treści
if ($_route->getAction() == NULL)
{
	$page = 'main';

	$data = array();
	foreach($_pdo->getData('SELECT * FROM [pages_types] ORDER BY `id` DESC') as $row)
	{
		if ($_user->hasAccess($row['insight_groups']))
		{
			$data[] = array(
				'name' => $row['name'],
				//'description' => $row['description'],
				'link' => $_route->path(array('action' => 'type', $row['id'], HELP::Title2Link($row['name'])))
			);
		}
	}

	$_tpl->assign('data', $data);
	
	$theme = array(
		'Title' => 'Materiały i wpisy &raquo; '.$_sett->get('site_name'),
		'Keys' => '',
		'Desc' => ''
	);
}
elseif ($_route->getAction() === 'type')
{
	if (isNum($_route->getParamVoid(1)))
	{
		$page = 'type';

		if ($type = $_pdo->getRow('SELECT `name`, `insight_groups` FROM [pages_types] WHERE id = '.$_route->getParamVoid(1)))
		{

			if ($_user->hasAccess($type['insight_groups']))
			{
				$data = array();

				if ($query = $_pdo->getData('SELECT `categories` FROM [pages] WHERE `type` = '.$_route->getParamVoid(1)))
				{
					$cats_id = array();
					foreach($query as $row)
					{
						foreach(HELP::explode($row['categories']) as $cat)
						{
							// Sprawdzanie, czy id nie jest już zapisany oraz nie jest równy 0
							if (! in_array($cat, $cats_id) && $cat)
							{
								$cats_id[] = $cat;
							}
						}
					}

					if ($cats_id)
					{
						$cats = HELP::implode($cats_id, ',');
						$query = $_pdo->getData('SELECT * FROM [pages_categories] WHERE `id` IN ('.$cats.') ORDER BY `id` DESC');
						$data = array();
						foreach($query as $row)
						{
							$data[] = array(
								'name' => $row['name'],
								'description' => $row['description'],
								'link' => $_route->path(array('action' => 'category', $_route->getParamVoid(1), $row['id'], HELP::Title2Link($row['name']))),
								'thumbnail' => $row['thumbnail']
							);
						}

						$_tpl->assign('data', $data);
					}

				}

				$_tpl->assign('type', $type['name']);
				
				$theme = array(
					'Title' => $type['name'].' &raquo; '.$_sett->get('site_name'),
					'Keys' => '',
					'Desc' => ''
				);
			}
			else
			{
				$_tpl->printMessage('error', 'Nie masz uprawnień do przeglądania tej podstrony');
			}
		}
		else
		{
			$_route->redirect(array('controller' => 'error', 'action' => 404));
		}
	}
}
elseif ($_route->getAction() === 'category')
{
	if (isNum($_route->getParamVoid(1)) && isNum($_route->getParamVoid(2)))
	{
		$page = 'category';

		if ($type = $_pdo->getRow('SELECT `id`, `name`, `insight_groups` FROM [pages_types] WHERE id = '.$_route->getParamVoid(1)))
		{
			if ($_user->hasAccess($type['insight_groups']))
			{
				$query = $_pdo->getData('SELECT * FROM [pages] WHERE `type` = '.$_route->getParamVoid(1).' ORDER BY `id` DESC');
				$data = array();
				foreach($query as $row)
				{
					if (in_array($_route->getParamVoid(2), HELP::explode($row['categories'])))
					{
						$data[] = array(
							'title' => $row['title'],
							'preview' => $row['preview'],
							'thumbnail' => $row['thumbnail'],
							'date' => HELP::showdate('longdate', $row['date']),
							'author' => HELP::profileLink(NULL, $row['author']),
							'link' => $_route->path(array('action' => $row['id'], HELP::Title2Link($row['title'])))
						);
					}
				}

				/** Nawigacja **/
				$category = $_pdo->getField('SELECT `name` FROM [pages_categories] WHERE id = '.$_route->getParamVoid(2));
				$_tpl->assign('type', array(
					'url' => $_route->path(array('action' => 'type', $type['id'], HELP::Title2Link($type['name']))),
					'name' => $type['name']
				));
				$_tpl->assign('category', $category);
				//--

				$_tpl->assign('data', $data);
				
				$theme = array(
					'Title' => $category.' &raquo; '.$_sett->get('site_name'),
					'Keys' => HELP::Title2Link($category),
					'Desc' => 'Materiały w kategorii '.$category
				);
			}
			else
			{
				$_tpl->printMessage('error', 'Nie masz uprawnień do przeglądania tej podstrony');
			}
		}
		else
		{
			$_route->redirect(array('controller' => 'error', 'action' => 404));
		}
	}
}
elseif ($_route->getAction() === 'categories')
{


	if ($_route->getParamVoid(1) && isNum($_route->getParamVoid(1)))
	{
		$page = 'categories';

		if ($category = $_pdo->getField('SELECT `name` FROM [pages_categories] WHERE id = '.$_route->getParamVoid(1)))
		{
			$types = $_pdo->getData('SELECT `id`, `insight_groups` FROM [pages_types]');
			$access_list = array();
			foreach($types as $type)
			{
				if ($_user->hasAccess($type['insight_groups']))
				{
					$access_list[] = $type['id'];
				}
			}

			if ($access_list)
			{
				$query = $_pdo->getData('SELECT * FROM [pages] WHERE `type` IN ('.implode(', ', $access_list).') ORDER BY `id` DESC');
				$data = array();
				foreach($query as $row)
				{
					if (in_array($_route->getParamVoid(1), HELP::explode($row['categories'])))
					{
						$data[] = array(
							'title' => $row['title'],
							'preview' => $row['preview'],
							'thumbnail' => $row['thumbnail'],
							'date' => HELP::showdate('longdate', $row['date']),
							'author' => HELP::profileLink(NULL, $row['author']),
							'link' => $_route->path(array('action' => $row['id'], HELP::Title2Link($row['title'])))
						);
					}
				}

				/** Nawigacja **/
				$_tpl->assign('category', $category);


				$_tpl->assign('data', $data);
				
				$theme = array(
					'Title' => $category.' &raquo; '.$_sett->get('site_name'),
					'Keys' => HELP::Title2Link($category),
					'Desc' => 'Materiały w kategorii '.$category
				);
			}
		}
		else
		{
			$_route->redirect(array('controller' => 'error', 'action' => 404));
		}
	}
	else
	{
		$page = 'categories_list';

		$query = $_pdo->getData('SELECT `id`, `name`, `description`, `thumbnail` FROM [pages_categories] ORDER BY `id` DESC');
		$data = array();
		foreach($query as $row)
		{
			$data[] = array(
				'name' => $row['name'],
				'description' => $row['description'],
				'thumbnail' => $row['thumbnail'],
				'link' => $_route->path(array('action' => 'categories', $row['id'], HELP::Title2Link($row['name'])))
			);
		}

		$_tpl->assign('data', $data);
		
		$theme = array(
			'Title' => 'Kategorie materiałów &raquo; '.$_sett->get('site_name'),
			'Keys' => '',
			'Desc' => 'Kategorie materiałów zamieszczanych na stronie'
		);
	}
}
// Przegląd konkretnego wpisu
elseif (isNum($_route->getAction(), FALSE))
{
	$page = 'entry';

	if ($row = $_pdo->getRow('SELECT * FROM [pages] WHERE `id` = '.$_route->getAction()))
	{

		$type = $_pdo->getRow('SELECT `id`, `name`, `insight_groups`, `user_allow_comments` FROM [pages_types] WHERE id = :id', array(':id', $row['type'], PDO::PARAM_INT));

		if ($_user->hasAccess($type['insight_groups']))
		{
			$_tpl->assign('data', array(
				'title' => $row['title'],
				'content' => $row['content'],
				'thumbnail' => $row['thumbnail'],
				'preview' => $row['preview'],
				'date' => HELP::showdate('longdate', $row['date']),
				'author' => HELP::profileLink(NULL, $row['author'])
			));



			! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);
			$keyword = array(); $k = array();
			if ($keys = $_tag->getTag('PAGES', $row['id'])){
				foreach($keys as $var){
					$keyword[] = array(
						'name' => $var['value'],
						'url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link']))
					);
					$k[] = $var['value'];
				}
			}


			$cats_q = $_pdo->getData('SELECT `id`, `name` FROM [pages_categories] WHERE `id` IN ('.implode(', ', HELP::explode($row['categories'])).')');

			$cats_data = array();
			foreach($cats_q as $cat)
			{
				$cats_data[] = array(
					'name' => $cat['name'],
					'url' => $_route->path(array('action' => 'categories', $cat['id'], HELP::Title2Link($cat['name'])))
				);
			}

			$theme = array(
				'Title' => $row['title'].' &raquo; '.$_sett->get('site_name'),
				'Keys' => implode(', ', $k),
				'Desc' => $row['description']
			);

			$_comment = $ec->comment;
			
			$_tpl->assignGroup(array(
				'comments' => $_comment->get($_route->getFileName(), $_route->getAction(), 0, 100),
				'entry' => $row['title'],
				'type' => array(
					'url' => $_route->path(array('action' => 'type', $type['id'], HELP::Title2Link($type['name']))),
					'name' => $type['name']
				),
				'keyword' => $keyword,
				'cats' => $cats_data,
				'user_allow_comments' => $type['user_allow_comments']
				//'category' => $category
			));

		}
		else
		{
			$_tpl->printMessage('error', 'Nie masz uprawnień do przeglądania tej podstrony');
		}
	}
	else
	{
		$_route->redirect(array('controller' => 'error', 'action' => 404));
	}
}
else
{
	$_route->redirect(array('controller' => 'error', 'action' => 404));
}

$_tpl->assign('page', $page);
