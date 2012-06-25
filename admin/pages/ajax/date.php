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
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	if ($_user->isLoggedIn())
	{
		if ($_request->post('value')->show())
		{
			$strf_time = time()+($_sett->get('offset_timezone')*3600);
			echo @HELP::strfTimeToUTF($_request->post('value')->show(), $strf_time);
		}
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