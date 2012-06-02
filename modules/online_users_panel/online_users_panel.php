<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
$_locale->moduleLoad('lang', 'online_users_panel');

openside(__('global_010'));

	$online = $_user->getOnline();
	/*$robots = array(
		array(
			'username' => $_robots->getRobots(FALSE, TRUE)
		)
	);
	*/
	
	echo '<div>'.__('global_011').': '.$_user->getGuests().'</div>';
	//echo '<div>'.__('global_012').': '.((count($online))+(count($robots))).'</div>';
	echo '<div>'.__('global_012').': '.(count($online)).'</div>';

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
	
	echo '<br />';
	
	echo '<div>'.__('global_014').': '.number_format($_pdo->getMatchRowsCount('SELECT `id` FROM [users] WHERE status = 0')).'</div>';

	if ($_sett->get('admin_activation') === '1' && $_user->hasPermission('admin.members'))
	{
		//echo '<a href="members.php?status=2" class="side">Nieaktywnych</a>';
		echo ': '.$_pdo->getMatchRowsCount('SELECT `id` FROM [users] WHERE status<=2')."<br />\n";
	}
	$data = $_pdo->getRow('SELECT `id`, `username` FROM [users] WHERE `status` != 1 AND `status` !=2 ORDER BY `joined` DESC LIMIT 1');
	echo __('global_016').': <span class="side">'.HELP::profileLink($data['username'], $data['id'])."</span>\n";
closeside();