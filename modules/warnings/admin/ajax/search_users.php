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
	require_once '../../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	if ($_user->isLoggedIn())
	{
		if ( ! $_user->hasPermission('module.warnings.admin'))
		{
			throw new userException(__('Access denied'));
		}
		
		if ($_request->get('user')->filters('strip'))
		{
			$query = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `username` LIKE "%":username"%" ORDER BY `username` ASC LIMIT 0,10',
				array(':username', $_request->get('user')->filters('strip'), PDO::PARAM_INT)
			);
			
			if ($query)
			{
				foreach ($query as $row)
				{
					echo '<div class="search">'.$_user->getUsername($row['id']).'<p><a href="'.ADDR_MODULES.'warnings/admin/warnings.php?page=warnings&amp;action=new&amp;uid='.$row['id'].'">'.__('Add Warning').'</a> || <a href="'.ADDR_MODULES.'warnings/admin/warnings.php?page=warnings&amp;action=history&amp;uid='.$row['id'].'">'.__('View history').'</a> || <a href="'.ADDR_MODULES.'warnings/admin/warnings.php?page=warnings&amp;action=explanation&amp;uid='.$row['id'].'">'.__('Explanation').'</a> </p></div>';
				}
			} 
			else
			{
				echo '<div class="search">'.__('No rezults').'</div>';
			}
		}
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