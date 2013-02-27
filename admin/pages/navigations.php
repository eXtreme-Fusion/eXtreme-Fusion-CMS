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

	$_locale->load('navigations');

    if ( ! $_user->hasPermission('admin.navigations'))
    {
        throw new userException(__('Access Denied'));
    }

	$_tpl = new Iframe;

	// Wyświetlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'add' => array(
					__('Link has been added.'), __('Error! Link has not been added.')
				),
				'edit' => array(
					__('Link has been edited.'), __('Error! Link has not been edited.')
				),
				'delete' => array(
					__('Link has been deleted.'), __('Error! Link has not been deleted.')
				)
			)
		);
    }

	if (!$_system->rewriteAvailable())
	{
		$rewrite_unavailable = TRUE;
	}

	if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
	{
		$row = $_pdo->getRow('SELECT `order` FROM [navigation] WHERE `id` = :id',
			array(
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			)
		);

		$query = $_pdo->exec('UPDATE [navigation] SET `order` = `order`-1 WHERE `order` > :order',
			array(
				array(':order', $row['order'], PDO::PARAM_INT)
			)
		);

		$count = $_pdo->exec('DELETE FROM [navigation] WHERE `id` = :id',
			array(
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			)
		);

		if ($count)
		{
			$_log->insertSuccess('delete', __('Link has been deleted.'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}

		$_log->insertFail('delete', __('Error! Link has not been deleted.'));
		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
	}
	else
	{
		if ($_request->post('save')->show())
		{
			$name = $_request->post('name')->strip();
			$url = $_request->post('url')->strip();
			$visibility = $_request->post('visibility')->show() ? $_request->post('visibility')->getNumArray() : array(0 => '0');
			$position =  $_request->post('position')->isNum(TRUE);
			$window =  $_request->post('window')->isNum(TRUE);
			$order = $_request->post('order')->isNum(TRUE);
			if (isset($rewrite_unavailable))
			{
				$rewrite = $_request->post('rewrite')->isNum(TRUE);
			}
			else
			{
				$rewrite = 1;
			}

			if ($name && $url)
			{
				if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
				{
					$row = $_pdo->getRow('SELECT `order` FROM [navigation] WHERE `id`= :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
						)
					);

					if ($order > $row['order'])
					{
						$query = $_pdo->exec('UPDATE [navigation] SET `order`=`order`-1 WHERE `order` > :old_order AND `order` <= :new_order',
							array(
								array(':new_order', $order, PDO::PARAM_INT),
								array(':old_order', $row['order'], PDO::PARAM_INT)
							)
						);
					}
					elseif ($order < $row['order'])
					{
						$query = $_pdo->exec('UPDATE [navigation] SET `order`=`order`-1  WHERE `order` < :old_order AND `order` >= :new_order',
							array(
								array(':new_order', $order, PDO::PARAM_INT),
								array(':old_order', $row['order'], PDO::PARAM_INT)
							)
						);
					}

					$count = $_pdo->exec('
						UPDATE [navigation]
						SET `name` = :name, `url` = :url, `visibility` = :visibility, `position` = :position, `window` = :window, `order` = :order, `rewrite` = :rewrite
						WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':name', $name, PDO::PARAM_STR),
							array(':url', $url, PDO::PARAM_STR),
							array(':visibility', HELP::implode($visibility), PDO::PARAM_STR),
							array(':position', $position, PDO::PARAM_INT),
							array(':window', $window, PDO::PARAM_STR),
							array(':order', $order, PDO::PARAM_INT),
							array(':rewrite', $rewrite, PDO::PARAM_INT)
						)
					);

					if ($count)
					{
						$_log->insertSuccess('edit', __('Link has been edited.'));
						$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
					}

					$_log->insertFail('edit', __('Error! Link has not been edited.'));
					$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'error'));

				}
				else
				{
					if ( ! $order)
					{
						$order = $_pdo->getMaxValue('SELECT MAX(`order`) FROM [navigation]');
					}

					$query = $_pdo->exec('UPDATE [navigation] SET `order`=`order`+1 WHERE `order`>= :order',
						array(
							array(':order', $order, PDO::PARAM_INT)
						)
					);

					$count = $_pdo->exec('INSERT INTO [navigation] (`name`, `url`, `visibility`, `position`, `window`, `order`, `rewrite`) VALUES (:name, :url, :visibility, :position, :window, :order, :rewrite)',
						array(
							array(':name', $name, PDO::PARAM_STR),
							array(':url', $url, PDO::PARAM_STR),
							array(':visibility', HELP::implode($visibility), PDO::PARAM_STR),
							array(':position', $position, PDO::PARAM_INT),
							array(':window', $window, PDO::PARAM_STR),
							array(':order', $order, PDO::PARAM_INT),
							array(':rewrite', $rewrite, PDO::PARAM_INT)
						)
					);

					if ($count)
					{
						$_log->insertSuccess('add', __('Link has been added.'));
						$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
					}

					$_log->insertSuccess('add', __('Error! Link has not been added.'));
					$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));

				}
			}
			else
			{
				$_request->redirect(FILE_SELF);
			}
		}



		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$row = $_pdo->getRow('SELECT * FROM [navigation] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);

			if ($row)
			{
				$name = $row['name'];
				$url = $row['url'];
				$visibility = $row['visibility'];
				$order = $row['order'];
				$position = $row['position'];
				$window = $row['window'];
				$rewrite = $row['rewrite'];
			}
			else
			{
				$_request->redirect(FILE_SELF);
			}
		}
		else
		{
			$name = '';
			$url = '';
			$visibility = '';
			$order = $_pdo->getMaxValue('SELECT MAX(`order`) FROM [navigation]') + 1;
			$position = '';
			$window = '';
			$rewrite = 1;
		}

		$_tpl->assignGroup(array(
			'name' => $name,
			'url' => $url,
			'access' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($visibility), TRUE),
			'order' => $order,
			'position' => $position,
			'window' => $window,
			'rewrite' => $rewrite
		));

		$query = $_pdo->getData('SELECT * FROM [navigation] ORDER BY `order`');

		if ($query)
		{
			$data = array();
			foreach($query as $row)
			{
				$data[] = array(
					'id' => $row['id'],
					'name' => $row['name'],
					'url' => $row['url'],
					'perse_url' => strstr($row['url'], 'http://') || strstr($row['url'], 'https://') ? TRUE : FALSE,
					'order' => $row['order'],
					'visibility' => $_user->groupArrIDsToNames(HELP::explode($row['visibility'])),
					'position' => $row['position'],
					'rewrite' => $row['rewrite']
				);
			}

			$_tpl->assign('data', $data);
		}
	}

	if (isset($rewrite_unavailable))
	{
		$_tpl->assign('modRewrite_unavailable', TRUE);
	}

	$_tpl->template('navigations');
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
