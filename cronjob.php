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

// Cron Job (1 MIN)
if ($_sett->get('cronjob_templates_clean') < (time()-60))
{
	$_files->rmDirRecursive(DIR_CACHE);
	$_sett->update(array('cronjob_templates_clean' => time()));
}

// Cron Job (6 MIN)
if ($_sett->get('cronjob_hour') < (time()-360))
{
	$_pdo->exec('DELETE FROM [flood_control] WHERE `timestamp`<'.(time()-360));
	$_pdo->exec('DELETE FROM [captcha] WHERE `datestamp`<'.(time()-360));
	//$_pdo->exec('DELETE FROM [users] WHERE `joined`=0 AND `ip`=0.0.0.0');
}

// Cron Job (12 MIN)
if ($_sett->get('cronjob_hour') < (time()-60*60*2))
{
	// Usuwanie niepotrzebnych wpisów z tabeli u¿ytkowników online.
	$_pdo->exec('DELETE FROM [users_online] WHERE `last_activity` < '.(time()-60*60*2));
	$_sett->update(array('cronjob_hour' => time()));
}

// Cron Job (24 HOUR)
if ($_sett->get('cronjob_day') < (time()-86400))
{
	$new_time = time();

	//$_pdo->exec('DELETE FROM [users] WHERE `datestamp` < '.(time()-86400));

	$query = $_pdo->getData('
		SELECT `id`, `username`, `email` FROM [users]
		WHERE `status`=3 AND `actiontime` != 0 AND `actiontime` < '.time().'
		LIMIT 10
	');
	if ($query)
	{
		/*
		if ($_pdo->getRowsCount($query))
		{
			foreach($query as $data)
			{
				$_pdo->exec('UPDATE [users] SET status = 0, actiontime = 0 WHERE id = :id',
					array(
						array(':id', $data['id'], PDO::PARAM_INT)
					)
				);
				$subject = __('global_451');
				$message = __('global_452', array(USER_NAME => $data['username'], 'LOST_PASSWORD' => ADDR_SITE.'lostpassword.php'));

				sendemail($data['username'], $data['email'], $_sett->get('siteusername'), $_sett->get('siteemail'), $subject, $message);
			}
		}
		if ($_pdo->getRowsCount($query) > 10)
		{
			$new_time = $_sett->get('cronjob_day');
		}
		*/
	}

	$query = $_pdo->getData('
		SELECT `id` FROM [users]
		WHERE `actiontime` < '.time().' AND `actiontime` !=0 AND `status` = 0
		LIMIT 10
	');
	if ($query)
	{
		if ($_sett->get('deactivation_action') == 0)
		{
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $data)
				{
					$_pdo->exec('UPDATE [users] SET `actiontime` = 0, `status` = 6 WHERE `id` = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
				}
			}
		}
		else
		{
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $data)
				{
					/*
					$_pdo->exec('DELETE FROM PREFIX_users WHERE `id` = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
					*/
					$_pdo->exec('DELETE FROM PREFIX_comments WHERE `name`= = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
					$_pdo->exec('DELETE FROM PREFIX_messages WHERE `to` = :id OR `from` = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
					$_pdo->exec('DELETE FROM PREFIX_news WHERE `name` = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
					$_pdo->exec('DELETE FROM PREFIX_ratings WHERE `user` = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
					$_pdo->exec('DELETE FROM PREFIX_suspends WHERE `user` = :id',
						array(
							array(':id', $data['id'], PDO::PARAM_INT)
						)
					);
				}
			}
		}
		if ($_pdo->getRowsCount($query) > 10)
		{
			$new_time = $_sett->get('cronjob_day');
		}
	}

	$_sett->update(array('cronjob_day' => $new_time));
}