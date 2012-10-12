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
require_once DIR_MODULES.'master_download'.DS.'system'.DS.'config.php';
$_locale->moduleLoad('lang', 'master_download');

$_head->set('<link href="'.ADDR_MDP.'templates/stylesheet/mdp.css" media="screen" rel="stylesheet" />');

include DIR_MODULES.'master_download'.DS.'config.php';
	
$_tpl->assign('config', $mod_info);

#*********** Settings 
	$theme = array(
		'Title' => __('Master Download'),
		'Keys' => '',
		'Desc' => ''
	);
	$_tpl->assign('Theme', $theme);

	// Blokuje wykonywanie pliku TPL z katalogu szablonu
	define('THIS', TRUE);

	// Definiowanie katalogu templatek modułu
	$_tpl->setPageCompileDir(DIR_MODULES.'master_download'.DS.'templates'.DS);
	
	$_tpl->assign('ADDR_MDP', ADDR_MDP);
#***********

if ($_route->getByID(1) === 'file' && isNum($_route->getByID(2)) && isNum($_route->getByID(3)))
{
	$_tpl->assign('md_file', TRUE);
	
	$cat = $_pdo->getRow('SELECT `viewaccess` FROM [master_download_subcats] WHERE `id` = '.$_route->getByID(2));
	if($_user->hasAccess($cat['viewaccess']))
	{
		$query = $_pdo->getData('
			SELECT `id`, `name`, `desc`, `subcat`, `img`, `url`, `size`, `date`, `count`, `mirror`
			FROM [master_download_files]
			WHERE `id` = '.$_route->getByID(3).'
			ORDER BY `id`
		');

		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $data = array();
			foreach($query as $row)
			{
				$cat = $_pdo->getRow('SELECT `getaccess` FROM [master_download_subcats] WHERE `id` = '.$_route->getByID(2));
				$_tpl->assign('access_file', $_user->hasAccess($cat['getaccess']));
			
				if($_route->getByID(4) == 'download' && $_user->hasAccess($cat['getaccess']))
				{
					$count = $_pdo->exec('UPDATE [master_download_files] SET `count` = `count`+1 WHERE `id` = '.$_route->getByID(3));
					$_request->redirect($row['url']);
				}
				
				$mirror = unserialize($row['mirror']);
				for($i=0;$i<count($mirror);$i++)
				{
					$mirrors = $mirror;
				}
				$_tpl->assign('mirror', $mirrors);

				if ($row['size'] >= 1048576)
				{
					$size = rount($row['size']/1048576, 2);
					$size = $size.' GB';
				}
				elseif ($row['size'] >= 1024 && $row['size'] < 1048576)
				{
					$size = round($row['size']/1024, 2);
					$size = $size.' MB';
				}
				elseif ($row['size'] < 1024)
				{
					$size = $row['size'].' kB';
				}
				
				$data = array(
					'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'row_color2' => $i % 2 == 0 ? 'tbl2' : 'tbl1',
					'id' => $row['id'],
					'name' => $row['name'],
					'desc' => $row['desc'],
					'subcat' => $row['subcat'],
					'img' => $row['img'],
					'size' => $size,
					'date' => HELP::showDate('shortdate', $row['date']),
					'count' => $row['count'],
					'url' => $_route->path(array('controller' => 'master_download', 'action' => 'file', $row['subcat'], $row['id'], 'download')),
					'subcat_url' => $_route->path(array('controller' => 'master_download', 'action' => 'cat', $row['subcat']))
				);
				$i++;
			}
			$_tpl->assign('file', $data);
		}
	}
	else
	{
		$_request->redirect(ADDR_SITE.'master_download.html');
	}
}
elseif ($_route->getByID(1) === 'cat' && isNum($_route->getByID(2)))
{
	$_tpl->assign('md_subcat', TRUE);
	
	$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [master_download_files] WHERE `subcat` = '.$_route->getByID(2));
	
	# STRONICOWANIE #
	$items_per_page = 15;
	if ( ! $_route->getByID(4))
	{
		$_GET['current'] = 1;
	}
	else
	{
		$_GET['current'] = $_route->getByID(4);
	}

	$_GET['rowstart'] = PAGING::getRowStart($_GET['current'], $items_per_page);

	# / STRONICOWANIE #
	
	$query = $_pdo->getData('
		SELECT `name`, `desc`, `id`, `viewaccess`
		FROM [master_download_subcats]
		WHERE `id` = '.$_route->getByID(2).'
		ORDER BY `id`
	');
	
	if ($_pdo->getRowsCount($query))
	{
		$i = 0; $data = array();
		foreach($query as $row)
        {
			$cat = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'desc' => $row['desc'],
			);
			$i++;
			
			$_tpl->assign('access', $_user->hasAccess($row['viewaccess']));
		}
		$_tpl->assign('cat', $cat);
	}
	
	// Files
	$query = $_pdo->getData('
		SELECT `id`, `name`, `desc`, `subcat`, `img`, `size`, `date`, `count`
		FROM [master_download_files]
		WHERE `subcat` = '.$_route->getByID(2).'
		ORDER BY `name`
		LIMIT :rowstart,:items_per_page',
		array(
			array(':rowstart', $_GET['rowstart'], PDO::PARAM_INT),
			array(':items_per_page', $items_per_page, PDO::PARAM_INT),
		)
	);
	
	if ($_pdo->getRowsCount($query))
	{
		$i = 0; $data = array();
		foreach($query as $row)
        {
			if ($row['size'] >= 1048576)
			{
				$size = rount($row['size']/1048576, 2);
				$size = $size.' GB';
			}
			elseif ($row['size'] >= 1024 && $row['size'] < 1048576)
			{
				$size = round($row['size']/1024, 2);
				$size = $size.' MB';
			}
			elseif ($row['size'] < 1024)
			{
				$size = $row['size'].' kB';
			}
			
			$data[] = array(
				'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
				'row_color2' => $i % 2 == 0 ? 'tbl2' : 'tbl1',
				'id' => $row['id'],
				'name' => $row['name'],
				'desc' => $row['desc'],
				'subcat' => $row['subcat'],
				'img' => $row['img'],
				'size' => $size,
				'date' => HELP::showDate('shortdate', $row['date']),
				'count' => $row['count'],
				'url' => $_route->path(array('controller' => 'master_download', 'action' => 'file', $row['subcat'], $row['id']))
			);
			$i++;
		}
		$_tpl->assign('file', $data);
	}
	
	$_pagenav = new PageNav(new Paging($rows, $_GET['current'], $items_per_page), $_tpl, 5, array($_route->getByID(2), 'page'));

	$_pagenav->get($_pagenav->create(), 'page_nav');
}
else
{
	$_tpl->assign('md_download', TRUE);
	
	// Pobieranie kategorii
	$query = $_pdo->getData('SELECT * FROM [master_download_cats] ORDER BY `id` ASC');
	$cats = array();
	
	// Przepisywanie pobranych danych na zwykłą tablicę
	foreach($query as $data)
	{
		$cats[] = $data;
	}

	// Pobieranie pól
	$query = $_pdo->getData('SELECT * FROM [master_download_subcats]');
	// Przepisywanie pobranych pól na zwykłą tablicę
	foreach($query as $data)
	{
		if ($_user->hasAccess($data['viewaccess']))
		{
			$subcategories[] = $data;
		}
	}
	$i = 0;

	# Segregacja danych
	if (isset($subcategories))
	{
		$subcats = array();

		foreach($cats as $key => $cat)
		{
			foreach($subcategories as $subcategory)
			{
				if ($subcategory['cat'] === $cat['id'])
				{
					$subcats[$key][$i] = array(
						'name' => $subcategory['name'],
						'desc' => $subcategory['desc'],
						'url' => $_route->path(array('controller' => 'master_download', 'action' => 'cat', $subcategory['id']))
					);
					
					$i++;
				}
			}
		}

		$_tpl->assign('subcats', $subcats);
	}

	$_tpl->assign('cats', $cats);
}