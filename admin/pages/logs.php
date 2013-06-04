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
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	$_locale->load('logs');

    if ( ! $_user->hasPermission('admin.logs'))
    {
        throw new userException(__('Access denied'));
    }

    $_tpl = new Iframe;

	// Czy usunąć przestarzałe wpisy Rejestru zdarzeń?
	if ($_sett->get('logger_optimize_active'))
	{
		$_log->deleteOld($_sett->get('logger_expire_days'));
	}

	if ($_request->get(array('status', 'act'))->show())
    {
		if ($_request->get('type')->show() === 'all')
		{
			$info = array(__('All logs have been deleted.'), __('Error! Logs have not been deleted.'));
		}
		else
		{
			$info = array(__('The log has been deleted.'), __('Error! The log has not been deleted.'));
		}

		// Czy zapisać w Rejestrze usunięcie wpisu?
		if ($_sett->get('logger_save_removal_action'))
		{
			$_log->insertSuccess('delete', $info[0]);
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), array('delete' => $info));
		}
		else
		{
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), array('delete' => $info));
		}
    }

    if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
    {
		$count = $_pdo->exec('DELETE FROM [logs] WHERE `id` = '.$_request->get('id')->show());

		if ($count)
		{
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}

		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
    }
	elseif ($_request->get('action')->show() === 'delete_all')
	{
		$count = $_pdo->exec('DELETE FROM [logs]');

		if ($count)
		{
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'all', 'status' => 'ok'));
		}

		$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'all', 'status' => 'error'));
	}
    else
    {
		$query = $_pdo->getData('
			SELECT * FROM [logs]
            ORDER BY `datestamp` DESC
			LIMIT 0,50
		');

		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $logs = array();
	        foreach($query as $row)
            {
				$actions = unserialize($row['action']);
				$data['row_color'] = $i % 2 == 0 ? 'tbl1' : 'tbl2';
				$data['id'] = $row['id'];
				$data['action'] = __($actions['0']);
				$data['status'] = __($actions['1']);
				$data['file'] = $actions['2'];
				$data['message'] = $actions['3'] ? $actions['3'] : __('---No Data---');
				$data['user'] = $_user->getUsername($actions['4']);
				$data['ip'] = $actions['5'];
				$data['date'] = HELP::showDate('longdate', $row['datestamp']);
                $logs[] = $data;

				unset($row, $data);
                $i++;
            }

			$_tpl->assign('logs', $logs);
        }
    }

	$_tpl->assign('active', $_sett->get('logger_active'));

    $_tpl->template('logs');
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
