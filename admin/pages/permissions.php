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

	$_locale->load('permissions');

    if ( ! $_user->hasPermission('admin.permissions'))
    {
        throw new userException(__('Access Denied'));
    }

	$_fav->setFavByLink('permissions.php', $_user->get('id'));
	
	$_tpl = new Iframe;

	if ($_request->get('act')->show() && $_request->get('status')->show() && $_request->get('type')->show())
    {
		if ($_request->get('type')->show() === 'permission')
		{
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), array(
				'add' => array(
					__('Permission has been added.'), __('Error! Permission has not been added.')
				),
				'edit' => array(
					__('Permission has been edited.'), __('Error! Permission has not been edited.')
				),
				'delete' => array(
					__('Permission has been deleted.'), __('Error! Permission has not been deleted.')
				)
			));
		}
		elseif ($_request->get('type')->show() === 'section')
		{
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), array(
				'add' => array(
					__('Section has been added.'), __('Error! Section has not been added.')
				),
				'edit' => array(
					__('Section has been edited.'), __('Error! Section has not been edited.')
				),
				'delete' => array(
					__('Section has been deleted.'), __('Error! Section has not been deleted.')
				)
			));
		}
    }

	$query = $_pdo->getData('SELECT * FROM [permissions_sections]');

	foreach($query as $section)
	{
		$sections[$section['id']] = array(
			'id'    	  => $section['id'],
			'name'		  => $section['name'],
			'description' => $section['description'],
			'is_system'   => $section['is_system']
		);
	}

	$_tpl->assign('sections', $sections);

	if (($_request->get('action')->show()) && $_request->get('type')->show())
	{
		if ($_request->get('type')->show() === 'section')
		{
			$_tpl->assign('type', $_request->get('type')->show());

			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
			{
				if ($_request->post())
				{
					$name = $_request->post('name')->filters('trim', 'strip');
					$description = $_request->post('description')->filters('trim', 'strip');

					$count = $_pdo->exec('UPDATE [permissions_sections] SET `name` = :name, `description` = :description WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':name', $name, PDO::PARAM_STR),
							array(':description', $description, PDO::PARAM_STR)
						)
					);

					$_system->clearCache();

					if ($count)
					{
						$_log->insertSuccess('edit', __('Section has been edited.'));
						$_request->redirect(FILE_PATH, array('act' => 'edit', 'type' => 'section', 'status' => 'ok'));
					}

					$_log->insertFail('edit', __('Error! Section has not been edited.'));
					$_request->redirect(FILE_PATH, array('act' => 'edit', 'type' => 'section', 'status' => 'error'));
				}

				foreach($sections as $key => $section)
				{
					if ($section['id'] === $_request->get('id')->show())
					{
						$_tpl->assign('section', $section);
					}
				}

				$_tpl->template('permission');
			}
			elseif ($_request->get('action')->show() === 'add')
			{
				if ($_request->post())
				{
					$name = $_request->post('name')->filters('trim', 'strip');
					$description = $_request->post('description')->filters('trim', 'strip');

					$count = $_pdo->exec('INSERT INTO [permissions_sections] (`name`, `description`) VALUES (:name, :description)',
						array(
							array(':name', $name, PDO::PARAM_STR),
							array(':description', $description, PDO::PARAM_STR)
						)
					);

					$_system->clearCache();

					if ($count)
					{
						$_log->insertSuccess('add', __('Section has been added.'));
						$_request->redirect(FILE_PATH, array('act' => 'add', 'type' => 'section', 'status' => 'ok'));
					}

					$_log->insertFail('add', __('Error! Section has not been added.'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'type' => 'section', 'status' => 'error'));

				}

				$_tpl->template('permission');
			}
			elseif ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
			{
				$section = $_pdo->getRow('SELECT is_system FROM [permissions_sections] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
					)
				);

				if ($section['is_system'])
				{
					throw new userException(__('You can not delete system section!'));
				}

				$count = $_pdo->exec('DELETE FROM [permissions_sections] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					)
				);

				$count = $_pdo->exec('DELETE FROM [permissions] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					)
				);

				$_user->cleanPerms();

				$_system->clearCache();

				if ($count)
				{
					$_log->insertSuccess('delete', __('Section has been deleted.'));
					$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'section', 'status' => 'ok'));
				}

				$_log->insertFail('delete', __('Error! Section has not been deleted.'));
				$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'section', 'status' => 'error'));
			}
		}
		elseif ($_request->get('type')->show() === 'permission')
		{
			$_tpl->assign('type', $_request->get('type')->show());

			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->show())
			{
				$permission = $_pdo->getRow('SELECT * FROM [permissions] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
					)
				);

				if ($_request->post())
				{
					$description = $_request->post('description')->filters('trim', 'strip');

					$count = $_pdo->exec('UPDATE [permissions] SET `description` = :description WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':description', $description, PDO::PARAM_STR)
						)
					);

					if ( ! $count && ! $permission['is_system'])
					{
						unset($count);

						$name = $_request->post('name')->filters('trim', 'strip');
						$section = $_request->post('section')->filters('trim', 'strip');

						$count = $_pdo->exec('UPDATE [permissions] SET `name` = :name, `section` = :section WHERE `id` = :id',
							array(
								array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
								array(':name', $name, PDO::PARAM_STR),
								array(':section', $section, PDO::PARAM_STR)
							)
						);
					}

					$_system->clearCache();

					if ($count)
					{
						$_log->insertSuccess('edit', __('Permission has been edited.'));
						$_request->redirect(FILE_PATH, array('act' => 'edit', 'type' => 'permission', 'status' => 'ok'));
					}

					$_log->insertFail('edit', __('Error! Permission has not been edited.'));
					$_request->redirect(FILE_PATH, array('act' => 'edit', 'type' => 'permission', 'status' => 'error'));

				}

				$_tpl->assign('permission', $permission);
				$_tpl->template('permission');
			}
			elseif ($_request->get('action')->show() === 'add')
			{
				if ($_request->post())
				{
					$description = $_request->post('description')->filters('trim', 'strip');
					$name = $_request->post('name')->filters('trim', 'strip');
					$section = $_request->post('section')->filters('trim', 'strip');

					$uniqe = $_pdo->getRow('SELECT id FROM [permissions] WHERE `name` = :name',
						array(
							array(':name', $name, PDO::PARAM_STR)
						)
					);

					if ($uniqe)
					{
						throw new userException(__('This permission name is already used.'));
					}

					$count = $_pdo->exec('INSERT INTO [permissions] (`name`, `description`, `section`) VALUES (:name, :description, :section)',
						array(
							array(':name', $name, PDO::PARAM_STR),
							array(':description', $description, PDO::PARAM_STR),
							array(':section', $section, PDO::PARAM_STR)
						)
					);

					$_system->clearCache();

					if ($count)
					{
						$_log->insertSuccess('add', __('Permission has been added.'));
						$_request->redirect(FILE_PATH, array('act' => 'add', 'type' => 'permission', 'status' => 'ok'));
					}

					$_log->insertFail('add', __('Error! Permission has not been added.'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'type' => 'permission', 'status' => 'error'));

				}

				$_tpl->template('permission');
			}
			elseif ($_request->get('action')->show() === 'delete' && $_request->get('id')->show())
			{
				$permission = $_pdo->getRow('SELECT is_system FROM [permissions] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					)
				);

				if ($permission['is_system'] == TRUE)
				{
					throw new userException(__('You can not delete system permission!'));
				}

				$count = $_pdo->exec('DELETE FROM [permissions] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					)
				);

				$_user->cleanPerms();
				$_system->clearCache();

				if ($count)
				{
					$_log->insertSuccess('delete', __('Permission has been deleted.'));
					$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'permission', 'status' => 'ok'));
				}

				$_log->insertFail('delete', __('Error! Permission has not been deleted.'));
				$_request->redirect(FILE_PATH, array('act' => 'delete', 'type' => 'permission', 'status' => 'error'));

			}
		}
	}
	else
	{
		$query = $_pdo->getData('SELECT * FROM [permissions]');
		if ($_pdo->getRowsCount($query))
		{
	        foreach($query as $permission)
	        {
				$permissions[$permission['section']][] = array(
					'id'          => $permission['id'],
					'name'        => $permission['name'],
					'description' => $permission['description'],
					'is_system'   => (bool) $permission['is_system'],
				);
			}
		}

		$_tpl->assign('permissions', $permissions);
		$_tpl->template('permissions');
	}
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
