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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+------------------------------------------------------
| Author: Nick Jones (Digitanium)
+------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+------------------------------------------------------*/
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	$_locale->load('user_fields_cats');

    if ( ! $_user->hasPermission('admin.user_fields_cats'))
    {
        throw new userException(__('Access denied'));
    }

    $_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'add' => array(
					__('Category has been added.'), __('Error! Category has not been added.')
				),
				'edit' => array(
					__('Category has been edited.'), __('Error! Category has not been edited.')
				),
				'delete' => array(
					__('Category has been deleted.'), __('Error! Category has not been deleted.')
				)
			)
		);
	}

  	$cat_name = '';
	$cat_order = '';

    if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
    {
		$query = $_pdo->getRow('SELECT * FROM [user_fields] WHERE `cat` = :cat',
			array(':cat', $_request->get('id')->show(), PDO::PARAM_INT)
		);

        if ( ! $query)
        {
			$query = $_pdo->exec('DELETE FROM [user_field_cats] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);

			$row = $_pdo->exec('UPDATE [user_field_cats] SET `order` = `order`-1 WHERE `order` > :order',
					array(':order', $_request->get('order')->isNum(TRUE), PDO::PARAM_INT)
			);

        	if ($query)
			{
				$_system->clearCache('profiles');
				$_log->insertSuccess('delete', __('Category has been deleted.'));
				$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
			}

			$_log->insertFail('delelete', __('Error! Category has nor been deleted.'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
        }
		else
		{
			throw new userException(__('Error! Category has not been deleted.').__(' - <small>There are User Fields linked to this category.</small>'));
		}
    }
    elseif ($_request->post('save')->show())
    {
        $cat_name = $_request->post('cat_name')->filters('trim', 'strip');
        if ($cat_name)
        {
            if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
            {
				$query = $_pdo->exec('UPDATE [user_field_cats] SET `name` = :name WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':name', $cat_name, PDO::PARAM_STR)
					)
				);

				if ($query)
				{
					$_system->clearCache('profiles');
					$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
				}

				$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'error'));
            }
            else
            {
				$query = $_pdo->exec('INSERT INTO [user_field_cats] (`name`) VALUES (:name)',
					array(
						array(':name', $cat_name, PDO::PARAM_STR)
					)
				);

	            if ($query)
				{
					$_system->clearCache('profiles');
					$_log->insertSuccess('add', __('Category has been added.'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
				}
				$_log->insertFail('add', __('Error! Category has nor been added.'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
            }
        }
        else
        {
            $_request->redirect(FILE_SELF);
        }
    }
    elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
    {
		$query = $_pdo->getRow('SELECT `id`, `name`, `order` FROM [user_field_cats] WHERE `id` = :id',
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

		if ($query)
		{
			$cat_name = $query['name'];
            $cat_order = $query['order'];
        }
    }

    $_tpl->assign('data', $_pdo->getData('SELECT `id`, `name`, `order` FROM [user_field_cats] ORDER by `order`'));

	$_tpl->assign('cat_name', $cat_name);
	$_tpl->assign('cat_order', $cat_order);

    $_tpl->template('user_field_cats');

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
