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
	$_locale->load('user_fields');

    if ( ! $_user->hasPermission('admin.user_fields'))
    {
        throw new userException(__('Access denied'));
    }

	$_fav->setFavByLink('user_fields.php', $_user->get('id'));

	$_tpl = new Iframe;

	if ($_request->get('status')->show() && $_request->get('act')->show())
    {
		$_tpl->logAndShow($_request->get('status')->show(), $_request->get('act')->show(), array(
			'add' => array(__('Field has been added.'), __('Error! Field has not been added.')),
			'edit' => array(__('Field has been edited.'), __('Error! Field has not been edited.')),
			'delete' => array(__('Field has been deleted.'), __('Error! Field has not been deleted.')),
		));
    }
	
    if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
    {
		$data = $_pdo->getRow('SELECT `index` FROM [user_fields] WHERE `id` = :id',
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);
		
		$query = $_pdo->exec('ALTER TABLE [users_data] DROP '.HELP::strip($data['index']));
	
		$query = $_pdo->exec('DELETE FROM [user_fields] WHERE `id` = :id',
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

		if ($query)
		{
			$_system->clearCache('profiles');
			$_log->insertSuccess('delete', __('Field has been deleted.'));
			$_request->redirect(FILE_SELF, array('act' => 'delete', 'status' => 'ok'));
		}
		
		$_log->insertFail('delete', __('Error! Field has not been deleted.'));
		$_request->redirect(FILE_SELF, array('act' => 'delete', 'status' => 'error'));
    }
    elseif ($_request->post('save')->show())
    {
		$name = $_request->post('name')->filters('trim', 'strip');
		$index = $_request->post('index')->filters('trim', 'removeSpecial', 'strip');
		$cat = $_request->post('cat')->isNum(TRUE);
		$type = $_request->post('type')->isNum(TRUE);
		$option = $_request->post('option')->filters('strip');
		
		$option = serialize(HELP::cleanSelectOptions(explode("\n", $option)));
		$register = $_request->post('register', '0')->show();
		$hide = $_request->post('hide', '0')->show();
		$edit = $_request->post('edit', '0')->show();

        if ($index && $name)
        {
            if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
            {
				$query = $_pdo->exec('UPDATE [user_fields] SET `name` = :name, `cat` = :cat, `type` = :type, `option` = :option, `register` = :register, `hide` = :hide, `edit` = :edit WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':name', $name, PDO::PARAM_STR),
						array(':cat', $cat, PDO::PARAM_INT),
						array(':type', $type, PDO::PARAM_STR),
						array(':option', $option, PDO::PARAM_STR),
						array(':register', $register, PDO::PARAM_STR),
						array(':hide', $hide, PDO::PARAM_INT),
						array(':edit', $edit, PDO::PARAM_INT)
					)
				);

				if ($query)
				{
					$_system->clearCache('profiles');
					$_request->redirect(FILE_SELF, array('act' => 'edit', 'status' => 'ok'));
				}

				$_request->redirect(FILE_SELF, array('act' => 'edit', 'status' => 'error'));
            }
            else
            {
				$query = $_pdo->exec('ALTER TABLE [users_data] ADD '.$index.' TEXT NOT NULL');

				$query = $_pdo->exec('
					INSERT INTO [user_fields] (`name`, `index`, `cat`, `type`, `option`, `register`, `hide`, `edit`)
					VALUES (:name, :index, :cat, :type, :option, :register, :hide, :edit)',
						array(
						array(':name', $name, PDO::PARAM_STR),
						array(':index', $index, PDO::PARAM_INT),
						array(':cat', $cat, PDO::PARAM_STR),
						array(':type', $type, PDO::PARAM_STR),
						array(':option', $option, PDO::PARAM_STR),
						array(':register', $register, PDO::PARAM_STR),
						array(':hide', $hide, PDO::PARAM_INT),
						array(':edit', $edit, PDO::PARAM_INT)
						)
				);

				if ($query)
				{
					$_system->clearCache('profiles');
					$_log->insertSuccess('add', __('Field has been added.'));
					$_request->redirect(FILE_SELF, array('act' => 'add', 'status' => 'ok'));
				}
	
				$_log->insertFail('add', __('Error! Field has not been added.'));
				$_request->redirect(FILE_SELF, array('act' => 'add', 'status' => 'error'));
            }
        }
        else
        {
			throw new userException(__('Incorrect data.'));
        }
    }
    elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
    {
		$data = $_pdo->getRow('SELECT * FROM [user_fields] WHERE `id` = :id', 
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

        if ($data) 
		{
            $name = $data['name'];
			$index = $data['index'];
			$cat = $data['cat'];
			$type = $data['type'];
			$option = $data['option'] ==! '' ? implode("\n", unserialize($data['option'])) : NULL;
			$register = $data['register'];
			$hide = $data['hide'];
			$edit = $data['edit'];
        }
    }
    else
    {
        $name = '';
		$index = '';
		$cat = '';
		$type = '';
		$option = '';
		$register = '';
		$hide = '';
		$edit = '';
    }
	
	$cat_list = array(); $query = $_pdo->getData('SELECT `id`, `name` FROM [user_field_cats] ORDER BY `order` ASC');
	if ($_pdo->getRowsCount($query))
	{
		foreach($query as $row)
		{
			$cat_list[$row['id']] = $row['name'];
		}
	}
	
	$type_list[1] = __('Textbox (short)');
	$type_list[2] = __('Textbox (long)');
	$type_list[3] = __('Selection list');
	
    $_tpl->assign('name', $name);
	$_tpl->assign('index', $index);
	$_tpl->assign('cat_list', $_tpl->createSelectOpts($cat_list, intval($cat), TRUE, FALSE));
	$_tpl->assign('type_list', $_tpl->createSelectOpts($type_list, intval($type), TRUE, FALSE));
	$_tpl->assign('option', $option);
	$_tpl->assign('register', $register);
	$_tpl->assign('hide', $hide);
	$_tpl->assign('edit', $edit);

	$query = $_pdo->getData('
			SELECT u.*, c.`name` AS catname FROM [user_fields] u
			LEFT JOIN [user_field_cats] c ON u.`cat`= c.`id`
            ORDER BY `id`
	');

    if ($query)
	{
		$i = 0; $data = array();
		foreach($query as $row)
		{
			switch ($row['type'])
			{
				case 1:
					$type = __('Textbox (short)');
					break;
				case 2:
					$type = __('Textbox (long)');
					break;
				case 3:
					$type = __('Select List');
			}
			$field[] = array(
				'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
				'id' => $row['id'],
				'name' => $row['name'],
				'index' => $row['index'],
				'cat' => $row['catname'],
				'type' => isset($type) ? $type : NULL,
				'register' => $row['register'],
				'hide' => $row['hide'],
				'edit' => $row['edit']
			);
			$i++;
		}
		$_tpl->assign('field', $field);
	}

    $_tpl->template('user_fields');
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
