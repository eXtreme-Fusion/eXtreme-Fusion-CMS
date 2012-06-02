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
require_once '../system/sitecore.php';

if ($_user->isLoggedIn())
{
	$username = $_request->get('user')->filters('strip');

	if ($username)
	{
		$query = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `username` LIKE "%'.$username.'%" AND id != '.$_user->get('id').' ORDER BY `username` ASC LIMIT 0,10');
		foreach ($query as $row)
		{
			echo '<p><a href="'.HELP::path(array('controller' => 'messages', 'action' => 'view', $row['id'])).'">'.$_user->getUsername($row['id']).'</a></p>';
		}
	}
}