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

    $_tpl = new AdminModuleIframe('cautions');

	// Wyœwietlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyœwietli komunikat
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
			$_log->insertSuccess('delete', __('Ostrze¿enie zosta³o usuniête'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}
	
		$_log->insertFail('delete', __('B³¹d podczas usuwania ostrze¿enia'));
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

					$count = $_pdo->exec('INSERT INTO [messages] (`to`, `from`, `subject`, `message`, `datestamp`) VALUES (:user, :admin, :subject, :message, '.time().')',
						array(
							array(':user', $_request->get('user')->show(), PDO::PARAM_INT),
							array(':admin', $_user->get('id'), PDO::PARAM_INT),
							array(':subject', $subject, PDO::PARAM_STR),
							array(':message', $message, PDO::PARAM_STR)
						)
					);
				}
		
				if ($count)
				{
					$_log->insertSuccess('add', __('Ostrze¿enie zosta³o dodane'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
				}	
		
				$_log->insertFail('add', __('B³¹d podczas dodawania ostrze¿enia'));
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