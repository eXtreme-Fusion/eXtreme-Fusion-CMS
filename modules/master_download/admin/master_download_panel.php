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
	require_once DIR_MODULES.'master_download'.DS.'system'.DS.'config.php';
	$_locale->moduleLoad('admin', 'master_download');

	if ( ! $_user->hasPermission('module.master_download_panel.admin'))
	{
		throw new userException(__('Access denied'));
	}

    $_tpl = new AdminModuleIframe('master_download');
	
	include DIR_MODULES.'master_download'.DS.'config.php';
	
	$_tpl->assign('config', $mod_info);

	// Wyœwietlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyœwietli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add_file' => array(
					__('The file has been added.'), __('Error! The file has not been added.')
				),
				'edit_file' => array(
					__('The file has been edited.'), __('Error! The file has not been edited.')
				),
				'del_file' => array(
					__('The file has been deleted.'), __('Error! The file has not been deleted.')
				),
				'add_cat' => array(
					__('The category has been added.'), __('Error! The category has not been added.')
				),
				'edit_cat' => array(
					__('The category has been edited.'), __('Error! The category has not been edited.')
				),
				'del_cat' => array(
					__('The category has been deleted.'), __('Error! The category has not been deleted.')
				),
				'add_subcat' => array(
					__('The subcategory has been added.'), __('Error! The subcategory has not been added.')
				),
				'edit_subcat' => array(
					__('The subcategory has been edited.'), __('Error! The subcategory has not been edited.')
				),
				'del_subcat' => array(
					__('The subcategory has been deleted.'), __('Error! The subcategory has not been deleted.')
				)
			)
		);
	}

	if ($_request->get('page')->show() === 'mdp_files')
	{
		$query = $_pdo->getData('SELECT `id` FROM [master_download_subcats] ORDER BY `id`');
	
		if ($_pdo->getRowsCount($query))
		{
			$_tpl->assign('mdp_files', TRUE);
			
			if ($_request->get('action')->show() === 'delete' && $_request->get('file_id')->isNum())
			{
				$count = $_pdo->exec('DELETE FROM [master_download_files] WHERE `id` = :id',
					array(
						array(':id', $_request->get('file_id')->isNum(), PDO::PARAM_INT)
					)
				);
				
				if ($count)
				{
					$_log->insertSuccess('delete', __('The file has been deleted.'));
					$_request->redirect(FILE_PATH, array('page' => 'mdp_files', 'act' => 'del_file', 'status' => 'ok'));
				}
	
				$_log->insertFail('delete', __('Error! The file has not been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'mdp_files', 'act' => 'del_file', 'status' => 'error'));
			}
			elseif ($_request->post('add_file')->show())
			{
				$file_name = $_request->post('file_name')->filters('trim', 'strip');
				$file_desc = $_request->post('file_desc')->filters('trim', 'strip');
				$file_subcat = $_request->post('file_subcat')->isNum(TRUE);
				$file_url = $_request->post('file_url')->filters('trim', 'strip');
				$file_img = $_request->post('file_img')->filters('trim', 'strip');
				$file_size = $_request->post('file_size')->filters('trim', 'strip');
				$file_mirror = $_request->post('file_mirror')->filters('trim', 'strip');
				$file_mirror = serialize(explode("\n", $file_mirror));
				
				if ($file_name && $file_desc && $file_url)
				{
					if ($_request->get('action')->show() === 'edit' && $_request->get('file_id')->isNum())
					{
						$count = $_pdo->exec('UPDATE [master_download_files] SET `name` = :name, `desc` = :desc, `subcat` = :subcat, `url` = :url, `img` = :img, `size` = :size, `date` = '.time().', `mirror` = :mirror WHERE `id` = :id',
							array(
								array(':id', $_request->get('file_id')->isNum(), PDO::PARAM_INT),
								array(':name', $file_name, PDO::PARAM_STR),
								array(':desc', $file_desc, PDO::PARAM_STR),
								array(':subcat', $file_subcat, PDO::PARAM_INT),
								array(':url', $file_url, PDO::PARAM_STR),
								array(':img', $file_img, PDO::PARAM_STR),
								array(':size', $file_size, PDO::PARAM_INT),
								array(':mirror', $file_mirror, PDO::PARAM_STR)
							)
						);
						
						if ($count)
						{
							$_request->redirect(FILE_PATH, array('page' => 'mdp_files', 'act' => 'edit_file', 'status' => 'ok'));
						}

						$_request->redirect(FILE_PATH, array('page' => 'mdp_files', 'act' => 'edit_file', 'status' => 'error'));
					}
					else
					{
						$count = $_pdo->exec('INSERT INTO [master_download_files] (`name`, `desc`, `subcat`, `url`, `img`, `size`, `date`, `mirror`) VALUES (:name, :desc, :subcat, :url, :img, :size, '.time().', :mirror)',
							array(
								array(':name', $file_name, PDO::PARAM_STR),
								array(':desc', $file_desc, PDO::PARAM_STR),
								array(':subcat', $file_subcat, PDO::PARAM_INT),
								array(':url', $file_url, PDO::PARAM_STR),
								array(':img', $file_img, PDO::PARAM_STR),
								array(':size', $file_size, PDO::PARAM_INT),
								array(':mirror', $file_mirror, PDO::PARAM_STR)
							)
						);
			
						if ($count)
						{
							$_log->insertSuccess('add', __('The file has been added.'));
							$_request->redirect(FILE_PATH, array('page' => 'mdp_files', 'act' => 'add_file', 'status' => 'ok'));
						}
			
						$_log->insertFail('add', __('Error! The file has not been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'mdp_files', 'act' => 'add_file', 'status' => 'error'));
					}
				}
				else
				{
					$_request->redirect(FILE_SELF);
				}
			}
			elseif ($_request->get('action')->show() === 'edit' && $_request->get('file_id')->isNum())
			{
				$data = $_pdo->getRow('SELECT `name`, `desc`, `subcat`, `url`, `img`, `size`, `mirror` FROM [master_download_files] WHERE `id` = :id',
					array(
						array(':id', $_request->get('file_id')->isNum(), PDO::PARAM_INT)
					)
				);
				
				if($data)
				{
					$name = $data['name'];
					$desc = $data['desc'];
					$url = $data['url'];
					$img = $data['img'];
					$size = $data['size'];
					$subcat = $data['subcat'];
					$mirror = implode("\n", unserialize($data['mirror']));
				}
				else
				{
					throw new userException(__('Error! There is no ID :id for the table master_download_files.', array(':id' => $_request->get('file_id')->isNum())));
				}
			}
			else
			{
				$name = '';
				$desc = '';
				$subcat = '';
				$url = '';
				$img = '';
				$size = '';
				$mirror = '';
			}

			$cat_list = array(); $query = $_pdo->getData('SELECT `id`, `name` FROM [master_download_subcats] ORDER BY `name` ASC');
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					$cat_list[] = array(
						'entry' => $row['name'],
						'value' => $row['id']
					);
				}
			}

			$_tpl->assignGroup(array(
				'name' => $name,
				'desc' => $desc,
				'url' => $url,
				'img' => $img,
				'size' => $size,
				'mirror' => $mirror,
				'cat_list' => $cat_list,
				'subcat' => $subcat
			));
			
			$_GET['current'] = intval($_request->get('current')->show() ? $_request->get('current')->show() : 1);
			
			$rows = $_pdo->getMatchRowsCount('SELECT * FROM [master_download_files] ORDER BY `id` ASC');
			
			if ($rows)
			{
				$per_page = 20;
				$rowstart = $_GET['current'] ? PAGING::getRowStart($_GET['current'], intval($per_page)) : 0;
				
				$query = $_pdo->getData('
					SELECT f.`id`, f.`name`, f.`desc`, f.`size`, f.`subcat`, f.`date`, s.`name` AS `sub_name`
					FROM [master_download_files] f
					LEFT JOIN [master_download_subcats] s
					ON s.`id` = f.`subcat`
					ORDER BY f.`date` DESC
					LIMIT :rowstart,:items_per_page',
					array(
						array(':rowstart', $rowstart, PDO::PARAM_INT),
						array(':items_per_page', $per_page, PDO::PARAM_INT)
					)
				);

				if ($_pdo->getRowsCount($query))
				{
					$i = 0; $data = array();
					foreach($query as $row)
					{
						$data[] = array(
							'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
							'id' => $row['id'],
							'name' => $row['name'],
							'desc' => $row['desc'],
							'cat' => $row['sub_name'],
							'size' => $row['size'],
							'date' => HELP::showDate('shortdate', $row['date'])
						);
						$i++;
					}
					$_tpl->assign('file', $data);
				}
				
				$_pagenav = new PageNav(new Paging($rows, $_GET['current'], intval($per_page)), $_tpl, 10, array('page=mdp_files', 'current=', FALSE));
				
				$_pagenav->get($_pagenav->create(), 'page_nav', DIR_ADMIN_TEMPLATES.'paging'.DS);
			}
		}
		else
		{
			$_tpl->assign('mdp_error_files', TRUE);
		}
	}
	if ($_request->get('page')->show() === 'mdp_cats')
	{
		$_tpl->assign('mdp_cats', TRUE);
		
		if ($_request->get('action')->show() === 'delete' && $_request->get('cat_id')->isNum())
		{
			$query = $_pdo->getData('SELECT `id` FROM [master_download_subcats] WHERE `cat` = '.$_request->get('cat_id')->show());
			if ( ! $_pdo->getRowsCount($query))
			{
				$count = $_pdo->exec('DELETE FROM [master_download_cats] WHERE `id` = :id',
					array(
						array(':id', $_request->get('cat_id')->show(), PDO::PARAM_INT)
					)
				);
			
				if ($count)
				{
					$_log->insertSuccess('delete', __('The category has been deleted.'));
					$_request->redirect(FILE_PATH, array('page' => 'mdp_cats', 'act' => 'del_cat', 'status' => 'ok'));
				}
				$_log->insertFail('delete', __('Error! The category has not been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'mdp_cats', 'act' => 'del_cat', 'status' => 'error'));
			}
			else
			{
				$_tpl->assign('mdp_cats_error', TRUE);
				$name = '';
				$desc = '';
			}
		}
		elseif ($_request->post('add_cat')->show())
		{
			$cat_name = $_request->post('cat_name')->filters('trim', 'strip');
			$cat_desc = $_request->post('cat_desc')->filters('trim', 'strip');
			if ($cat_name)
			{
				if ($_request->get('action')->show() === 'edit' && $_request->get('cat_id')->isNum())
				{
					$count = $_pdo->exec('UPDATE [master_download_cats] SET `name` = :name, `desc` = :desc WHERE `id` = :id',
						array(
							array(':id', $_request->get('cat_id')->show(), PDO::PARAM_INT),
							array(':name', $cat_name, PDO::PARAM_STR),
							array(':desc', $cat_desc, PDO::PARAM_STR)
						)
					);
					
					if ($count)
					{
						$_request->redirect(FILE_PATH, array('page' => 'mdp_cats', 'act' => 'edit_cat', 'status' => 'ok'));
					}
					$_request->redirect(FILE_PATH, array('page' => 'mdp_cats', 'act' => 'edit_cat', 'status' => 'error'));
				}
				else
				{
					$count = $_pdo->exec('INSERT INTO [master_download_cats] (`name`, `desc`) VALUES (:name, :desc)',
						array(
							array(':name', $cat_name, PDO::PARAM_STR),
							array(':desc', $cat_desc, PDO::PARAM_STR)
						)
					);
					
					if ($count)
					{
						$_log->insertSuccess('add', __('The category has been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'mdp_cats', 'act' => 'add_cat', 'status' => 'ok'));
					}
					$_log->insertFail('add', __('Error! The category has not been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'mdp_cats', 'act' => 'add_cat', 'status' => 'error'));
				}
			}
			else
			{
				$_request->redirect(FILE_SELF);
			}
		}
		elseif ($_request->get('action')->show() === 'edit' && $_request->get('cat_id')->isNum())
		{
			$data = $_pdo->getRow('SELECT `name`, `desc` FROM [master_download_cats] WHERE `id` = :id',
				array(
					array(':id', $_request->get('cat_id')->show(), PDO::PARAM_INT)
				)
			);
			
			if($data)
			{
				$name = $data['name'];
				$desc = $data['desc'];
			}
		}
		else
		{
			$name = '';
			$desc = '';
		}
		
		$_tpl->assignGroup(array(
			'name' => $name,
			'desc' => $desc
		));
		
		$query = $_pdo->getData('
			SELECT `id`, `name`, `desc`
			FROM [master_download_cats]
			ORDER BY `id`
		');

		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $data = array();
			foreach($query as $row)
			{
				$data[] = array(
					'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'id' => $row['id'],
					'name' => $row['name'],
					'desc' => $row['desc']
				);
				$i++;
			}
			$_tpl->assign('cat', $data);
		}
	}
	
	if ($_request->get('page')->show() === 'mdp_subcats')
	{
		$query = $_pdo->getData('SELECT `id` FROM [master_download_cats] ORDER BY `id`');
	
		if ($_pdo->getRowsCount($query))
		{
			$_tpl->assign('mdp_sub_cats', TRUE);
			
			if ($_request->get('action')->show() === 'delete' && $_request->get('subcat_id')->isNum())
			{
				$query = $_pdo->getData('SELECT `id` FROM [master_download_files] WHERE `subcat` = '.$_request->get('subcat_id')->show());
				if ( ! $_pdo->getRowsCount($query))
				{
					$count = $_pdo->exec('DELETE FROM [master_download_subcats] WHERE `id` = :id',
						array(
							array(':id', $_request->get('subcat_id')->show(), PDO::PARAM_INT)
						)
					);
				
					if ($count)
					{
						$_log->insertSuccess('delete', __('The subcategory has been deleted.'));
						$_request->redirect(FILE_PATH, array('page' => 'mdp_subcats', 'act' => 'del_subcat', 'status' => 'ok'));
					}
					$_log->insertFail('delete', __('Error! The subcategory has not been deleted.'));
					$_request->redirect(FILE_PATH, array('page' => 'mdp_subcats', 'act' => 'del_subcat', 'status' => 'error'));
				}
				else
				{
					$_tpl->assign('mdp_sub_cats_error', TRUE);
					$name = '';
					$desc = '';
					$cat = '';
					$access_view = '';
					$access_get = '';
				}
			}
			elseif ($_request->post('add_sub_cat')->show())
			{
				$sub_name = $_request->post('name')->filters('trim', 'strip');
				$sub_desc = $_request->post('desc')->filters('trim', 'strip');
				$sub_cat = $_request->post('cat')->isNum(TRUE);
				$sub_view_access =  $_request->post('view_access')->show() ? $_request->post('view_access')->getNumArray() : array(0 => '3');
				$sub_get_access =  $_request->post('get_access')->show() ? $_request->post('get_access')->getNumArray() : array(0 => '2');
				if ($sub_name && $sub_cat)
				{
					if ($_request->get('action')->show() === 'edit' && $_request->get('subcat_id')->isNum())
					{
						$count = $_pdo->exec('UPDATE [master_download_subcats] SET `name` = :name, `desc` = :desc, `cat` = :cat, `viewaccess` = :viewaccess, `getaccess` = :getaccess WHERE `id` = :id',
							array(
								array(':id', $_request->get('subcat_id')->show(), PDO::PARAM_INT),
								array(':name', $sub_name, PDO::PARAM_STR),
								array(':desc', $sub_desc, PDO::PARAM_STR),
								array(':cat', $sub_cat, PDO::PARAM_INT),
								array(':viewaccess', HELP::implode($sub_view_access), PDO::PARAM_STR),
								array(':getaccess', HELP::implode($sub_get_access), PDO::PARAM_STR)
							)
						);
						
						if ($count)
						{
							$_request->redirect(FILE_PATH, array('page' => 'mdp_subcats', 'act' => 'edit_subcat', 'status' => 'ok'));
						}
						$_request->redirect(FILE_PATH, array('page' => 'mdp_subcats', 'act' => 'edit_subcat', 'status' => 'error'));
					}
					else
					{
						$count = $_pdo->exec('INSERT INTO [master_download_subcats] (`name`, `desc`, `cat`, `viewaccess`, `getaccess`) VALUES (:name, :desc, :cat, :viewaccess, :getaccess)',
							array(
								array(':name', $sub_name, PDO::PARAM_STR),
								array(':desc', $sub_desc, PDO::PARAM_STR),
								array(':cat', $sub_cat, PDO::PARAM_INT),
								array(':viewaccess', HELP::implode($sub_view_access), PDO::PARAM_INT),
								array(':getaccess', HELP::implode($sub_get_access), PDO::PARAM_INT)
							)
						);
						
						if ($count)
						{
							$_log->insertSuccess('add', __('The subcategory has been added.'));
							$_request->redirect(FILE_PATH, array('page' => 'mdp_subcats', 'act' => 'add_subcat', 'status' => 'ok'));
						}
						$_log->insertFail('add', __('Error! The subcategory has not been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'mdp_subcats', 'act' => 'add_subcat', 'status' => 'error'));
					}
				}
				else
				{
					$_request->redirect(FILE_SELF);
				}
			}
			elseif ($_request->get('action')->show() === 'edit' && $_request->get('subcat_id')->isNum())
			{
				$data = $_pdo->getRow('SELECT `name`, `desc`, `cat`, `viewaccess`, `getaccess` FROM [master_download_subcats] WHERE `id` = :id',
					array(
						array(':id', $_request->get('subcat_id')->show(), PDO::PARAM_INT)
					)
				);
				
				if($data)
				{
					$name = $data['name'];
					$desc = $data['desc'];
					$cat = $data['cat'];
					$access_view = $data['viewaccess'];
					$access_get = $data['getaccess'];
				}
			}
			else
			{
				$name = '';
				$desc = '';
				$cat = '';
				$access_view = '';
				$access_get = '';
			}

			$cat_list = array(); $query = $_pdo->getData('SELECT `id`, `name` FROM [master_download_cats] ORDER BY `name` ASC');
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					$cat_list[] = array(
						'entry' => $row['name'],
						'value' => $row['id']
					);
				}
			}

			$_tpl->assignGroup(array(
				'name' => $name,
				'desc' => $desc,
				'cat_list' => $cat_list,
				'cat' => $cat,
				'access_view' => $_tpl->getMultiSelect($_user->getViewGroups(), $access_view, TRUE),
				'access_get' => $_tpl->getMultiSelect($_user->getViewGroups(), $access_get, TRUE)
			));
			
			$query = $_pdo->getData('
				SELECT s.`id`, s.`name`, s.`desc`, s.`cat`, s.`viewaccess`, s.`getaccess`, c.`name` AS `cat_name`
				FROM [master_download_subcats] s
				LEFT JOIN [master_download_cats] c
				ON c.`id` = s.`cat`
				ORDER BY s.`id`
			');
	
			if ($_pdo->getRowsCount($query))
			{
				$i = 0; $data = array();
				foreach($query as $row)
				{
					$data[] = array(
						'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
						'id' => $row['id'],
						'name' => $row['name'],
						'desc' => $row['desc'],
						'cat' => $row['cat_name'],
						'view_access' => $_user->getRoleName($row['viewaccess']),
						'get_access' => $_user->getRoleName($row['getaccess'])
					);
					$i++;
				}
				$_tpl->assign('subcat', $data);
			}
		}
		else
		{
			$_tpl->assign('mdp_error_sub_cats', TRUE);
		}
	}
	
	if ($_request->get('page')->show() === 'mdp_info')
	{
		$_tpl->assign('mdp_info', TRUE);
	}
	
	if (! $_request->get('page')->show())
	{
		$_request->redirect(FILE_PATH, array('page' => 'mdp_files'));
	}

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