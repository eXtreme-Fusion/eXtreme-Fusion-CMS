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
require_once '../system/sitecore.php';

if ($_user->isLoggedIn())
{
	$username = $_request->post('to')->filters('strip');

	if ($username)
	{
		if ($data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `username` LIKE "%'.$username.'%" AND id != '.$_user->get('id').' ORDER BY `username` ASC LIMIT 0,10'))
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