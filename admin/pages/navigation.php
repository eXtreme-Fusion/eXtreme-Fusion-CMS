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

	$_locale->load('pages');

    if ( ! $_user->isLoggedIn())
    {
        $_request->redirect(ADDR_ADMIN);
    }

	$_tpl = new Iframe;

	$_SESSION['history'] = array(
		'Page' => 'admin/pages/'.FILE_SELF.'?'.$_SERVER['QUERY_STRING'],
		'Current' => $_request->get('PageNum')->show() ? intval($_request->get('PageNum')->show()) : 5
	);

	if ($_request->get('PageNum')->show())
	{
		switch($_request->get('PageNum')->show())
		{
			case 1:
				$_tpl->assign('NavigationTitle', __('Manage content'));
				break;
			case 2:
				$_tpl->assign('NavigationTitle', __('Manage users'));
				break;
			case 3:
				$_tpl->assign('NavigationTitle', __('Manage site'));
				break;
			case 4:
				$_tpl->assign('NavigationTitle', __('Settings'));
				break;
			case 5:
				$_tpl->assign('NavigationTitle', __('Modules'));
				break;
			case 6:
				$_tpl->assign('NavigationTitle', __('Panel'));
				break;
			case 7:
				$_tpl->assign('NavigationTitle', __('System'));
				break;
			default:
				$_tpl->assign('NavigationTitle', 'N/A');
		}

		$query = $_pdo->getData('SELECT * FROM [admin] WHERE `page` = :page ORDER BY `title`',
			array(':page', $_request->get('PageNum')->show(), PDO::PARAM_INT)
		);

		$link = array();
		if ($_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				if ($row['link'] !== 'reserved' && $_user->hasPermission($row['permissions']))
				{
					$link[] = array(
						'Link'  => $_request->get('PageNum')->show() === '5' ? ADDR_MODULES.$row['link'] : $row['link'],
						'Image' => $_request->get('PageNum')->show() === '5' ? ADDR_MODULES.$row['image'] : $row['image'],
						'Title' => __($row['title'])
					);
				}
			}
		}

		if ($_request->get('PageNum')->show() === '5')
		{
			if (count($link))
			{
				$_tpl->assign('NavigationTitle', __('Modules'));
				$_tpl->assign('Modules', TRUE);
				$_tpl->assign('AdminLink', $link);
				$_tpl->template('navigation');
			}
			else
			{
				require DIR_ADMIN.'pages/modules.php';
			}
		}
		elseif ($_request->get('PageNum')->show() === '6')
		{
			require DIR_ADMIN.'pages/home.php';
		}
		elseif ($_request->get('PageNum')->show() === '7')
		{
			require DIR_ADMIN.'pages/settings_ef.php';
		}
		else
		{
			$_tpl->assign('AdminLink', $link);
			$_tpl->template('navigation');
		}
	}
	else
	{
		require DIR_ADMIN.'pages/modules.php';
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