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
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

	if ($_user->isLoggedIn())
	{
		/**
		  * Automatyczne pobieranie danych usera
		**/
		if ($_request->get('term')->show())
		{
			if ($_user->hasPermission('admin.users'))
			{
				$query = $_pdo->getData('
						SELECT `id`, `username`, `email`
						FROM [users]
						ORDER BY `id` ASC
				');

				$i = 0; $data = array(); $autocompletiondata = array(); $result = array();
				foreach($query as $row)
				{
					$autocompletiondata[] = array(
						'username' => $row['username'],
						'email' => $row['email']
					);
				}

				foreach($autocompletiondata as $key => $value) {
					if(strlen($_GET['term']) === 0 || strpos(strtolower($value['username']), strtolower($_GET['term'])) !== false) {
						$result[] = '{"id":"'.$key.'","label":"'.$value['username'].'","value":"'.$value['email'].'"}';
					}
				}
				_e('['.implode(',', $result).']');
			}

			throw new userException(__('Access denied'));
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
