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

if($_modules->isInstalled('forum'))
{
	$_panel->assign('isInstalled', TRUE);
	$_locale->moduleLoad('lang', 'forum');
	$query = $_pdo->getData('
			SELECT 
				t.*,
				u.username as username,
				(SELECT COUNT(e.id)-1 FROM [entries] e WHERE e.thread_id = t.id) as entries,
				e.id as entry_id,
				e.user_id as entry_user,
				e.timestamp as entry_timestamp
			FROM [threads] t
			LEFT JOIN [users] u
			ON u.id = t.user_id
			LEFT JOIN (SELECT e.* FROM [entries] e ORDER BY e.id DESC) e
			ON e.thread_id = t.id
			GROUP BY t.id
			ORDER BY
				t.timestamp DESC
			LIMIT 0,10
	');
	if ($query)
	{
		$i = 0; $threads = array();
		foreach($query as $row)
		{
			$threads[] = array(
				'row' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
				'link' => $_url->path(array('module' => 'forum', 'controller' => 'thread', $row['id'])).'#entry-'.$row['entry_id'],
				'id' => $row['id'],
				'timestamp' => HELP::showDate('shortdate', $row['timestamp']),
				'autor' => HELP::profileLink($row['username'], $row['user_id']),
				'title' => $row['title'],
				'entries' => $row['entries'],
				'entry_user' => HELP::profileLink(NULL, $row['entry_user'])
			);
			$i++;
		}
		
		$_panel->assign('threads', $threads);
	}
}
