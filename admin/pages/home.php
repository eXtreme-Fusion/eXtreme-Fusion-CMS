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
			$_tpl->printMessage('valid', __('Note has been added.'));
		}
		else
		{
			$_log->insertFail('add', __('Error! Note has not been added.'));
			$_tpl->printMessage('error', __('Error! Note has not been added.'));
		}

		$_tpl->assign('notes_log', TRUE);
	}

	if ($_request->get('action')->show() === 'delete' && $_request->get('note_id')->isNum())
	{
		$count = $_pdo->exec('DELETE FROM [notes] WHERE `id` = :id', array(
			array(':id', $_request->get('note_id')->show(), PDO::PARAM_INT)
		));

		if ($count)
        {
			$_log->insertSuccess('delete', __('Note has been deleted.'));
			$_tpl->printMessage('valid', __('Note has been deleted.'));

        }
		else
		{
			$_log->insertFail('delete', __('Error! Note has not been deleted.'));
			$_tpl->printMessage('valid', __('Error! Note has not been deleted.'));

		}

		$_tpl->assign('notes_log', TRUE);
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
					'author' => HELP::profileLink(NULL, $row['author']),
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