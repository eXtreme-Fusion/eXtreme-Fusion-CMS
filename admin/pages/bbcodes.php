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
| Author: Wooya
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
	
	$_locale->load('bbcodes');
	$_locale->setSubDir('bbcodes');
	
    if ( ! $_user->hasPermission('admin.bbcodes'))
	{
		throw new userException(__('Access denied'));
	}

	$_fav->setFavByLink('bbcodes.php', $_user->get('id'));
	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'delete' => array(
					__('Tag has been deactivated'), __('Error while deactivating')
				),
				'add' => array(
					__('Tag has been activated'), __('Error while activating')
				)
			)
		);
    }

	if ($_request->get('action')->show() === 'unactive' && $_request->get('id')->isNum())
	{
		$query = $_pdo->getData('SELECT `order` FROM [bbcodes] WHERE `id`= '.$_request->get('id')->show());

		if ($_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				$_pdo->exec('UPDATE [bbcodes] SET `order`=`order`-1 WHERE `order` > :order',
					array(':order', $row['order'], PDO::PARAM_INT)
				);
			}
		}

		$count = $_pdo->exec('DELETE FROM [bbcodes] WHERE `id` = :id',
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

		if ($count)
		{
			$_log->insertSuccess('delete', __('Tag has been deactivated'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}

		$_log->insertFail('delete', __('Error while deactivating'));
		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
	}
	elseif ($_request->get('action')->show() === 'active' && $_request->get('name')->show() && file_exists(DIR_SYSTEM.DS.'bbcodes'.DS.$_request->get('name')->show().'.php'))
	{
		if (substr($_request->get('name')->show(), 0, 1) != '!')
		{
			$query = $_pdo->getData('SELECT `order` FROM [bbcodes]');
			if ($_pdo->getRowsCount($query))
			{
				foreach ($query as $row)
				{
					$order = ($row['order'] == 0 ? 1 : $row['order'] + 1);
				}
			}
			else
			{
				$order = 1;
			}

			$count = $_pdo->exec('INSERT INTO [bbcodes] (`name`, `order`) VALUES (:name, :order)',
				array(
					array(':name', $_request->get('name')->show(), PDO::PARAM_STR),
					array(':order', $order, PDO::PARAM_INT)
				)
			);

			if ($count)
			{
				$_log->insertSuccess('add', __('Tag has been activated'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
			}

			$_log->insertFail('add', __('Error while activating'));
			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
		}
		else
		{
			$query = $_pdo->getMatchRowsCount('SELECT `id` FROM [bbcodes]');
			
			if ($query)
			{
				$_pdo->exec('UPDATE [bbcodes] SET `order`=`order`+1');
			}

			$count = $_pdo->exec('INSERT INTO [bbcodes] (`name`, `order`) VALUES (:name, 1)',
				array(':name', $_request->get('name')->show(), PDO::PARAM_STR)
			);

			if ($count)
			{
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
			}

			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
		}
	}

	$available_bbcodes = array();
	$bbcode_used = '';

	if ($handle_bbcodes = opendir(DIR_SYSTEM.DS.'bbcodes'.DS))
	{
		while (FALSE !== ($file_bbcodes = readdir($handle_bbcodes)))
		{
			if ( ! in_array($file_bbcodes, array('..', '.', 'index.php')) && ! is_dir(DIR_SYSTEM.DS.'bbcodes'.DS.$file_bbcodes))
			{
				if ( ! preg_match('/_save.php/i', $file_bbcodes) && ! preg_match('/.js/i', $file_bbcodes))
				{
					$bbcode_name = explode('.', $file_bbcodes);
					$available_bbcodes[] = $bbcode_name[0];
					unset($bbcode_name);
				}
			}
		}
		closedir($handle_bbcodes);
	}

	sort($available_bbcodes); $enabled_bbcodes = array(); $active_bbcodes = array();

	$query = $_pdo->getData('SELECT `id`, `name` FROM [bbcodes] ORDER by `order`');
	if ($_pdo->getRowsCount($query))
	{
		foreach ($query as $row)
		{
			$active_bbcodes[] = $row['name'];
			
			if (file_exists(DIR_SYSTEM.DS.'bbcodes'.DS.$row['name'].'.php'))
			{
				require DIR_SYSTEM.DS.'bbcodes'.DS.$row['name'].'.php';
			}
		
			if (file_exists(DIR_IMAGES.DS.'bbcodes'.DS.$row['name'].'.png'))
			{
				$bbcode_info['image'] = 1;
			}
			else if (file_exists(DIR_IMAGES.DS.'bbcodes'.DS.$row['name'].'.gif'))
			{
				$bbcode_info['image'] = 2;
			}
			else if (file_exists(DIR_IMAGES.DS.'bbcodes'.DS.$row['name'].'.jpg'))
			{
				$bbcode_info['image'] = 3;
			}
			else
			{
				$bbcode_info['image'] = NULL;
			}
			
			$row['image'] = $bbcode_info['image'];
			$row['name'] = $bbcode_info['name'];
			$row['description'] = $bbcode_info['description'];
			$row['row'] = $bbcode_info['value'];
			
			$bbcode_active[] = $row;
			
			unset($row);
		}
		$_tpl->assign('bbcode_active', $bbcode_active);
	}
	if (is_array($available_bbcodes))
	{
		$bbcode_inactive = array(); $i = 0;
		foreach($available_bbcodes as $row)
		{
			if( ! in_array($row, $active_bbcodes))
			{
				if (file_exists(DIR_SYSTEM.DS.'bbcodes'.DS.$row.'.php'))
				{
					require DIR_SYSTEM.DS.'bbcodes'.DS.$row.'.php';
				}
			
				if (file_exists(DIR_IMAGES.DS.'bbcodes'.DS.$row.'.png'))
				{
					$bbcode_info['image'] = 1;
				}
				else if (file_exists(DIR_IMAGES.DS.'bbcodes'.DS.$row.'.gif'))
				{
					$bbcode_info['image'] = 2;
				}
				else if (file_exists(DIR_IMAGES.DS.'bbcodes'.DS.$row.'.jpg'))
				{
					$bbcode_info['image'] = 3;
				}
				else
				{
					$bbcode_info['image'] = NULL;
				}
				
				unset($row);
				
				$row['row_color'] = $i % 2 == 0 ? 'tbl1' : 'tbl2';
				$row['image'] = $bbcode_info['image'];
				$row['name'] = $bbcode_info['name'];
				$row['description'] = $bbcode_info['description'];
				$row['row'] = $bbcode_info['value'];

				$bbcode_inactive[] = $row;
				
				$i++;
			}
		}
		$_tpl->assign('bbcode_inactive', $bbcode_inactive);
	}
	$_tpl->template('bbcodes');
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
