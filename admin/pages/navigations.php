<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/
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

	// Wyœwietlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyœwietli komunikat
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
			if ($name && $url) 
			{
				if (($_request->get('action')->show() === 'edit') && $_request->get('id')->isNum()) 
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
						SET `name` = :name, `url` = :url, `visibility` = :visibility, `position` = :position, `window` = :window, `order` = :order
						WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':name', $name, PDO::PARAM_STR),
							array(':url', $url, PDO::PARAM_STR),
							array(':visibility', HELP::implode($visibility), PDO::PARAM_STR),
							array(':position', $position, PDO::PARAM_INT),
							array(':window', $window, PDO::PARAM_STR),
							array(':order', $order, PDO::PARAM_INT)
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
					
					$count = $_pdo->exec('INSERT INTO [navigation] (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES (:name, :url, :visibility, :position, :window, :order)',
						array(
							array(':name', $name, PDO::PARAM_STR),
							array(':url', $url, PDO::PARAM_STR),
							array(':visibility', HELP::implode($visibility), PDO::PARAM_STR),
							array(':position', $position, PDO::PARAM_INT),
							array(':window', $window, PDO::PARAM_STR),
							array(':order', $order, PDO::PARAM_INT)
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
		if (($_request->get('action')->show() === 'edit') && $_request->get('id')->isNum()) 
		{
			$row = $_pdo->getRow('SELECT * FROM [navigation] WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);
			
			if ($row)
			{
				$name = $row['name'];
				$url = $row['url'];
				$visibility = $row['visibility'];
				$order = $row['order'];
				$position = $row['position'];
				$window = $row['window'];
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
		}

		$_tpl->assignGroup(array(
			'name' => $name,
			'url' => $url,
			'access' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($visibility), TRUE),
			'order' => $order,
			'position' => $position,
			'window' => $window
		));
		
		$query = $_pdo->getData('SELECT * FROM [navigation] ORDER BY `order`');
		
		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $data = array();
			foreach($query as $row)
            {
				if ($_pdo->getRowsCount($query) != 1) 
				{
					$data[] = array(
						'id' => $row['id'],
						'name' => $row['name'],
						'url' => $row['url'],
						'perse_url' => ((strstr($row['url'], "http://") || strstr($row['url'], "https://")) ? TRUE : FALSE),
						'order' => $row['order'],
						'visibility' => $_user->groupArrIDsToNames(HELP::explode($row['visibility'])),
						'position' => $row['position']
					);
				}
				$i++;
			}
			$_tpl->assign('data', $data);
		}

		$_tpl->template('navigations');
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