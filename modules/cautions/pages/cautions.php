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
*********************************************************/
$_locale->moduleLoad('lang', 'cautions');

$lookup = isNum($_route->getByID(1)) ? $_route->getByID(1) : FALSE;

if ($lookup)
{
	#*********** Settings 
		$theme = array(
			'Title' => __('Cautions user :username', array(':username' => $_user->getByID($lookup, 'username'))),
			'Keys' => '',
			'Desc' => ''
		);
		$_tpl->assign('Theme', $theme);

		// Blokuje wykonywanie pliku TPL z katalogu szablonu
		define('THIS', TRUE);

		// Definiowanie katalogu templatek modułu
		$_tpl->setPageCompileDir(DIR_MODULES.'cautions'.DS.'templates'.DS);
	#***********

	$query = $_pdo->getData('
		SELECT c.`id`, c.`content`, c.`date`, u.`id` AS `uid`, a.`id` AS `aid`, a.`username`
		FROM [cautions] c
		LEFT JOIN [users] a ON c.`admin_id` = a.`id`
		LEFT JOIN [users] u ON c.`user_id` = u.`id`
		WHERE c.`user_id` = :id
		ORDER BY `date` DESC',
		array(':id', $lookup, PDO::PARAM_INT)
	);
	
	$rows = $_pdo->getRowsCount($query);

    $i = 0; $data = array();
	foreach($query as $row)
	{
		$data[] = array(
			'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
			'id' => $row['id'],
			'user_id' => $row['uid'],
			'date' => HELP::showDate("shortdate", $row['date']),
			'donor' => $row['aid'] ? HELP::profileLink($row['username'], $row['aid']) : NULL,
			'content' => $row['content']
		);
		$i++;
	}
	
	$_tpl->assignGroup(
		array(
			'caution' => $data,
			'rows' => $rows,
			'permission' => $_user->hasPermission('module.cautions'),
			'profile' => HELP::profileLink($_user->getByID($lookup, 'username'), $_user->getByID($lookup, 'id'))
		)
	);
}
else
{
	$_request->redirect(ADDR_SITE.'members.html');
}