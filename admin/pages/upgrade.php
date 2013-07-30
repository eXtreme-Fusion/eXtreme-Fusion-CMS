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
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->load(array('settings', 'upgrade'));

    if ( ! $_user->hasPermission('admin.upgrade'))
	{
		throw new userException(__('Access denied'));
	}
	
	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
			array(
				'updating' => array(
					__('The update has been finished successfully.'), __('Error! The update has not been finished.')
				)
			)
		);
    }

	if (SYSTEM_VERSION !== $_sett->get('version'))
	{		
		if (! HELP::validUpVersion($_sett->get('version'), SYSTEM_VERSION))
		{
			$_tpl->printMessage('error', 'Nie możesz skorzystać z tej aktualizacji. Pobierz inną, właściwą dla Twojej wersji systemu CMS: https://github.com/eXtreme-Fusion/EF5-updates');
		}
		else
		{
			if ($_request->post('save')->show())
			{
				/*
					Zapytania wymagane podczas aktualizacji
				*/
			
				$_pdo->exec("INSERT INTO [admin] (`permissions`, `image`, `title`, `link`, `page`) VALUES ('admin.settings_synchro', 'synchro.png', 'Synchronization', 'settings_synchro.php', 4)");
				$_pdo->exec("INSERT INTO [permissions] (`name`, `section`, `description`, `is_system`) VALUES ('admin.settings_synchro', 1, '".__('Perm: admin settings_synchro')."', 1)");
				
				if(file_exists(DIR_SITE.'system'.DS.'opt'.DS.'opt.error.php'))
				{
					@unlink(DIR_SITE.'system'.DS.'opt'.DS.'opt.error.php');
				}
				
				if(file_exists(DIR_SITE.'themes'.DS.'eXtreme-Fusion-5'.DS.'core'.DS.'theme.php'))
				{
					//@unlink(DIR_SITE.'themes'.DS.'eXtreme-Fusion-5'.DS.'core'.DS.'theme.php');
				}

				$_pdo->exec("CREATE TABLE [admin_favourites] 
					(
						`id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` MEDIUMINT UNSIGNED NOT NULL,
						`page_id` MEDIUMINT UNSIGNED NOT NULL,
						`count` MEDIUMINT NOT NULL,
						`time` INT UNSIGNED NOT NULL,
						PRIMARY KEY (`id`),
						UNIQUE KEY(`page_id`, `user_id`),
						CONSTRAINT FOREIGN KEY (`page_id`) REFERENCES [admin](`id`) ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES [users](`id`) ON DELETE CASCADE ON UPDATE CASCADE
					) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;"
				);

				$_pdo->exec("ALTER TABLE [users] ADD `algo` VARCHAR(10) NOT NULL DEFAULT 'sha512' AFTER `salt`");
				$_pdo->exec("INSERT INTO [settings] (`key`, `value`) VALUES ('algorithm', 'sha512')");
				$_pdo->exec("INSERT INTO [settings] (`key`, `value`) VALUES ('synchro', '0')");
				$_pdo->exec("DELETE FROM [admin] WHERE `link` = 'panel_editor.php'");
				$_pdo->exec("DELETE FROM [admin] WHERE `link` = 'upgrade.php'");
				
				
				$_sett->update(array(
					'routing' => serialize(array(
						'param_sep' => '-',
						'main_sep' => $_sett->getUns('routing', 'main_sep'),
						'url_ext' => $_sett->getUns('routing', 'url_ext'),
						'tpl_ext' => $_sett->getUns('routing', 'tpl_ext'),
						'logic_ext' => $_sett->getUns('routing', 'logic_ext'),
						'ext_allowed' => $_sett->getUns('routing', 'ext_allowed')
					))
				));
			
				$_sett->update(array('version' => SYSTEM_VERSION));
				
				
				
				//if ($count)
				//{
					$_log->insertSuccess('updating', __('The update has been finished successfully.'));
					$_files->rmDirRecursive(DIR_CACHE);
					$_request->redirect(FILE_PATH, array('act' => 'updating', 'status' => 'ok'));
				//}

				//$_log->insertFail('updating', __('Error! The update has not been finished.'));
				//$_request->redirect(FILE_PATH, array('act' => 'updating', 'status' => 'error'));
			}

			$_tpl->assign('upgrade', TRUE);
		}
	}
	
	$_tpl->template('upgrade');
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
catch(PDOException $exception)
{
   echo $exception;
}