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
| Author: Marcus Gottschalk (MarcusG)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	$_locale->moduleLoad('admin', 'chat');

	if ( ! $_user->hasPermission('module.chat.admin'))
	{
		throw new userException(__('Access denied'));
	}
	$_fav->setFavByLink('chat/admin/chat.php', $_user->get('id'));
	
	$_tpl = new AdminModuleIframe('chat');
	
	$_tpl->setHistory(__FILE__);
	
	$row = $_pdo->getRow('SELECT * FROM [chat_settings]');

	if ($_request->post('save')->show())
	{

		$count = $_pdo->exec('UPDATE [chat_settings] SET `refresh` = :refresh, `life_messages` = :life_messages, `panel_limit` = :panel_limit, `archive_limit` = :archive_limit',
			array(
				array(':refresh', $_request->post('refresh')->isNum(TRUE), PDO::PARAM_INT),
				array(':life_messages', $_request->post('life_messages')->isNum(TRUE), PDO::PARAM_INT),
				array(':panel_limit', $_request->post('panel_limit')->isNum(TRUE), PDO::PARAM_INT),
				array(':archive_limit', $_request->post('archive_limit')->isNum(TRUE), PDO::PARAM_INT),
			)
		);

		if ($count)
		{
			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
		}
	}

	$_tpl->assignGroup(array
		(
			'refresh' => $row['refresh'],
			'life_messages' => $row['life_messages'],
			'panel_limit' => $row['panel_limit'],
			'archive_limit' => $row['archive_limit']
		)
	);

	$_tpl->template('admin.tpl');

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
    PDOErrorHandler($exception);
}