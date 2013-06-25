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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
if (isset($_POST['from_admin']) && $_POST['from_admin'])
{
	require_once '../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
}
else
{
	require_once '../system/sitecore.php';
}

if ($_user->isLoggedIn())
{
	$username = $_request->post('to')->filters('strip');

	if ($username)
	{
		if ($_request->post('self_search')->show())
		{
			$data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `username` LIKE "'.$username.'%" AND `status` = 0 ORDER BY `username` ASC LIMIT 0,10');
		}
		else
		{
			$data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `username` LIKE "'.$username.'%" AND id != '.$_user->get('id').' AND `status` = 0 ORDER BY `username` ASC LIMIT 0,10');
		}
		if ($data)
		{ ?>
			{
				"status" : 0,
				"users" : 
				[
					<?php
					$json = array();
					foreach($data as $row)
					{
						$json[] = '{"username" : "'.$row['username'].'", "id" : "'.$row['id'].'"}';
					}
					echo implode($json, ',');
					?>
				]
			}
		  <?php
		}
		else
		{
			echo '{"status" : 1}';
		}
	}
}
