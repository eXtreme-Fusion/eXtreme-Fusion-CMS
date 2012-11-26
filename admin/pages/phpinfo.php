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

    if ( ! $_user->hasPermission('admin.phpinfo'))
    {
        throw new userException(__('Access denied'));
    }

	phpinfo();

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