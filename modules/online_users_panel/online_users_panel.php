<?php defined('EF5_SYSTEM') || exit;
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
$_locale->moduleLoad('lang', 'online_users_panel');

openside(__('Users Online'));

	$online = $_user->getOnline();
	/*$robots = array(
		array(
			'username' => $_robots->getRobots(FALSE, TRUE)
		)
	);
	*/

	echo '		<div>'.__('Guests Online').': '.$_user->getGuests().'</div>
	';
	//echo '<div>'.__('Members Online').': '.((count($online))+(count($robots))).'</div>';
	echo '				<div>'.__('Members Online').': '.(count($online)).'</div>
	';

	$data = array();
	if ($online)
	//if ($online || $robots)
	{

		foreach($online as $user)
		{
			$data[] = HELP::profileLink($user['username'], $user['id']);
		}

		/*
		foreach($robots as $robot)
		{
			$data[] = $robot['username'];
		}
		*/

		echo '<div>'.implode($data, ', ').'</div>';
	}

	echo '					<br />
	';

	echo '				<div>'.__('Total Members').': '.number_format($_pdo->getMatchRowsCount('SELECT `id` FROM [users] WHERE status = 0')).'</div>
	';

	if ($_sett->get('admin_activation') === '1' && $_user->hasPermission('admin.members'))
	{
		//echo '<a href="members.php?status=2" class="side">Nieaktywnych</a>';
		echo ': '.$_pdo->getMatchRowsCount('SELECT `id` FROM [users] WHERE status<=2')."<br />\n";
	}
	$data = $_pdo->getRow('SELECT `id`, `username` FROM [users] WHERE `status` != 1 AND `status` !=2 ORDER BY `joined` DESC LIMIT 1');
	echo '				'.__('Newest Member').': <span class="side">'.HELP::profileLink($data['username'], $data['id'])."</span>\n";
closeside();
