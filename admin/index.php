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
	require_once '../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	$tpl = new AdminMainEngine;

	$tpl->assign('Action', $_request->get('action')->show());
	
	if ($_request->session(array('history', 'Page'))->show())
	{
		$tpl->assign('History', $_SESSION['history']);
	}

	if ($_request->get('action')->show() === 'login')
    {
		if ($_request->post('login')->show())
        {
			if ($_user->adminLogin($_request->post('user')->show(), $_request->post('pass')->show()))
            {
				$_request->redirect(ADDR_ADMIN);
			}
		}
	}
    elseif ($_request->get('action')->show() === 'logout')
    {
		$_user->adminLogout();
		$_request->redirect(ADDR_ADMIN);
	}
    else
    {
		if ( ! $_user->hasPermission('admin.login'))
		{//echo 8; exit;
			$_user->adminLogout();
			$_request->redirect(ADDR_ADMIN.'index.php', array('action' => 'login'));
		}
		else
		{
			$query = $_pdo->query('SELECT page, permissions FROM [admin]');
			if ($query)
			{
				foreach($query as $data)
				{
					if ($_user->hasPermission($data['permissions']))
					{
						if ( ! isset($page_links[$data['page']]))
						{
							$page_links[$data['page']] = 1;
						}
						else
						{
							$page_links[$data['page']]++;
						}
					}
				}

				/*
					Póki co moduł prywatnych wiadomości nie jest stworzony.
					Więc bez sesnu pobierać te dane...

					- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

					$GetMessages = $_pdo->getMatchRowsCount('SELECT `id` FROM [messages] WHERE `to` = :to AND `read`=0 AND `folder`=0', array(
						array(':to', $_user->get('id'), PDO::PARAM_INT)
					));

					$tpl->assign('Messages', $GetMessages);
				*/

				$tpl->assign('UserID', $_user->get('id'));

				if (isset($page_links))
				{
					for ($i = 1, $c = count($page_links); $i <= $c; $i++)
					{
						if (isset($page_links[$i]))
						{
							$DefaultPageNum = $i;
							break;
						}
					}
					var_dump($DefaultPageNum, $page_links);
					$tpl->assign('Count', $page_links);
					$tpl->assign('DefaultPageNum', $DefaultPageNum);
				}
				else
				{
					HELP::redirect(ADDR_ADMIN.'index.php?action=login');
				}
			}
			else
			{
				HELP::redirect(ADDR_ADMIN.'index.php?action=login');
			}
		}
	}

	$tpl->template('pre'.DS.'_framework');
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
