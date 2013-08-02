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

	$_locale->load('groups');

    if ( ! $_user->hasPermission('admin.groups'))
    {
        throw new userException(__('Access denied'));
    }

	$_fav->setFavByLink('groups.php', $_user->get('id'));
	$_tpl = new Iframe;

	// Wyświetlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'add' => array(
					__('Group has been added.'), __('Error! Group has not been added.')
				),
				'edit' => array(
					__('Group has been edited.'), __('Error! Group has not been edited.')
				),
				'delete' => array(
					__('Group has been deleted.'), __('Error! Group has not been deleted.')
				)
			)
		);
    }

	if ($_request->get('action')->show())
	{
		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			if ($_request->post('save')->show())
			{
				$title = $_request->post('title')->filters('trim', 'strip');
				$description = $_request->post('description')->filters('trim', 'strip');
				$format = stripslashes($_request->post('format')->show());
				$team = $_request->post('team')->isNum(TRUE);

				$count = $_pdo->exec('UPDATE [groups] SET `title` = :title, `description` = :description, `format` = :format, `team` = :team WHERE `id` = '.$_request->get('id')->show(),
					array(
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':format', $format, PDO::PARAM_STR),
						array(':team', $team, PDO::PARAM_INT)
					)
				);


				if ($count && ( ! in_array($_request->get('id')->show(), $_user->groups) || $_request->get('id')->show() > 1))
				{
					unset($count);

					if ($_request->post('permissions')->show())
					{
						$permissions = array_keys($_request->post('permissions')->show());
						$permissions = in_array('all', $permissions) ? array('*') : $permissions;
					}
					else
					{
						$permissions = array();
					}

					$count = $_pdo->exec('UPDATE [groups] SET `permissions` = :permissions WHERE `id` = '.$_request->get('id')->show(),
						array(':permissions', serialize($permissions), PDO::PARAM_STR)
					);

					unset($permissions);
				}

				$_system->clearCache();

				if ($count)
				{
					$_log->insertSuccess('edit', __('Group has been edited.'));
					$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
				}

				$_log->insertFail('edit', __('Error! Group has not been edited.'));
				$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'error'));
			}

			$group = $_pdo->getRow('SELECT * FROM [groups] WHERE `id` = '.$_request->get('id')->show());

			$_tpl->assign('permission', unserialize($group['permissions']));

			$_tpl->assign('group', array(
				'id' => $group['id'],
				'title' => $group['title'],
				'description' => $group['description'],
				'format' => htmlspecialchars($group['format']),
				'team' => $group['team'],
				'is_system' => in_array($group['id'], $_user->groups),
			));

			$query = $_pdo->getData('SELECT * FROM [permissions_sections]');

			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $section)
				{
					$sections[$section['id']] = $section['description'];
				}
			}
			else
			{
				throw new userException(__('There are no records in permissions_sections table.'));
			}

			$query = $_pdo->getData('SELECT * FROM [permissions]');

			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $permission)
				{
					$permissions[$permission['section']][] = array(
						'id' => $permission['id'],
						'name' => $permission['name'],
						'description' => $permission['description'],
					);
				}
			}
			else
			{
				throw new userException(__('There are no records in permissions table.'));
			}

			$_tpl->assign('sections', $sections);
			$_tpl->assign('permissions', $permissions);

			$_tpl->template('group');
		}
		elseif ($_request->get('action')->show() === 'add')
		{
			if ($_request->post('save')->show())
			{
				$title = $_request->post('title')->filters('trim', 'strip');
				$description = $_request->post('description')->filters('trim', 'strip');
				$format = $_request->post('format')->filters('trim', 'html_decode');
				$team = $_request->post('team')->isNum(TRUE);

				if ($_request->post('permissions')->show())
				{
					$permissions = array_keys($_request->post('permissions')->show());
					$permissions = in_array('all', $permissions) ? array('*') : $permissions;
				}
				else
				{
					$permissions = array();
				}

				$count = $_pdo->exec('INSERT INTO [groups] (`title`, `description`, `format`, `permissions`, `team`) VALUES (:title, :description, :format, :permissions, :team)',
					array(
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':format', $format, PDO::PARAM_STR),
						array(':permissions', serialize($permissions), PDO::PARAM_STR),
						array(':team', $team, PDO::PARAM_INT)
					)
				);

				unset($permissions);

				$_system->clearCache();

				if ($count)
				{
					$_log->insertSuccess('add', __('Group has been added.'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
				}

				$_log->insertFail('add', __('Error! Group has not been added.'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
			}

			$query = $_pdo->getData('SELECT * FROM [permissions_sections]');

			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $section)
				{
					$sections[$section['id']] = $section['description'];
				}
			}
			else
			{
				throw new userException(__('There are no records in permissions_sections table.'));
			}

			$query = $_pdo->getData('SELECT * FROM [permissions]');

			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $permission)
				{
					$permissions[$permission['section']][] = array(
						'id' => $permission['id'],
						'name' => $permission['name'],
						'description' => $permission['description'],
					);
				}
			}
			else
			{
				throw new userException(__('There are no records in permissions table.'));
			}

			$_tpl->assign('group', array('format' => '{username}'));
			$_tpl->assign('sections', $sections);
			$_tpl->assign('permissions', $permissions);

			$_tpl->template('group');
		}
		elseif ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
		{
			if (in_array($_request->get('id')->show(), $_user->groups))
				throw new userException(__('You can not delete system group.'));

			$count = $_pdo->exec('DELETE FROM [groups] WHERE `id` = '.$_request->get('id')->show());
			
			$_pdo->exec('UPDATE [users] SET `role` = 2 WHERE `role` = '.$_request->get('id')->show());
			
			$_system->clearCache();

			if ($count)
			{
				$_log->insertSuccess('delete', __('Group has been deleted.'));
				$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
			}

			$_log->insertFail('delete', __('Error! Group has not been deleted.'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
		}
	}
	else
	{
		$query = $_pdo->getData('SELECT * FROM [groups]');

		if ($_pdo->getRowsCount($query))
		{
			
	        foreach($query as $group)
	        {
				$row['id'] = $group['id'];
				$row['title'] = str_replace('{username}', $group['title'], $group['format']);
				$row['description'] = $group['description'];
				$row['format'] = $_user->getUsername($_user->get('id'), $group['format']);
				$row['team'] = $group['team'];
				$row['is_system'] = in_array($group['id'], $_user->groups);

				$groups[] = $row;

				unset($row);
			}
			$_tpl->assign('groups', $groups);
		}

		$_tpl->template('groups');
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
