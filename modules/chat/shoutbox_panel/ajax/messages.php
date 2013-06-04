<?php
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
require_once '../../../../system/sitecore.php';
$_locale->moduleLoad('shoutbox', 'chat');

$_ajax = new Ajax(DIR_MODULES.DS.'chat'.DS.'shoutbox_panel'.DS.'ajax'.DS);
$_sbb =  $ec->sbb;


	$rows = $_pdo->getMatchRowsCount('SELECT * FROM [chat_messages]');
	$chat_settings = $_pdo->getRow('SELECT * FROM [chat_settings]');


	$posts = array();
	if ($rows)
	{
		if ($chat_settings['panel_limit'] == 0)
		{
			$result = $_pdo->getData('SELECT * FROM [chat_messages] ORDER BY `id` DESC');
		}
		else
		{
			$result = $_pdo->getData('SELECT * FROM [chat_messages] ORDER BY `id` DESC LIMIT 0,'.intval($chat_settings['panel_limit']));
		}

		foreach ($result as $row)
		{
			$posts[] = array(
				'id' => $row['id'],
				'user' => HELP::profileLink(NULL, $row['user_id']),
				'content' => $_sbb->parseAllTags($row['content']),
				'date' => HELP::showDate('longdate', $row['datestamp'])
			);
		}
	}

$_ajax->assign('rows', $rows);
$_ajax->assign('posts', $posts);

$_ajax->assign('iAdmin', $_user->iAdmin());

$_ajax->template('shoutbox_messages.tpl');
