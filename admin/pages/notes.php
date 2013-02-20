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
	
	$_locale->load('home');

	if ($_request->post('note_title')->show() && $_request->post('note_id')->isNum())
	{
		$_pdo->exec('UPDATE [notes] SET `title` = :title WHERE `id` = :id',
			array(
				array(':id', $_request->post('note_id')->show(), PDO::PARAM_INT),
				array(':title', $_request->post('note_title')->strip(), PDO::PARAM_STR)
			)
		);
		
		_e($_request->post('note_title')->strip());
	}
	else if ($_request->post('note_note')->show() && ($_request->post('note_id')->isNum()))
	{
		$_pdo->exec('UPDATE [notes] SET `note` = :note WHERE `id` = :id',
			array(
				array(':id', $_request->post('note_id')->show(), PDO::PARAM_INT),
				array(':note', $_request->post('note_note')->strip(), PDO::PARAM_STR)
			)
		);
		
		_e($_request->post('note_note')->strip());
	}
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
