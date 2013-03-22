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
try
{
	require_once '../../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->setSubDir(''); // reset pozycji
	$_locale->moduleLoad('lang', 'newsletter');
	
	if ($_request->post('email')->show())
	{
		if ($_user->bannedByEmail($_request->post('email')->show(), TRUE)) 
		{
			$request = __('Please enter a valid email address');
		}
		else
		{
			$request = __('You have been added to the list of newsletter members.');
			
			$_pdo->exec('INSERT INTO [newsletter] (`email`, `ip`, `datestamp`, `value`) VALUES (:email, :ip, '.time().', :value)',
				array(
					array(':email', $_request->post('email')->show(), PDO::PARAM_STR),
					array(':ip', $_user->getIP(), PDO::PARAM_STR),
					array(':value', '1', PDO::PARAM_STR)
				)
			);
		}
	} 
	else
	{
		$request = __('Enter your email address');
	}
	if ( ! $_request->post('rules')->show()) $request = __('Accept the rules');

	echo $request;
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