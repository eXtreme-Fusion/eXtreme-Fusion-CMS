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
