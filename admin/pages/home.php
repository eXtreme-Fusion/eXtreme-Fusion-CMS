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
// TO DO
// Poprawić wygląd notatek
// Podpiąć download
// Sprawdzanie wersji systemu z aktualną dostępną na EF.
// TO DO

try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->load('home');

    if ( ! $_user->isLoggedIn())
    {
       $_request->redirect(ADDR_ADMIN.'index.php', array('action' => 'login'));
    }
	
	$_tpl = new Iframe;
	
	if ($_request->get('act')->show() && $_request->get('status')->show() && $_request->get('quick_news')->show())
    {
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add' => array(
					__('Quick news has been added.'), __('Error! Quick news has not been added.')
				)
			)
		);
		$_tpl->assign('quick_news_log', TRUE);
    }

	if ($_request->get('act')->show() && $_request->get('status')->show() && $_request->get('note')->show())
    {
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'delete' => array(
					__('Note has been deleted.'), __('Error! Note has not been deleted.')
				),
				'add' => array(
					__('Note has been added.'), __('Error! Note has not been added.')
				)
			)
		);
		
		$_tpl->assign('notes_log', TRUE);
    }
	
	if ($_request->post('note_add_save')->show() === 'yes')
	{
		$count = $_pdo->getMatchRowsCount('
			INSERT INTO [notes] (`title`, `note`, `author`, `block`, `datestamp`)
			VALUES (:title, :note, :author, :block, '.time().')',
			array(
				array(':title', $_request->post('title')->filters('trim', 'strip'), PDO::PARAM_STR),
				array(':note', $_request->post('note')->filters('trim', 'strip'), PDO::PARAM_STR),
				array(':author', $_user->get('id'), PDO::PARAM_INT),
				array(':block', $_request->post('block_edit')->show(), PDO::PARAM_INT)
			)
		);

		if ($count)
		{
			$_log->insertSuccess('add', __('Note has been added.'));
			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok', 'note' => TRUE));
		}
		
		$_log->insertFail('add', __('Error! Note has not been added.'));
		$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error', 'note' => TRUE));
	}

	if ($_request->get('action')->show() === 'delete' && $_request->get('note_id')->isNum())
	{
		$count = $_pdo->exec('DELETE FROM [notes] WHERE `id` = :id', array(
			array(':id', $_request->get('note_id')->show(), PDO::PARAM_INT)
		));

		if ($count)
        {
			$_log->insertSuccess('delete', __('Note has been deleted.'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok', 'note' => TRUE));
        }
		
		$_log->insertFail('delete', __('Error! Note has not been deleted.'));
        $_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error', 'note' => TRUE));
	}

	if ($_request->post('note_add')->show() === 'yes')
	{
		$_tpl->assign('notes_add', TRUE);
	}
	else
	{
		$query = $_pdo->getData('SELECT * FROM [notes] ORDER BY datestamp DESC');

		if ($count = $_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				$data[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'note' => $row['note'],
					'author' => $_user->getusername($row['author']),
					'author_id' => $row['author'],
					'user_id' => $_user->get('id'),
					'block' => $row['block'],
					'datestamp' => HELP::showDate('shortdate', $row['datestamp'])
				);
			}

			$_tpl->assign('notes', $data);
		}

		$_tpl->assignGroup(array(
			'notes_per_page' => $_sett->get('notes_per_page'),
			'current' => $count
		));
	}

	if ($_request->post('quick_news_add')->show() && $_request->post('quick_news_title')->show() && $_request->post('quick_news_content')->show())
	{
		if ( ! $_user->hasPermission('admin.news'))
		{
			throw new userException(__('Access Denied'));
		}
		
		$count = $_pdo->getRow('SELECT `id` FROM [news] WHERE `title` = :title', 
			array(':title', $_request->post('quick_news_title')->filters('trim', 'strip'), PDO::PARAM_STR)
		);

		if ( ! $count)
		{
			$count = $_pdo->exec('
				INSERT INTO [news] (`title`, `link`, `content`, `language`, `author`, `access`, `datestamp`, `draft`, `sticky`, `allow_comments`, `allow_ratings`)
				VALUES (:title, :link, :content, :language, :author, :access, :datestamp, :draft, :sticky, :allow_comments, :allow_ratings)',
				array(
					array(':title', $_request->post('quick_news_title')->filters('trim', 'strip'), PDO::PARAM_STR),
					array(':link', $_request->post('quick_news_title')->filters('setTitleForLinks'), PDO::PARAM_STR),
					array(':content', $_request->post('quick_news_content')->show(), PDO::PARAM_STR),
					array(':language', $_sett->get('locale'), PDO::PARAM_STR),
					array(':author', $_user->get('id'), PDO::PARAM_INT),
					array(':access', 3, PDO::PARAM_INT),
					array(':datestamp', time(), PDO::PARAM_INT),
					array(':draft', 0, PDO::PARAM_INT),
					array(':sticky', 0, PDO::PARAM_INT),
					array(':allow_comments', 1, PDO::PARAM_INT),
					array(':allow_ratings', 1, PDO::PARAM_INT)
				)
			);
	
			if ($count)
			{
				$_system->clearCache('news');
				$_log->insertSuccess('add', __('Quick news has been added.'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok', 'quick_news' => TRUE));
			}
			$_log->insertFail('add', __('Error! Quick news has not been added.'));
			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error', 'quick_news' => TRUE));
		}
		
		$_tpl->printMessage('error', $_log->insertFail('add', __('News już istnieje w bazie danych.')));
	}

	$query = $_pdo->getData('SELECT * FROM [logs] ORDER BY `datestamp` DESC LIMIT 0,7');

	if ($_pdo->getRowsCount($query))
	{
		$i = 0; $logs = array();
		foreach($query as $row)
		{
			if($actions = @unserialize($row['action']))
			{
				$logs[] = array(
					'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'message' => ! empty($actions['3']) ? $actions['3'] : __('---No Data---'),
					'user' => $_user->getusername($actions['4']),
					'ip' => $actions['5'],
					'Date' => HELP::showDate('longdate', $row['datestamp']),
				);
				$i++;
			}
		}

		$_tpl->assign('logs', $logs);
	}

	$_tpl->assign('version', $_sett->get('version'));

	$_tpl->template('home');
}
catch(optException $exception)
{
	optErrorHandler($exception);
}
catch(systemException $exception)
{
	systemErrorHandler($exception);
}
catch(userException $exception)
{
	userErrorHandler($exception);
}
catch(PDOException $exception)
{
    PDOErrorHandler($exception);
}
