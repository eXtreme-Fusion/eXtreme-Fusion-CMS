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
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->moduleLoad('admin', 'newsletter');

    if ( ! $_user->hasPermission('module.newsletter.admin'))
    {
        throw new userException(__('Access denied'));
    }

    $_tpl = new AdminModuleIframe('newsletter');
	
	$_tpl->setHistory(__FILE__);
	
	if ($_request->post('send')->show() === 'yes' && $_request->post('message')->show() && $_request->post('subject')->show())
    {
		$query = $_pdo->getData('SELECT email FROM [newsletter]');
		
		if ($res = $_pdo->getRowsCount($query))
		{	
			$_mail = new Mailer($_sett->get('smtp_username'), $_sett->get('smtp_password'), $_sett->get('smtp_host'));
			$data = array();
			foreach ($query as $row)
			{
				$data[] = $row['email'];
			}
			var_dump($data);
			//$count = $_mail->sendBcc($_request->post('mail')->show(), $_sett->get('contact_email'), $_request->post('subject')->show(), $_request->post('message')->show());
		}
	}
	
	if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
    {
		$count = $_pdo->exec('DELETE FROM [newsletter] WHERE `id` = '.$_request->get('id')->show());

		if ($count)
		{
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}

		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
    }
	elseif ($_request->get('action')->show() === 'delete_all')
	{
		$count = $_pdo->exec('DELETE FROM [newsletter]');

		if ($count)
		{
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'all', 'status' => 'ok'));
		}

		$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'all', 'status' => 'error'));
	}
    else
    {
	
		$query = $_pdo->getData('SELECT * FROM [newsletter]');
		
		if ($res = $_pdo->getRowsCount($query))
		{
			$i = 0;
			foreach ($query as $row)
			{
				
				$data[] = array(
					'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'id' => $row['id'],
					'email' => $row['email'],
					'ip' => $row['ip'],
					'datestamp' => HELP::showDate('longdate', $row['datestamp'])
				);
				$i++;
			}
			$_tpl->assign('data', $data);
		}
	}
	
	$_tpl->template('admin.tpl');
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