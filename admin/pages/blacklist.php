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
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	$_locale->load('blacklist');

    if ( ! $_user->hasPermission('admin.blacklist'))
    {
        throw new userException(__('Access denied'));
    }

	$_tpl = new Iframe;
	
	// Wyœwietlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyœwietli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add' => array(
					__('Address has been added to the blacklist.'), __('Error! Address has not been added to the blacklist.')
				),
				'edit' => array(
					__('Blocked address has been edited.'), __('Error! Blockd address has not been edited.')
				),
				'delete' => array(
					__('Address has been deleted from the blacklist.'), __('Error! Address has not been deleted from the blacklist.')
				)
			)
		);
    }
	
    if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
    {
		$count = $_pdo->exec('DELETE FROM [blacklist] WHERE `id`= '.$_request->get('id')->show());
		
		if ($count)
        {
			$_log->insertSuccess('delete', __('Address has been deleted from the blacklist.'));
            $_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
        }
		
		$_log->insertFail('delete', __('Error! Address has not been deleted from the blacklist.'));
		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
    }
    elseif ($_request->post('save')->show())
    {
		$ip = $_request->post('blacklist_ip')->filters('trim', 'strip');
		$email = $_request->post('blacklist_email')->filters('trim', 'strip');
		$reason = $_request->post('blacklist_reason')->filters('trim', 'strip');
		$form_title = __('Blacklist');
		
		if ($email && $ip)
		{
			throw new userException(__('Error! E-mail address can not be accepted with IP address. You must add IP address or e-mail address.'));
		}
		
		if (strpos($ip, '.')) 
		{
			if (strpos($ip, ':') === FALSE) 
			{
				$ip_type = 4;
			} 
			else 
			{
				$ip_type = 5;
			}
		} 
		else
		{
			$ip_type = 6;
		}
		
		if ( ! $email || ! $ip)
		{
			
			if ( ! $_request->post('id')->show())
			{
				if ($ip)
				{
					$count = $_pdo->getMatchRowsCount('SELECT `id` FROM [blacklist] WHERE `ip` = :ip', 
						array(':ip', $ip, PDO::PARAM_STR)
					);
				}
				elseif ($email)
				{
					$count = $_pdo->getMatchRowsCount('SELECT `id` FROM [blacklist] WHERE `email`= :email', 
						array(':email', $email, PDO::PARAM_STR)
					);
				}
			}
			
			
			if ($_request->post('save')->show() === 'yes' && $_request->post('id')->show())
			{
				$count = $_pdo->exec('UPDATE [blacklist] SET `ip` = :ip, `type` = :type, `email` = :email, `reason` = :reason WHERE `id` = :id',
					array(
						array(':id', $_request->post('id')->show(), PDO::PARAM_INT),
						array(':ip', $ip, PDO::PARAM_STR),
						array(':type', $ip_type, PDO::PARAM_INT),
						array(':email', $email, PDO::PARAM_STR),
						array(':reason', $reason, PDO::PARAM_STR)
					)
				);

				if ($count)
				{
					$_log->insertSuccess('edit', __('Blocked address has been edited.'));
					$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
				}

				$_log->insertFail('edit', __('Error! Blockd address has not been edited.'));
				$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'error'));
			}
			else
			{
				$count = $_pdo->exec('INSERT INTO [blacklist] (`ip`, `type`, `user_id`, `email`, `reason`, `datestamp`) VALUES (:ip, :type, :user_id, :email, :reason, '.time().')',
					array(
						array(':ip', $ip, PDO::PARAM_STR),
						array(':type', $ip_type, PDO::PARAM_INT),
						array(':user_id', $_user->get('id'), PDO::PARAM_INT),
						array(':email', $email, PDO::PARAM_STR),
						array(':reason', $reason, PDO::PARAM_STR)
					)
				);

				if ($count)
				{
					$_log->insertSuccess('add', __('Address has been added to the blacklist.'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
				}

				$_log->insertFail('add', __('Error! Address has not been added to the blacklist.'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
			}
		}
		else
		{
			throw new userException(__('Error! Entered data is incorrect. Please validate e-mail address or IP address.'));
		}
    }
    if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
    {
		$row = $_pdo->getRow('SELECT `id`, `ip`, `email`, `reason` FROM [blacklist] WHERE `id` = '.$_request->get('id')->show());
     	
		if ($row)
		{
			$id = $row['id'];
			$ip = $row['ip'];
			$email = $row['email'];
			$reason = $row['reason'];
			$form_title = __('Blocked address edition');
		}
		else
        {
			throw new userException(__('Error! Missing id.', array(':id' => $_request->get('id')->show())));
        }
    }
    else
    {
        $ip = '';
        $email = '';
        $reason = '';
        $form_title = __('Blacklist');
    }

    $_tpl->assign('blaclist_form', array(
		'title' => $form_title,
		'ip' => $ip,
		'email' => $email,
		'reason' => $reason,
		'id' => isset($id) ? $id : NULL
	));

	$query = $_pdo->getData('
		SELECT b.`id`, b.`ip`, b.`email`, b.`reason`, b.`datestamp`, u.`id` AS uid, u.`username`, u.`status`
		FROM [blacklist] b
		LEFT JOIN [users] u
		ON u.`id` = b.`user_id`
		ORDER BY `datestamp`
		DESC LIMIT 0,20
	');
	
	if ($_pdo->getRowsCount($query))
	{
        $i = 0; $blacklist_ = array();
        foreach($query as $row)
        {
            $blacklist[] = array(
                'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
                'id' => $row['id'],
                'ip' => $row['ip'],
                'email' => $row['email'],
                'reason' => $row['reason'],
                'username' => $row['username'],
                'datestamp' => $row['datestamp'] != 0 ? HELP::showDate('longdate', $row['datestamp']) : __('N/A')
            );
            $i++;
        }
		
        $_tpl->assign('blacklist', $blacklist);
    }
    $_tpl->template('blacklist');
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
