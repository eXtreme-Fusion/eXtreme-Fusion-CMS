<?php defined('EF5_SYSTEM') || exit;
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