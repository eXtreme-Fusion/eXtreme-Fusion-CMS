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
$_locale->load('users');

define('THIS', FALSE);

$theme = array(
	'Title' => __('Lista użytkowników').' &raquo; '.$_sett->get('site_name'),
	'Keys' => 'Użytkownicy '.$_sett->get('site_name').', konta '.$_sett->get('site_name').', profile',
	'Desc' => 'Lista aktywnych kont na stronie '.$_sett->get('site_name')
);

$data = new Edit(
	array(
		'current' => $_route->getByID(1) ? $_route->getByID(1) : 1,
		'sort' => is_string($_route->getByID(2)) ? $_route->getByID(2) : 'all'
	)
);

$rows = $_pdo->getMatchRowsCount('SELECT * FROM [users] WHERE `status` = 0 '.($data->arr('sort')->filters('trim', 'strip') !== 'all' ? 'AND `username` LIKE "'.$data->arr('sort')->filters('trim', 'strip').'%"' : '').'');

if ($rows)
{
	$rowstart = $data->arr('current')->isNum(TRUE, FALSE) ? Paging::getRowStart($data->arr('current')->isNum(TRUE, FALSE), intval($_sett->get('users_per_page'))) : 0;

	$cache = $_system->cache('users,'.$_user->getCacheName().',sort_by-'.$data->arr('sort')->filters('trim', 'strip').',page-'.$data->arr('current')->isNum(TRUE, FALSE), NULL, 'users', $_sett->getUns('cache', 'expire_pages'));
	if ($cache === NULL)
	{
		$query = $_pdo->getData('
			SELECT `id`, `username`, `role`, `status`, `lastvisit`
			FROM [users] WHERE `status` = 0 '.($data->arr('sort')->filters('trim', 'strip') !== 'all' ? 'AND `username` LIKE "'.$data->arr('sort')->filters('trim', 'strip').'%"' : '').'
			ORDER BY `role` ASC, `username`
			LIMIT :rowstart,:users_per_page',
			array(
				array(':users_per_page', intval($_sett->get('users_per_page')), PDO::PARAM_INT),
				array(':rowstart', $rowstart, PDO::PARAM_INT)
			)
		);
	
		if ($_pdo->getRowsCount($query))
		{
			$i = 0;
			foreach($query as $row)
			{
				$cache[] = array(
					'row' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'id' => $row['id'],
					'visit' => HELP::showDate('shortdate', $row['lastvisit']),
					'link' => HELP::profileLink($row['username'], $row['id']),
					'role' => $_user->getRoleName($row['role']),
					'roles' => implode(', ', $_user->getUserRolesTitle($row['id'], 3))
				);
				$i++;
			}
		}
		$_system->cache('users,'.$_user->getCacheName().',sort_by-'.$data->arr('sort')->filters('trim', 'strip').',page-'.$data->arr('current')->isNum(TRUE, FALSE), $cache, 'users');
	}

	$_pagenav = new PageNav(new Paging($rows, $data->arr('current')->isNum(TRUE, FALSE), intval($_sett->get('users_per_page'))), $_tpl, 10, array($_route->getFileName(), $data->arr('sort')->filters('trim', 'strip'), FALSE));

	if (file_exists(DIR_THEME.'templates'.DS.'paging'.DS.'users_page_nav.tpl'))
	{
		$_pagenav->get($_pagenav->create(), 'users_page_nav', DIR_THEME.'templates'.DS.'paging'.DS);
	}
	else
	{
		$_pagenav->get($_pagenav->create(), 'page_nav');
	}

	$_tpl->assign('users', $cache);
}

$sort = array(
	'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R',
	'S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9'
);

for ($i = 0, $c = count($sort); $i < $c; $i++)
{
	$sort_sel[$i] = array(
		'disp' => $sort[$i],
		'val' => strtolower($sort[$i]),
		'link' => $_route->path(array('controller' => 'users', 'action' => $data->arr('current')->isNum(TRUE, FALSE), strtolower($sort[$i])))
	);

	if ($data->arr('sort')->filters('trim', 'strip') === $sort_sel[$i]['val'])
	{
		$sort_sel[$i]['sel'] = TRUE;
	}
}

$_tpl->assign('sort', $sort_sel);

if ($data->arr('sort')->filters('trim', 'strip') !== 'all')
{
	$_tpl->assignGroup(array(
		'show_all' => TRUE,
		'link' => $_route->path(array('controller' => 'users', 'action' => $data->arr('current')->isNum(TRUE, FALSE)))
	));
}
