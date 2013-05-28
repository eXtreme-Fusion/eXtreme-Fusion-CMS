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

require_once '../../../system/sitecore.php';

$username = $_request->get('user')->filters('strip');

if ($username !== '')
{
	$query = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `username` LIKE "%'.$username.'%" ORDER BY `username` ASC LIMIT 0,10');
	foreach ($query as $row)
	{
		echo '<a href="'.ADDR_MODULES.'cautions/admin/cautions.php?user='.$row['id'].'"><div class="search">'.$_user->getUsername($row['id']).'</div></a>';
	}
}