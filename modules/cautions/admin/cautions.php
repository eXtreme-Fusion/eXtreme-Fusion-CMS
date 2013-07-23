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
	
	$_locale->moduleLoad('admin', 'cautions');

	if ( ! $_user->hasPermission('module.cautions.admin'))
	{
		throw new userException(__('Access denied'));
	}
	
	$_fav->setFavByLink('cautions/admin/cautions.php', $_user->get('id'));
	
    $_tpl = new AdminModuleIframe('cautions');
	
	$_tpl->setHistory(__FILE__);
	
	// Wyświetlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add' => array(
					__('Added warning'), __('Fails to add warnings')
				),
				'edit' => array(
					__('Edited warning'), __('Error when editing warning')
				),
				'delete' => array(
					__('Deleted warning'), __('Crash deleting warnings')
				)
			)
		);
	}

    if ($_request->get('action')->show() === 'delete' && $_request->get('caution_id')->isNum())
    {
		$count = $_pdo->exec('DELETE FROM [cautions] WHERE `id` = :id',
			array(
				array(':id', $_request->get('caution_id')->show(), PDO::PARAM_INT)
			)
		);
	
		if ($count)
		{
			$_log->insertSuccess('delete', __('Ostrzeżenie zostało usunięte'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}
	
		$_log->insertFail('delete', __('Błąd podczas usuwania ostrzeżenia'));
		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
    }
	
	if($_request->get('user')->show())
	{
		$user = TRUE;
		if ($_request->post('save')->show())
		{
			$content = $_request->post('cautions')->filters('trim', 'strip');
		
			if ($_request->get('action')->show() === 'edit' && $_request->get('caution_id')->isNum())
			{
				$count = $_pdo->exec('UPDATE [cautions] SET `date` = '.time().', `content` = :content WHERE `id` = :id',
					array(
						array(':id', $_request->get('caution_id')->isNum(), PDO::PARAM_INT),
						array(':content', $content, PDO::PARAM_STR)
					)
				);

				if ($count)
				{
					$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
				}
		
				$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
			}
			else
			{
				$count = $_pdo->exec('INSERT INTO [cautions] (`user_id`, `admin_id`, `content`, `date`) VALUES (:user, :admin, :content, '.time().')',
					array(
						array(':user', $_request->get('user')->show(), PDO::PARAM_INT),
						array(':admin', $_user->get('id'), PDO::PARAM_INT),
						array(':content', $content, PDO::PARAM_STR)
					)
				);

				if ($_request->post('send_pm')->show())
				{
					$subject = __('Received Warning');
					$message = __('Administrator punish you a warning. Read the rules.');
					$item_id = $_pdo->getField('SELECT max(`item_id`) FROM [messages]') + 1;

					$count = $_pdo->exec('INSERT INTO [messages] (`item_id`, `to`, `from`, `subject`, `message`, `datestamp`) VALUES (:item, :user, :admin, :subject, :message, '.time().')',
						array(
							array(':item', $item_id, PDO::PARAM_INT),
							array(':user', $_request->get('user')->show(), PDO::PARAM_INT),
							array(':admin', $_user->get('id'), PDO::PARAM_INT),
							array(':subject', $subject, PDO::PARAM_STR),
							array(':message', $message, PDO::PARAM_STR)
						)
					);
				}
		
				if ($count)
				{
					$_log->insertSuccess('add', __('Ostrzeżenie zostało dodane'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
				}	
		
				$_log->insertFail('add', __('Błąd podczas dodawania ostrzeżenia'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
			}
		}
		elseif ($_request->get('action')->show() === 'edit' && $_request->get('caution_id')->isNum())
		{
			$data = $_pdo->getRow('
				SELECT c.`id`, c.`user_id`, c.`content`, u.`id`, u.`username`
				FROM [cautions] c
				LEFT JOIN [users] u ON c.`user_id` = u.`id`
				WHERE c.`id` = :id',
				array(':id', $_request->get('caution_id')->isNum(), PDO::PARAM_INT)
			);
			
			if($data)
			{
				$user_list = $_user->getUsername($data['user_id']);
				$user_id = $data['user_id'];
				$cautions = $data['content'];
				$edit = TRUE;
			}
			else
			{
				throw new userException(__('No ID :id for the table cautions.', array(':id' => $_request->get('caution_id')->isNum())));
			}
		}
		else
		{
			$user_id = '';
			$user_list = $_user->getByID($_request->get('user')->show(), 'username');
			$cautions = '';
			$edit = FALSE;
		
		}
		
		$_tpl->assignGroup(
			array(
				'cautions' => $cautions,
				'username' => $user_list,
				'user_id' => $user_id,
				'edit' => $edit
			)
		);
	}
	else
	{
		$user = FALSE;
	}
	
	$_tpl->assign('user', $user);

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