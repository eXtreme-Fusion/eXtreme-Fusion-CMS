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
	include DIR_MODULES.'google_analytics/class/gapi.php';
                                
	$_locale->moduleLoad('admin', 'google_analytics');

	if($_request->get('page')->show() === 'preview')
	{
		
		if ( ! $_user->hasPermission('module.google_analytics.preview'))
		{
			throw new userException(__('Access denied'));
		}
			
		$_fav->setFavByLink('google_analytics/admin/google_analytics.php', $_user->get('id'));
	
		$_tpl = new AdminModuleIframe('google_analytics');
		
		$_tpl->setHistory(__FILE__);

		$row = $_pdo->getRow('SELECT * FROM [google_analytics_sett]');
		
		if ($row['status']) 
		{
			$google_analytics = $_system->cache('google_analytics', NULL, 'google_analytics', 86700);
			
			if ($google_analytics === NULL)
			{
				$google_analytics = new Gapi($row['email'], $row['password']);

				$dimensions = array(
					'source',
					'networkDomain',
					'browser',
					'browserVersion',
					'operatingSystem',
					'operatingSystemVersion',
					'country'
				);

				$metrics = array(
					'pageviews',
					'visits'
				);
				
				$google_analytics->requestReportData($row['account_id'], $dimensions, $metrics, '-visits');
				
				$_system->cache('google_analytics', $google_analytics, 'google_analytics');
			}

			if ( ! is_array($google_analytics->_error))
			{
			
				$date = explode('T', $google_analytics->getUpdated());
				$time = explode('.', $date[1]);
				
				$_tpl->assignGroup(array
					(
						'total_results' => number_format($google_analytics->getTotalResults(), 0, '', '.'),
						'page_views' => number_format($google_analytics->getPageviews(), 0, '', '.'),
						'visits' => number_format($google_analytics->getVisits(), 0, '', '.'),
						'updated_day' => $date[0],
						'updated_hour' => $time[0]
					)
				);
			}
			else
			{
				$_tpl->assign('Error', $google_analytics->_error);
			}
		} 
		else
		{
			$_tpl->assign('Error', array(__('Error! The module has not been turned on in settings.')));
		}		
	}
	elseif($_request->get('page')->show() === 'sett')
	{
		if ( ! $_user->hasPermission('module.google_analytics.sett'))
		{
			throw new userException(__('Access denied'));
		}

		$_tpl = new AdminModuleIframe('google_analytics');
		$_tpl->setHistory(__FILE__);
		
		$row = $_pdo->getRow('SELECT * FROM [google_analytics_sett]');

		if ($_request->post('save')->show())
		{
			$count = $_pdo->exec('UPDATE [google_analytics_sett] SET `email` = :email, `password` = :password, `account_id` = :account_id, `profile_id` = :profile_id, `status` = :status',
				array(
					array(':email', $_request->post('email')->strip(), PDO::PARAM_STR),
					array(':password', $_request->post('password')->strip(), PDO::PARAM_STR),
					array(':account_id', $_request->post('account_id')->strip(), PDO::PARAM_STR),
					array(':profile_id', $_request->post('profile_id')->strip(), PDO::PARAM_STR),
					array(':status', $_request->post('status')->strip(), PDO::PARAM_STR)
				)
			);
		
			if ($count)
			{
				$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
			}

		}
		
		$_tpl->assignGroup(array
			(
				'email' => $row['email'],
				'password' => $row['password'],
				'account_id' => $row['account_id'],
				'profile_id' => $row['profile_id'],
				'status' => $row['status']
			)
		);

	}
	else
	{
		$_request->redirect(FILE_PATH, array('page' => 'preview'));
		exit;
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