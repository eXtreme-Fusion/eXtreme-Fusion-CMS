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