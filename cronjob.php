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

// Cron Job (1 MIN) - todo: zakomentowane, bo przecież cache czysci sie wg poszczegolych ustawien dla plikow a nie wszystko przez cronjoba!
//if ($_sett->get('cronjob_templates_clean') < (time()-60))
//{
//	$_files->rmDirRecursive(DIR_CACHE);
//	$_sett->update(array('cronjob_templates_clean' => time()));
//}

// Cron Job (6 MIN) todo: zakomentowane bo nie korzystamy z tych tabel
//if ($_sett->get('cronjob_hour') < (time()-360))
//{
	//$_pdo->exec('DELETE FROM [flood_control] WHERE `timestamp`<'.(time()-360));
	//$_pdo->exec('DELETE FROM [captcha] WHERE `datestamp`<'.(time()-360));
	//$_pdo->exec('DELETE FROM [users] WHERE `joined`=0 AND `ip`=0.0.0.0');
//}

// Cron Job (12 MIN)
if ($_sett->get('cronjob_hour') < (time()-60*60*2))
{
	// Usuwanie niepotrzebnych wpisów z tabeli użytkowników online.
	$_pdo->exec('DELETE FROM [users_online] WHERE `last_activity` < '.(time()-60*60*2));
	$_sett->update(array('cronjob_hour' => time()));
}

/*
// Cron Job (24 HOUR)
if ($_sett->get('cronjob_day') < (time()-86400))
{

	$_sett->update(array('cronjob_day' => time()));
}
*/
