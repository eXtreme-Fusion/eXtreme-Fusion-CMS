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
	$_locale->moduleLoad('admin', 'contact');

	if ( ! $_user->hasPermission('module.contact.admin'))
	{
		throw new userException(__('Access denied'));
	}
	
	$_fav->setFavByLink('contact/admin/contact.php', $_user->get('id'));
	
	$_tpl = new AdminModuleIframe('contact');
	
	$_tpl->setHistory(__FILE__);
	
	// Wyświetlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add' => array(
					__('Contact form has been added.'), __('Error! Contact form has not been added.')
				),
				'edit' => array(
					__('Contact form has been edited.'), __('Error! Contact form has not been edited.')
				),
				'delete' => array(
					__('Contact form has been deleted.'), __('Error! Contact form has not been deleted.')
				)
			)
		);
	}

	if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum(TRUE))
	{
		$count = $_pdo->exec('DELETE FROM [contact] WHERE `id` = :id',
			array(
				array(':id', $_request->get('id')->isNum(), PDO::PARAM_INT)
			)
		);
	
		if ($count)
		{
			$_log->insertSuccess('delete', __('Contact form has been deleted.'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}

		$_log->insertFail('delete', __('Error! Contact form has not been deleted.'));
		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
	}
	elseif ($_request->post('save')->show() && $_request->post('email')->isEmail() && $_request->post('title')->show())
	{
		$title = $_request->post('title')->filters('trim', 'strip');
		$email = $_request->post('email')->isEmail();
		$description = $_request->post('description')->strip();
		$value = $_request->post('value')->strip();
		
		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$count = $_pdo->exec('UPDATE [contact] SET `title` = :title, `email` = :email, `description` = :description, `value` = :value WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					array(':title', $title, PDO::PARAM_STR),
					array(':email', $email, PDO::PARAM_STR),
					array(':description', $description, PDO::PARAM_STR),
					array(':value', $value, PDO::PARAM_STR)
				)
			);

			if ($count)
			{
				$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
			}
		
			$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
		}
		else
		{
			$count = $_pdo->exec('INSERT INTO [contact] (`title`, `email`, `description`, `value`) VALUES (:title, :email, :description, :value)',
				array(
					array(':title', $title, PDO::PARAM_STR),
					array(':email', $email, PDO::PARAM_STR),
					array(':description', $description, PDO::PARAM_STR),
					array(':value', $value, PDO::PARAM_STR)
				)
			);
				
			if ($count)
			{
				$_log->insertSuccess('add', __('Contact form has been added.'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
			}

			$_log->insertFail('add', __('Error! Contact form has not been added.'));
			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
		}
		
		$_request->redirect(FILE_PATH);
	}
	elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
	{
		$data = $_pdo->getRow('SELECT * FROM [contact] WHERE `id` = :id',
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);
		
		if($data)
		{
			$contact = array(
				'title' => $data['title'],
				'email' => $data['email'],
				'description' => $data['description'],
				'value' => $data['value']
			);
		}
		else
		{
			throw new userException(__('No ID :id for the table contact.', array(':id' => $_request->get('id')->isNum())));
		}
    }
    else
    {
        $contact = array();
    }

	$_tpl->assign('contact', $contact);
	
	$query = $_pdo->getData('SELECT `id`, `title`, `email` FROM [contact] ORDER BY `id` DESC');
	
	$i = 0; $contacts = array();
	foreach($query as $row)
	{
		$contacts[] = array(
			'row_color' => ($i % 2 == 0 ? 'tbl1' : 'tbl2'),
			'id' => $row['id'],
			'title' => $row['title'],
			'email' => $row['email']
		);
		$i++;
	}
	
	$_tpl->assign('data', $contacts);

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