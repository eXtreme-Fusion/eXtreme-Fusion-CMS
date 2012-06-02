<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
require_once '../../../../system/sitecore.php';

$_ajax = new Ajax(DIR_MODULES.DS.'chat'.DS.'shoutbox_panel'.DS.'ajax'.DS);


	$rows = $_pdo->getMatchRowsCount('SELECT * FROM [chat_messages]');
	$chat_settings = $_pdo->getRow('SELECT * FROM [chat_settings]');


	$posts = array();
	if ($rows)
	{
		if($chat_settings['life_messages'] == 0) 
		{
			$result = $_pdo->getData('SELECT * FROM [chat_messages] ORDER BY `id` DESC'); 
		}
		else
		{
			$result = $_pdo->exec('DELETE FROM [chat_messages] WHERE date < '.(time() - ($chat_settings['life_messages']*60)));
			$result = $_pdo->getData('SELECT * FROM [chat_messages] WHERE date > '.(time() - ($chat_settings['life_messages']*60)).' ORDER BY `id` DESC');    
		}

		foreach ($result as $row) 
		{
			$posts[] = array(
				'id' => $row['id'],
				'user' => $_user->getusername($row['user_id']),
				'content' => $row['content'],
				'date' => HELP::showDate('longdate', $row['datestamp'])
			);
		}
	}

$_ajax->assign('rows', $rows);
$_ajax->assign('posts', $posts);

$_ajax->assign('iAdmin', $_user->iAdmin());

$_ajax->template('shoutbox_messages.tpl');