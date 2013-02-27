<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	if ($_request->post('email')->show())
	{
		if ( ! filter_var($_request->post('email')->show(), FILTER_VALIDATE_EMAIL)) {
			$request = __('Please enter a valid email address');
		}
		else
		{
			$request = __('You have been added to the list of newsletter members.');
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