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
	$_locale->moduleLoad('admin', 'point_system');
	
    if ( ! $_user->hasPermission('module.point_system.admin'))
    {
        throw new userException(__('Access denied'));
    }
	
	$_tpl = new AdminModuleIframe('point_system');
	
	include DIR_MODULES.'point_system'.DS.'config.php';
	
	$_tpl->assign('config', $mod_info);
	
	include DIR_MODULES.'point_system'.DS.'class'.DS.'Points.php';
	$_points = new Points($_pdo, $_system);
	
	if ($_request->get(array('status', 'act'))->show())
	{
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add_points' => array(
					__('Points section has been added.'), __('Error! Points setion has not been added.') 
				),
				'edit_points' => array(
					__('Points section has been edited.'), __('Error! Points setion has not been edited.')
				),
				'del_points' => array(
					__('Points section has been deleted.'), __('Error! Points setion has not been deleted.')
				),
				'add_ranks' => array(
					__('Rank has been added.'), __('Error! Rank has not been added.')
				),
				'edit_ranks' => array(
					__('Rank has been edited.'), __('Error! Rank has not been edited.')
				),
				'del_ranks' => array(
					__('Rank has been deleted.'), __('Error! Rank has not been deleted.')
				),
				'add_bonus' => array(
					__('Bonus points have been added.'), __('Error! Bonus points have not been added.')
				),
				'add_fine' => array(
					__('Penalty points have been added.'), __('Error! Penalty points have not been added.')
				),
				'delete_points' => array(
					__('User points have been deleted.'), __('Error! User points have not been deleted.')
				),
				'delete_all_points' => array(
					__('All users points have been deleted.'), __('Error! All users points have not been deleted.')
				),
				'add_error' => array(
					'', __('Error: You must enter a positive value.')
				),
				'error' => array(
					'', __('Error! This number has already been used.')
				)
			)
		);
    }
	
	if ($_request->get('page')->show() === 'points')
	{
		$point = TRUE;
		
		if ($_request->get('action')->show() === 'delete_points' && $_request->get('id')->isNum())
		{
			$count = $_pdo->exec('DELETE FROM [points] WHERE `id`='.$_request->get('id')->show());

			if ($count)
			{
				$_log->insertSuccess('del_points', __('Deleted points section'));
				$_request->redirect(FILE_PATH, array('page' => 'points', 'act' => 'del_points', 'status' => 'ok'));
			}
			$_log->insertFail('del_points', __('Error when deleting a section'));
			$_request->redirect(FILE_PATH, array('page' => 'points', 'act' => 'del_points', 'status' => 'error'));
		}
		elseif ($_request->post('save_points')->show() && $_request->post('points')->isNum())
		{
			$points = $_request->post('points')->isNum(TRUE);
			$section = $_request->post('section')->strip();
			if ($points)
			{
				if ($_request->get('page')->show() === 'points' && $_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
				{
					$count = $_pdo->exec('UPDATE [points] SET `points` = :points WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':points', $points, PDO::PARAM_INT)
						)
					);

					if ($count)
					{
						$_request->redirect(FILE_PATH, array('page' => 'points', 'act' => 'edit_points', 'status' => 'ok'));
					}
					$_request->redirect(FILE_PATH, array('page' => 'points', 'act' => 'edit_points', 'status' => 'error'));
				}
				else
				{
					$count = $_pdo->exec('INSERT INTO [points] (`points`, `section`) VALUES (:points, :section)',
						array(
							array(':points', $points, PDO::PARAM_INT),
							array(':section', $section, PDO::PARAM_STR)
						)
					);
	
					if ($count)
					{
						$_log->insertSuccess('add_points', __('Points section has been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'points', 'act' => 'add_points', 'status' => 'ok'));
					}
					$_log->insertFail('add_points', __('Error! Points section has not been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'points', 'act' => 'add_points', 'status' => 'error'));
				}
			}
			else
			{
				$_request->redirect(FILE_PATH);
			}
		}
		elseif ($_request->get('page')->show() === 'points' && $_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$data = $_pdo->getRow('SELECT `points`, `section` FROM [points] WHERE `id` = '.$_request->get('id')->show());
			if ($data)
			{
				$points = $data['points'];
				$section = $data['section'];
				$edit = TRUE;
			}
		}
		else
		{
			$points = '';
			$section = '';
			$edit = FALSE;
		}
	
		$_tpl->assignGroup(array(
			'point' => $point,
			'points' => $points,
			'section' => $section,
			'edit' => $edit
		));
	}
	elseif ($_request->get('page')->show() === 'ranks')
	{
		$rank = TRUE;
		
		if ($_request->get('action')->show() === 'delete_ranks' && $_request->get('id')->isNum())
		{
			$count = $_pdo->exec('DELETE FROM [ranks] WHERE `id` = '.$_request->get('id')->show());

			if ($count)
			{
				$_log->insertSuccess('del_ranks', __('Rank has been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'del_ranks', 'status' => 'ok'));
			}
			$_log->insertFail('del_ranks', __('Error! Rank has not been deleted.'));
			$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'del_ranks', 'status' => 'error'));
		}
		elseif($_request->post('save_ranks')->show() && $_request->post('points')->isNum())
		{
			$ranks = $_request->post('ranks')->strip();
			if ($ranks)
			{
				if ($_request->get('page')->show() === 'ranks' && $_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
				{
					$count = $_pdo->exec('UPDATE [ranks] SET `points` = :points, `ranks` = :ranks WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':points', $_request->post('points')->show(), PDO::PARAM_INT),
							array(':ranks', $ranks, PDO::PARAM_STR)
						)
					);

					if ($count)
					{
						$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'edit_ranks', 'status' => 'ok'));
					}
					$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'edit_ranks', 'status' => 'error'));
				}
				else
				{
					$data = $_pdo->getRow('SELECT `points` FROM [ranks] WHERE `points` = '.$_request->post('points')->show());
					if($data)
					{
						$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'error', 'status' => 'error'));
					}
					
					$count = $_pdo->exec('INSERT INTO [ranks] (`points`, `ranks`) VALUES (:points, :ranks)',
						array(
							array(':points', $_request->post('points')->show(), PDO::PARAM_INT),
							array(':ranks', $ranks, PDO::PARAM_STR)
						)
					);

					if ($count)
					{
						$_log->insertSuccess('add_ranks', __('Rank has been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'add_ranks', 'status' => 'ok'));
					}
					$_log->insertFail('add_ranks', __('Error! Rank has not been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'ranks', 'act' => 'add_ranks', 'status' => 'error'));
				}
			}
			else
			{
				$_request->redirect(FILE_PATH);
			}
		}
		elseif ($_request->get('page')->show() === 'ranks' && $_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$data = $_pdo->getRow('SELECT `points`, `ranks` FROM [ranks] WHERE `id` = '.$_request->get('id')->show());

			if ($data)
			{
				$points = $data['points'];
				$ranks = $data['ranks'];
			}
		}
		else
		{
			$points = '';
			$ranks = '';
		}
		
		$_tpl->assignGroup(array(
			'rank' => $rank,
			'points' => $points,
			'ranks' => $ranks
		));
	}
	elseif ($_request->get('page')->show() === 'bonus')
	{
		$bonus = TRUE;
		$user_points = array();
		
		if ($_request->get('user')->show())
		{
			if($_request->post('plus_points')->show() && $_request->post('points_bonus')->show())
			{
				if($_request->post('points_bonus')->isNum() && $_request->post('bonus_user')->isNum())
				{
					if($_request->post('add_comment')->show() != NULL)
					{
						$comment = $_request->post('add_comment')->strip().'&nbsp;';
						$comment .= __('(:points points)', array(':points' => $_request->post('points_bonus')->show()));
					}
					else
					{
						$comment = __('Bonus points to the administrator (:points points)', array(':points' => $_request->post('points_bonus')->show()));
					}
					if ($_request->post('points_bonus')->show() > 0)
					{
						$_points->add($_request->post('bonus_user')->show(), $_request->post('points_bonus')->show(), $comment);
						$_system->clearCache('point_system');
						$_log->insertSuccess('add_bonus', $_user->getByID($_request->post('bonus_user')->show(), 'username').' - Dodanie punktów: '.$comment);
						$_request->redirect(FILE_PATH, array('page' => 'bonus', 'user' => $_request->post('user')->show(), 'act' => 'add_bonus', 'status' => 'ok'));
					}
					else
					{
						$_request->redirect(FILE_PATH, array('page' => 'bonus', 'user' => $_request->post('user')->show(), 'act' => 'add_error', 'status' => 'error'));
					}
				}
			}
			elseif($_request->post('minus_points')->show() && $_request->post('points_fine')->show())
			{
				if($_request->post('points_fine')->isNum() && $_request->post('fine_user')->isNum())
				{
					if($_request->post('fine_comment')->show() != NULL)
					{
						$comment = $_request->post('fine_comment')->strip().'&nbsp;';
						$comment .= __('(:points points)', array(':points' => -$_request->post('points_fine')->show()));
					}
					else
					{
						$comment = __('Punishment points to the administrator (:points points)', array(':points' => -$_request->post('points_fine')->show()));
					}
					if ($_request->post('points_fine')->show() > 0)
					{
						$_points->add($_request->post('fine_user')->show(), -$_request->post('points_fine')->show(), $comment);
						$_system->clearCache('point_system');
						$_log->insertSuccess('add_fine', $_user->getByID($_request->post('fine_user')->show(), 'username').' - Odjęcie punktów: '.$comment);
						$_request->redirect(FILE_PATH, array('page' => 'bonus', 'user' => $_request->post('user')->show(), 'act' => 'add_fine', 'status' => 'ok'));
					}
					else
					{
						$_request->redirect(FILE_PATH, array('page' => 'bonus', 'user' => $_request->post('user')->show(), 'act' => 'add_error', 'status' => 'error'));
					}
				}
			}
			elseif($_request->post('delete_user_points')->show() && $_request->post('user')->isNum())
			{
				$_points->deleteAll($_request->post('user')->show());
				$_system->clearCache('point_system');
				$_log->insertSuccess('delete_points', $_user->getByID($_request->post('user')->show(), 'username').' - '.__('Deleted all user points.'));
				$_request->redirect(FILE_PATH, array('page' => 'bonus', 'user' => $_request->post('user')->show(), 'act' => 'delete_points', 'status' => 'ok'));
			}
			
			$query = $_pdo->getData('
				SELECT p.*, u.`id` 
				FROM [points_history] p
				LEFT JOIN [users] u 
				ON p.`user_id` = u.`id`
				WHERE p.`user_id` = '.$_request->get('user')->show().'
				ORDER BY p.`date` DESC LIMIT 0,6'
			);
			
			if ($_pdo->getRowsCount($query)) 
			{
				$i = 0; $history = array();
				foreach ($query as $row)
				{
					$history[] = array(
						'date' => HELP::showDate("shortdate", $row['date']),
						'text' => $row['text'],
						'points' => $row['points']
					);
					$i++;
				}
			}
			
		
			$user_points = array(
				'user_bonus' => TRUE,
				'points' => $_points->show($_user->getByID($_request->get('user')->show(), 'id')),
				'rank' => $_points->showRank($_user->getByID($_request->get('user')->show(), 'id')),
			);
		}
		
		$_tpl->assignGroup(array(
			'bonus' => $bonus,
			'point_user' => $user_points,
			'user' => ($_request->get('user')->show() ? $_user->getByID($_request->get('user')->show()) : FALSE),
			'history' => (isset($history) ? $history : FALSE)
		));
	}
	
	$query = $_pdo->getData('SELECT `id`, `points`, `section` FROM [points] ORDER BY `id`');
	if ($_pdo->getRowsCount($query)) {
		$i = 0; $data = array();
		foreach ($query as $row)
		{
			$data[] = array(
				'row_color' => ($i % 2 == 0 ? 'tbl1' : 'tbl2'),
				'id' => $row['id'],
				'points' => $row['points'],
				'section' => $row['section']
			);
			$i++;
		}
		$_tpl->assign('point_list', $data);
	}

	$query = $_pdo->getData('SELECT `id`, `points`, `ranks` FROM [ranks] ORDER BY `points`');
	if ($_pdo->getRowsCount($query)) {
		$i = 0; $data = array();
		foreach ($query as $row)
		{
			$data[] = array(
				'row_color' => ($i % 2 == 0 ? 'tbl1' : 'tbl2'),
				'id' => $row['id'],
				'points' => $row['points'],
				'ranks' => $row['ranks']
			);
			$i++;
		}
		$_tpl->assign('rank_list', $data);
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