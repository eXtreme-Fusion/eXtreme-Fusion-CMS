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
$_locale->moduleLoad('lang', 'gallery');

! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

include DIR_MODULES.'gallery'.DS.'config.php';

$_tpl->assign('config', $mod_info);


if($_route->getByID(1) === 'cat')
{
	if ( ! $_route->getByID(2))
	{
		throw new systemException('Error! There is no specified directory paramter.');
	} 
	else
	{
		$data = new Edit(
			array(
				'current' => $_route->getByID(5) ? $_route->getByID(5) : 1
			)
		);

		$rows = $_pdo->getMatchRowsCount('
			SELECT a.`id`
			FROM [gallery_albums] a
			LEFT JOIN [gallery_cats] c
			ON (a.`cat` = c.`id`)
			WHERE a.`access` IN ('.$_user->listRoles().') AND c.`access` IN ('.$_user->listRoles().') AND a.`cat`= :id
			ORDER BY a.`order`',
			array(':id', $_route->getByID(2), PDO::PARAM_INT)
		);

		if ($rows)
		{
			$rowstart = $data->arr('current')->isNum(TRUE, FALSE) ? Paging::getRowStart($data->arr('current')->isNum(TRUE, FALSE), intval($_gallery_sett->get('albums_per_page'))) : 0;

			$cache = $_system->cache('gallery,cat-id-'.$_route->getByID(2).','.$_user->getCacheName().',page-'.$data->arr('current')->isNum(TRUE, FALSE), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
			if ($cache === NULL)
			{
				$query = $_pdo->getData('
					SELECT a.`id`, a.`title`, a.`description`, a.`file_name`, a.`datestamp`, a.`access`
					FROM [gallery_albums] a
					LEFT JOIN [gallery_cats] c
					ON (a.`cat` = c.`id`)
					WHERE a.`access` IN ('.$_user->listRoles().') AND c.`access` IN ('.$_user->listRoles().') AND a.`cat`= :id
					ORDER BY a.`order`
					LIMIT :rowstart,:albums_per_page',
					array(
						array(':id', $_route->getByID(2), PDO::PARAM_INT),
						array(':albums_per_page', intval($_gallery_sett->get('albums_per_page')), PDO::PARAM_INT),
						array(':rowstart', $rowstart, PDO::PARAM_INT)
					)
				);

				if ($res = $_pdo->getRowsCount($query))
				{
					$i = 0;
					foreach ($query as $row)
					{
						$row['photos'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_photos] WHERE `album` = :id AND `access` IN ('.$_user->listRoles().')',
							array(
								array(':id', $row['id'], PDO::PARAM_INT)
							)
						);
						
						$row['comments'] = $_pdo->getMatchRowsCount("SELECT `id` FROM [comments] WHERE content_type = :content_type AND content_id = :content_id", 
							array(
								array(':content_id', $row['id'], PDO::PARAM_INT),
								array(':content_type', $_route->getFileName(), PDO::PARAM_STR)
							)
						);
						
						$cache[] = array(
							'color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
							'id' => $row['id'],
							'title' => $row['title'],
							'description' => $row['description'],
							'file_name' => $row['file_name'],
							'datestamp' => $row['datestamp'],
							'photos' => $row['photos'],
							'album_link' => $_route->path(array('controller' => 'gallery', 'action' => 'album', $row['id'], $row['title'])),
							'comments' => $row['comments'],
							'access' => $row['access'],
							'role_name' => $_user->getRoleName($row['access'])
						);
						$i++;
					}
				}
				$_system->cache('gallery,cat-id-'.$_route->getByID(2).','.$_user->getCacheName(), $cache, 'gallery');
			}

			$_pagenav = new PageNav(new Paging($rows, $data->arr('current')->isNum(TRUE, FALSE), intval($_gallery_sett->get('albums_per_page'))), $_tpl, 5, array($_route->getFileName(), 'cat,'.$_route->getByID(2).','.$_route->getByID(3).',page'));
			
			$_tpl->assign('album', $cache);
		}
	}
	
	$seo_var = $_system->cache('gallery,seo-var-cat-id-'.$_route->getByID(2).','.$_user->getCacheName().',page-'.$data->arr('current')->isNum(TRUE, FALSE), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
	if ($seo_var === NULL)
	{
		$row = $_pdo->getRow('
			SELECT `title` as cat, `description`, `id` as cat_id
			FROM [gallery_cats]
			WHERE id = :id',
			array(':id', $_route->getByID(2), PDO::PARAM_INT)
		);
		
		if ($row)
		{
			$keyword = array();
			if ($keys = $_tag->getTag('GALLERY_CATS', $_route->getByID(2))){
				foreach($keys as $var){
					$keyword[] = $var['value'];
				}
			}
			$keyword = implode(', ', $keyword);
			
			$seo_var = array(
				'Theme' => array(
					'Title' => $_gallery_sett->get('title').' &raquo; '.$row['cat'],
					'Keys' => $keyword,
					'Desc' => $row['description']
				),
				'Breadcrumb' => array(
					'index' => array(
						'id' => 'gallery'.$_sett->getUns('routing', 'url_ext'),
						'title' => $_gallery_sett->get('title')
					),
					'cat' => array(
						'id' => $row['cat_id'],
						'link' => $_route->path(array('controller' => 'gallery', 'cat' => $row['cat_id'], $row['cat'])),
						'title' => $row['cat']
					),
					'album' => FALSE,
					'photo' => FALSE
				)
			);
		}
		
		$_system->cache('gallery,seo-var-cat-id-'.$_route->getByID(2).','.$_user->getCacheName(), $seo_var, 'gallery');
	}
}
elseif($_route->getByID(1) === 'album')
{
	if ( ! $_route->getByID(2))
	{
		throw new systemException('Error! There is no specified album parameter.');
	}
	else
	{
		$data = new Edit(
			array(
				'current' => $_route->getByID(5) ? $_route->getByID(5) : 1
			)
		);

		$rows = $_pdo->getMatchRowsCount('
			SELECT p.`id`
			FROM [gallery_photos] p
			LEFT JOIN [gallery_albums] a
			ON (p.`album` = a.`id`)
			LEFT JOIN [gallery_cats] c
			ON (a.`cat` = c.`id`)
			WHERE p.`access` IN ('.$_user->listRoles().') AND a.`access` IN ('.$_user->listRoles().') AND c.`access` IN ('.$_user->listRoles().') AND p.`album`= :id',
			array(':id', $_route->getByID(2), PDO::PARAM_INT)
		);

		if ($rows)
		{
			$rowstart = $data->arr('current')->isNum(TRUE, FALSE) ? Paging::getRowStart($data->arr('current')->isNum(TRUE, FALSE), intval($_gallery_sett->get('albums_per_page'))) : 0;

			$cache = $_system->cache('gallery,album-id-'.$_route->getByID(2).','.$_user->getCacheName().',page-'.$data->arr('current')->isNum(TRUE, FALSE), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
			if ($cache === NULL)
			{
				$query = $_pdo->getData('
					SELECT p.`id`, p.`title`, p.`user`, p.`description`, p.`path_url`, p.`file_name`, p.`watermark`, p.`datestamp`, p.`access`, a.`id` AS album_id
					FROM [gallery_photos] p
					LEFT JOIN [gallery_albums] a
					ON (p.`album` = a.`id`)
					LEFT JOIN [gallery_cats] c
					ON (a.`cat` = c.`id`)
					WHERE p.`access` IN ('.$_user->listRoles().') AND a.`access` IN ('.$_user->listRoles().') AND c.`access` IN ('.$_user->listRoles().') AND p.`album`= :id
					ORDER BY p.`order`
					LIMIT :rowstart,:photos_per_page',
					array(
						array(':id', $_route->getByID(2), PDO::PARAM_INT),
						array(':photos_per_page', intval($_gallery_sett->get('photos_per_page')), PDO::PARAM_INT),
						array(':rowstart', $rowstart, PDO::PARAM_INT)
					)
				);

				if ($res = $_pdo->getRowsCount($query))
				{
					$i = 0;
					foreach ($query as $row)
					{
						
						$row['comments'] = $_pdo->getMatchRowsCount("SELECT `id` FROM [comments] WHERE content_type = :content_type AND content_id = :content_id", 
							array(
								array(':content_id', $row['id'], PDO::PARAM_INT),
								array(':content_type', $_route->getFileName(), PDO::PARAM_STR)
							)
						);
						
						$cache[] = array(
							'color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
							'id' => $row['id'],
							'album_id' => $row['album_id'],
							'title' => $row['title'],
							'description' => $row['description'],
							'path_url' => $row['path_url'],
							'photo_link' => $_route->path(array('controller' => 'gallery', 'photo' => $row['id'], $row['title'])),
							'watermark' => $row['watermark'],
							'file_name' => $row['file_name'],
							'datestamp' => $row['datestamp'],
							'access' => $row['access'],
							'comments' => $row['comments'],
							'user' => $_user->getUsername($row['user']),
							'role_name' => $_user->getRoleName($row['access'])
						);
						$i++;
					}
				}
				$_system->cache('gallery,album-id-'.$_route->getByID(2).','.$_user->getCacheName().',page-'.$data->arr('current')->isNum(TRUE, FALSE), $cache, 'gallery');
			}
			
			$_pagenav = new PageNav(new Paging($rows, $data->arr('current')->isNum(TRUE, FALSE), intval($_gallery_sett->get('photos_per_page'))), $_tpl, 5, array($_route->getFileName(), 'album,'.$_route->getByID(2).','.$_route->getByID(3).',page'));
		
			$_tpl->assign('photo', $cache);
		}
	}
	
	$seo_var = $_system->cache('gallery,seo-var-album-id-'.$_route->getByID(2).','.$_user->getCacheName(), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
	if ($seo_var === NULL)
	{			
		$row = $_pdo->getRow('
			SELECT a.`title` AS album, a.`description`, c.`title` AS cat, a.`id` AS album_id, c.`id` AS cat_id
			FROM [gallery_albums] a
			LEFT JOIN [gallery_cats] c
			ON (a.`cat` = c.`id`)
			WHERE a.`id` = :id',
			array(':id', $_route->getByID(2), PDO::PARAM_INT)
		);
		
		if ($row)
		{		
			$keyword = array();
			if ($keys = $_tag->getTag('GALLERY_ALBUMS', $_route->getByID(2))){
				foreach($keys as $var){
					$keyword[] = $var['value'];
				}
			}
			$keyword = implode(', ', $keyword);
			
			$seo_var = array(
				'Theme' => array(
					'Title' => $_gallery_sett->get('title').' &raquo; '.$row['cat'].' &raquo; '.$row['album'],
					'Keys' => $keyword,
					'Desc' => $row['description']
				),
				'Breadcrumb' => array(
					'index' => array(
						'id' => 'gallery'.$_sett->getUns('routing', 'url_ext'),
						'title' => $_gallery_sett->get('title')
					),
					'cat' => array(
						'id' => $row['cat_id'],
						'link' => $_route->path(array('controller' => 'gallery', 'cat' => $row['cat_id'], $row['cat'])),
						'title' => $row['cat']
					),
					'album' => array(
						'id' => $row['album_id'],
						'link' => $_route->path(array('controller' => 'gallery', 'album' => $row['album_id'], $row['album'])),
						'title' => $row['album']
					),
					'photo' => FALSE
				)
			);
		}
		
		$_system->cache('gallery,seo-var-album-id-'.$_route->getByID(2).','.$_user->getCacheName(), $seo_var, 'gallery');
	}
}
elseif($_route->getByID(1) === 'photo')
{
	if ( ! $_route->getByID(2))
	{
		throw new systemException('Error! There is no specified image parameter.');
	}
	else
	{
		$cache = $_system->cache('gallery,photo-id-'.$_route->getByID(2).','.$_user->getCacheName(), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
		if ($cache === NULL)
		{
			$row = $_pdo->getRow('
				SELECT p.`id`, p.`title`, p.`user`, p.`comment`, p.`description`, p.`path_url`, p.`file_name`, p.`watermark`, p.`datestamp`, p.`access`
				FROM [gallery_photos] p
				LEFT JOIN [gallery_albums] a
				ON (p.`album` = a.`id`)
				LEFT JOIN [gallery_cats] c
				ON (a.`cat` = c.`id`)
				WHERE p.`access` IN ('.$_user->listRoles().') AND a.`access` IN ('.$_user->listRoles().') AND c.`access` IN ('.$_user->listRoles().') AND p.`id`= :id
				ORDER BY p.`order`',
				array(':id', $_route->getByID(2), PDO::PARAM_INT)
			);

			if ($row)
			{
				$cache = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => $row['description'],
					'path_url' => $row['path_url'],
					'file_name' => $row['file_name'],
					'watermark' => $row['watermark'],
					'datestamp' => $row['datestamp'],
					'access' => $row['access'],
					'comment' => $row['comment'],
					'user' => $_user->getUsername($row['user']),
					'role_name' => $_user->getRoleName($row['access'])
				);
			}
			$_system->cache('gallery,photo-id-'.$_route->getByID(2).','.$_user->getCacheName(), $cache, 'gallery');
		}
		
		if ($_gallery_sett->get('allow_comment') === 'true')
		{
			if ($cache['comment'] === '1')
			{	
				$_comment = $ec->comment;
				
				$_tpl->assign('comment', $_comment->get($_route->getFileName(), $cache['id']));

				if (isset($_POST['comment']['save']))
				{
					$comment = array_merge($comment, array(
						'action'  => 'add',
						'author'  => $_user->get('id'),
						'content' => $_POST['comment']['content']
					));
				}
			}
		}
		
		$_tpl->assign('photo', $cache);
	}
	
	$seo_var = $_system->cache('gallery,seo-var-photo-id-'.$_route->getByID(2).','.$_user->getCacheName(), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
	if ($seo_var === NULL)
	{			
		$row = $_pdo->getRow('
			SELECT p.`title` AS photo, p.`description`, a.`title` AS album, c.`title` AS cat, p.`id` AS photo_id, a.`id` AS album_id, c.`id` AS cat_id
			FROM [gallery_photos] p
			LEFT JOIN [gallery_albums] a
			ON (p.`album` = a.`id`)
			LEFT JOIN [gallery_cats] c
			ON (a.`cat` = c.`id`)
			WHERE p.`id` = :id',
			array(':id', $_route->getByID(2), PDO::PARAM_INT)
		);
		
		if ($row)
		{		
			$keyword = array();
			if ($keys = $_tag->getTag('GALLERY_PHOTOS', $_route->getByID(2))){
				foreach($keys as $var){
					$keyword[] = $var['value'];
				}
			}
			$keyword = implode(', ', $keyword);
			
			$seo_var = array(
				'Theme' => array(
					'Title' => $_gallery_sett->get('title').' &raquo; '.$row['cat'].' &raquo; '.$row['album'].' &raquo; '.$row['photo'],
					'Keys' => $keyword,
					'Desc' => $row['description']
				),
				'Breadcrumb' => array(
					'index' => array(
						'id' => 'gallery'.$_sett->getUns('routing', 'url_ext'),
						'title' => $_gallery_sett->get('title')
					),
					'cat' => array(
						'id' => $row['cat_id'],
						'link' => $_route->path(array('controller' => 'gallery', 'cat' => $row['cat_id'], $row['cat'])),
						'title' => $row['cat']
					),
					'album' => array(
						'id' => $row['album_id'],
						'link' => $_route->path(array('controller' => 'gallery', 'album' => $row['album_id'], $row['album'])),
						'title' => $row['album']
					),
					'photo' => array(
						'id' => $row['photo_id'],
						'link' => $_route->path(array('controller' => 'gallery', 'photo' => $row['photo_id'], $row['photo'])),
						'title' => $row['photo']
					)
				)
			);
		}
		
		$_system->cache('gallery,seo-var-photo-id-'.$_route->getByID(2).','.$_user->getCacheName(), $seo_var, 'gallery');
	}
}
else
{
	$data = new Edit(
		array(
			'current' => $_route->getByID(2) ? $_route->getByID(2) : 1
		)
	);

	$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_cats]');

	if ($rows)
	{
		$rowstart = $data->arr('current')->isNum(TRUE, FALSE) ? Paging::getRowStart($data->arr('current')->isNum(TRUE, FALSE), intval($_gallery_sett->get('cats_per_page'))) : 0;

		$cache = $_system->cache('gallery,cats,'.$_user->getCacheName().',page-'.$data->arr('current')->isNum(TRUE, FALSE), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
		if ($cache === NULL)
		{
			$query = $_pdo->getData('
					SELECT `id`, `title`, `description`, `file_name`, `datestamp`, `access`
					FROM [gallery_cats]
					WHERE `access` IN ('.$_user->listRoles().')
					ORDER BY `order`
					LIMIT :rowstart,:cats_per_page',
					array(
						array(':cats_per_page', intval($_gallery_sett->get('cats_per_page')), PDO::PARAM_INT),
						array(':rowstart', $rowstart, PDO::PARAM_INT)
					)
				);

			if ($res = $_pdo->getRowsCount($query))
			{
				$i = 0;
				foreach ($query as $row)
				{
					$row['albums'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_albums] WHERE `cat` = :id AND `access` IN ('.$_user->listRoles().')',
						array(
							array(':id', $row['id'], PDO::PARAM_INT)
						)
					);
					
					$row['photos'] = 0;	$row['comments'] = 0;
					$album_id = $_pdo->getRow('
						SELECT a.`id`
						FROM [gallery_albums] a
						LEFT JOIN [gallery_cats] c
						ON (a.`cat` = c.`id`)
						WHERE a.`access` IN ('.$_user->listRoles().') AND c.`access` IN ('.$_user->listRoles().') AND a.`cat`= :id',
						array(':id', $row['id'], PDO::PARAM_INT)
					);
					
					if ($album_id)
					{
						$row['photos'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_photos] WHERE `album` = :id AND `access` IN ('.$_user->listRoles().')',
							array(
								array(':id', intval($album_id), PDO::PARAM_INT)
							)
						);
						
						$row['comments'] = $_pdo->getMatchRowsCount("SELECT `id` FROM [comments] WHERE content_type = :content_type AND content_id = :content_id", 
							array(
								array(':content_id', intval($album_id), PDO::PARAM_INT),
								array(':content_type', $_route->getFileName(), PDO::PARAM_STR)
							)
						);
					}
					
					$cache[] = array(
						'color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
						'id' => $row['id'],
						'title' => $row['title'],
						'description' => $row['description'],
						'file_name' => $row['file_name'],
						'datestamp' => $row['datestamp'],
						'cat_link' => $_route->path(array('controller' => 'gallery', 'action' => 'cat', $row['id'], $row['title'])),
						'access' => $row['access'],
						'role_name' => $_user->getRoleName($row['access']),
						'albums' => $row['albums'],
						'photos' => $row['photos'],
						'comments' => $row['comments']
					);
					$i++;
				}
			}
			$_system->cache('gallery,cats,'.$_user->getCacheName().',page-'.$data->arr('current')->isNum(TRUE, FALSE), $cache, 'gallery');
		}
			
		$_pagenav = new PageNav(new Paging($rows, $data->arr('current')->isNum(TRUE, FALSE), intval($_gallery_sett->get('cats_per_page'))), $_tpl, 5, array($_route->getFileName(), 'page'));
		
		$_tpl->assign('cat', $cache);
	}
	$seo_var = $_system->cache('gallery,seo-var-cats,'.$_user->getCacheName(), NULL, 'gallery', $_gallery_sett->get('cache_expire'));
	if ($seo_var === NULL)
	{			
		$keyword = array();
		if ($keys = $_tag->getTag('GALLERY_GLOBAL', 1)){
			foreach($keys as $var){
				$keyword[] = $var['value'];
			}
		}
		$keyword = implode(', ', $keyword);
		
		$seo_var = array(
			'Theme' => array(
				'Title' => $_gallery_sett->get('title'),
				'Keys' => $keyword,
				'Desc' => $_gallery_sett->get('description')
			),
			'Breadcrumb' => array(
				'index' => array(
					'id' => 'gallery'.$_sett->getUns('routing', 'url_ext'),
					'title' => $_gallery_sett->get('title')
				),
				'cat' => FALSE,
				'album' => FALSE,
				'photo' => FALSE
			)
		);
		
		$_system->cache('gallery,seo-var-cats,'.$_user->getCacheName(), $seo_var, 'gallery');
	}
}

if (isset($_pagenav))
{
	if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'gallery_page_nav.tpl'))
	{
		$_pagenav->get($_pagenav->create(), 'gallery_page_nav', DIR_THEME.'templates'.DS.'paging'.DS);
	}
	else
	{
		$_pagenav->get($_pagenav->create(), 'gallery_page_nav', DIR_MODULES.'gallery'.DS.'templates'.DS.'paging'.DS);
	}
}

if (isset($seo_var))
{
	$theme = $seo_var['Theme'];
}

$_tpl->assignGroup(array(
	'page' => $_route->getByID(1),
	'Breadcrumb' => isset($seo_var['Breadcrumb']) ? $seo_var['Breadcrumb'] : array(),
	'ADDR_GALLERY_IMAGES' => ADDR_MODULES.'gallery/templates/images/'
	)
);


$_tpl->setPageCompileDir(DIR_MODULES.'gallery'.DS.'templates'.DS);