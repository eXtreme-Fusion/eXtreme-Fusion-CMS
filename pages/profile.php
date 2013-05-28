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
$_locale->load('profile');
$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/profile.css" media="screen" rel="stylesheet">');
$_head->set('<script src="'.ADDR_TEMPLATES.'javascripts/jquery.tabs.js"></script>');

$row = $_pdo->getRow('SELECT `id`, `username`, `avatar`, `email`, `hide_email`, `joined`, `lastvisit`, `role` FROM [users] WHERE `id` = :id', array(':id', $_route->getByID(1), PDO::PARAM_INT));

if ($row)
{
	$_tpl->assign('profile', TRUE);
	$_tpl->assign('iAdmin', $_user->iAdmin());
	
	$theme = array(
		'Title' => __('Profil użytkownika - :Username', array(':Username' => $row['username'])).' &raquo; '.$_sett->get('site_name'),
		'Keys' => 'Profil użytkownika '.$row['username'].', konto '.$row['username'].', podgląd konta '.$row['username'].' ',
		'Desc' => 'Odwiedź konto użytkownika '.$row['username'].', skontaktuj się z nim, sprawdź aktywność.'
	);

	// Blokuje wykonywanie pliku TPL z katalogu szablonu
	define('THIS', FALSE);
	
	// nazwa pliku bez rozszerzenia, dane do zapisu (jeśli brak to funkcja zwraca dane o ile plik istnieje), czas użyteczności pliku (nadpisanie w przypadku zbyt starej wersji)
	$cats = $_system->cache('profile_id_'.$row['id'].'_cats', NULL, 'profiles', $_sett->getUns('cache', 'expire_profile'));
	if ($cats === NULL)
	{
		// Pobieranie kategorii
		$query = $_pdo->getData('SELECT * FROM [user_field_cats] ORDER BY `order` ASC');
		$cats = array();
		
		// Przepisywanie pobranych danych na zwykłą tablicę
		foreach($query as $data)
		{
			$cats[] = $data;
		}
		$_system->cache('profile_id_'.$row['id'].'_cats', $cats, 'profiles');
	}
	
	// nazwa pliku bez rozszerzenia, dane do zapisu (jeśli brak to funkcja zwraca dane o ile plik istnieje), czas użyteczności pliku (nadpisanie w przypadku zbyt starej wersji)
	$fields = $_system->cache('profile_id_'.$row['id'].'_user_fields', NULL, 'profiles', $_sett->getUns('cache', 'expire_profile'));
	if ($fields === NULL)
	{
		// Pobieranie pól
		if ($_user->get('role') == 1)
		{
			$query = $_pdo->getData('SELECT * FROM [user_fields]');
		}
		else
		{
			$query = $_pdo->getData('SELECT * FROM [user_fields] WHERE `hide` = 0');
		}
		// Przepisywanie pobranych pól na zwykłą tablicę
		foreach($query as $data)
		{
			$fields[] = $data;
		}
		$_system->cache('profile_id_'.$row['id'].'_user_fields', $fields, 'profiles');
	}

	
	// nazwa pliku bez rozszerzenia, dane do zapisu (jeśli brak to funkcja zwraca dane o ile plik istnieje), czas użyteczności pliku (nadpisanie w przypadku zbyt starej wersji)
	$data = $_system->cache('profile_id_'.$row['id'].'_user_data', NULL, 'profiles', $_sett->getUns('cache', 'expire_profile'));
	if ($data === NULL)
	{
		// Pobieranie wszystkich dodatkowych pól uzytkowników
		$data = $_pdo->getRow('SELECT * FROM [users_data] WHERE `user_id` ='.$row['id'].' LIMIT 1');
		
		// Przepisywanie pobranych pól na zwykłą tablicę
		$_system->cache('profile_id_'.$row['id'].'_user_data', $data, 'profiles');
	}
	$i = 0;

	# Segregacja danych
	if (isset($fields))
	{
		$_new_fields = array();

		foreach($cats as $key => &$cat)
		{
			$field_exists = FALSE;
			foreach($fields as $field)
			{
				if ($field['cat'] === $cat['id'])
				{
					$new_fields[$key][$i] = $field;
					
					// Sprawdzanie, czy użytkownik podał dane w wybranym polu
					
					if (isset($data[$field['index']]) && $data[$field['index']])
					{
						$new_fields[$key][$i]['value'] = $field['type'] === '2' ? $ec->sbb->parseAllTags($data[$field['index']]) : $data[$field['index']];
						$field_exists = TRUE;
					}
					else
					{
						$new_fields[$key][$i]['value'] = NULL;
					}
					//$new_fields[$key][$i]['value'] = isset($data[$field['index']]) && $data[$field['index']] ? $data[$field['index']] : NULL;
					$i++;
					
				}
			}
			
			if ($field_exists)
			{
				$cat['has_fields'] = '1';
			}
			else
			{
				$cat['has_fields'] = '0';
			}
		}

		$_tpl->assign('fields', $new_fields);
	}

	$_tpl->assign('cats', $cats);


	#************

	$query = $_pdo->getData('SELECT `id` FROM [news] WHERE `author` = :id',
		array(':id', $row['id'], PDO::PARAM_INT)
	);
	$news = $_pdo->getRowsCount($query);
	
	$query = $_pdo->getData('SELECT `id` FROM [comments] WHERE `author` = :id',
		array(':id', $row['id'], PDO::PARAM_INT)
	);
	$comment = $_pdo->getRowsCount($query);
	
	$data = array(
		'id' => $row['id'],
		'username' => $_user->getUsername($row['id']),
		'hide_email' => $row['hide_email'],
		'role' => $_user->getRoleName($row['role']),
		'roles' => implode($_user->getUserRolesTitle($row['id'], 3), ', '),
		'avatar' => $_user->getAvatarAddr($row['id']),
		'email' => HELP::hide_email($row['email']),
		'joined' => HELP::showDate('shortdate', $row['joined']),
		'joined_datetime' => date('c', $row['joined']),
		'last_visit_time' => $row['lastvisit'] ? HELP::showDate('longdate', $row['lastvisit']) : __('Nie był na stronie'),
		'is_online' => inArray($row['id'], $_user->getOnline(), 'id') ? 1 : 0,
		'time' => time(),
		'news' => $news,
		'comment' => $comment,
		'myid' => $_user->get('id'),
		'pm_link' => $_route->path(array('controller' => 'messages', 'action' => 'new', $row['id']))
	);
	//var_dump(inArray($row['id'], $_user->getOnline(), 'id'));exit;
	if ($_pdo->tableExists('chat_messages'))
	{
		$query = $_pdo->getData('SELECT `id` FROM [chat_messages] WHERE `user_id` = :id',
			array(':id', $row['id'], PDO::PARAM_INT)
		);
		$chat = $_pdo->getRowsCount($query);
		
		$_tpl->assign('chat', $chat);
	}
	
	if (class_exists('Points', FALSE))
	{
		$points = array(
			'points' => $_points->show($row['id']),
			'rank' => $_points->showRank($row['id'])
		);
		
		$_tpl->assign('points_stat', $points);
	}
	
	if ($_pdo->tableExists('cautions'))
	{
		$query = $_pdo->getData('SELECT `id` FROM [cautions] WHERE `user_id` = :id',
			array(':id', $row['id'], PDO::PARAM_INT)
		);
		$cautions = array(
			'cautions' => $_pdo->getRowsCount($query),
			'link' => $_route->path(array('controller' => 'cautions', 'action' => $row['id']))
		);
		
		$_tpl->assign('cautions', $cautions);
	}
	
	$_tpl->assign('user', $data);
	
	// START :: Point System module - admin action
	if (class_exists('Points', FALSE) && $_user->hasPermission('module.point_system.admin'))
	{
		$_locale->moduleLoad('lang', 'point_system');
		$_log = new Logger($_user, $_pdo, $_sett->get('logger_active'));
		
		$_tpl->assign('points', TRUE);
		
		if($_request->post('plus_points')->show() && $_request->post('points_bonus')->isNum())
		{
			if($_request->post('add_comment')->show() != NULL)
			{
				$comment = $_request->post('add_comment')->strip().'&nbsp;';
				$comment .= __('(:points points)', array(':points' => $_request->post('points_bonus')->show()));
			}
			else
			{
				$comment = __('Bonus points to the administrator (:points points)', array(':points' => $_request->post('points_bonus')->show()));
			}
			if ($_request->post('points_bonus')->show() > 0)
			{
				$_points->add($row['id'], $_request->post('points_bonus')->show(), $comment);
				$_system->clearCache('point_system');
				$_log->insertSuccess('add_bonus', $_user->getByID($row['id'], 'username').' - Dodanie punktów przez profil: '.$comment);
				$_request->redirect($_route->path(array('controller' => 'profile', 'action' => $row['id'])));
			}
		}
		elseif($_request->post('minus_points')->show() && $_request->post('points_fine')->isNum())
		{
			if($_request->post('fine_comment')->show() != NULL)
			{
				$comment = $_request->post('fine_comment')->strip().'&nbsp;';
				$comment .= __('(:points points)', array(':points' => $_request->post('points_fine')->show()));
			}
			else
			{
				$comment = __('Bonus points to the administrator (:points points)', array(':points' => $_request->post('points_fine')->show()));
			}
			if ($_request->post('points_fine')->show() > 0)
			{
				$_points->add($row['id'], -$_request->post('points_fine')->show(), $comment);
				$_system->clearCache('point_system');
				$_log->insertSuccess('add_fine', $_user->getByID($row['id'], 'username').' - Odjęcie punktów przez profil: '.$comment);
				$_request->redirect($_route->path(array('controller' => 'profile', 'action' => $row['id'])));
			}
		}
		elseif($_request->post('delete_user_points')->show())
		{
			$_points->deleteAll($row['id']);
			$_system->clearCache('point_system');
			$_log->insertSuccess('delete_points', $_user->getByID($row['id'], 'username').' - Akcja przez profil: '.__('Deleted all user points.'));
			$_request->redirect($_route->path(array('controller' => 'profile', 'action' => $row['id'])));
		}
	}
	// END :: Point System module - admin action 
}
