<?php // Wyszukiwania uzytkownika po ID, loginie, adresie e-mail bądź IP
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
if (isset($_POST['from_admin']) && $_POST['from_admin'])
{
	require_once '../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
}
else
{
	require_once '../system/sitecore.php';
}

if ($_user->isLoggedIn())
{
	$request = new Edit($_request->post('to')->trim());

	if ($request->show())
	{
		if ($request->isNum(FALSE, FALSE))
		{
			$field = 'id';
			$value = intval($request->show());
		}
		elseif ($request->isIP(FALSE))
		{
			$field = 'ip';
			$value = $request->show();
		}
		elseif ($request->isEmail(FALSE))
		{
			$field = 'email';
			$value = $request->show();
		}
		else
		{
			$field = 'username';
			$value = $request->strip();
		}


		if ($value)
		{
			if ($_request->post('self_search')->show())
			{
				if ($_request->post('only_active')->show())
				{
					$data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `'.$field.'` LIKE "'.$value.'%" AND `status` = 0 ORDER BY `username` ASC LIMIT 0,10');
				}
				else
				{
					$data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `'.$field.'` LIKE "'.$value.'%" ORDER BY `username` ASC LIMIT 0,10');
				}
			}
			else
			{
				if ($_request->post('only_active')->show())
				{
					$data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `'.$field.'` LIKE "'.$value.'%" AND id != '.$_user->get('id').' AND `status` = 0 ORDER BY `username` ASC LIMIT 0,10');
				}
				else
				{
					$data = $_pdo->getData('SELECT `id`, `username` FROM [users] WHERE `'.$field.'` LIKE "'.$value.'%" AND id != '.$_user->get('id').' AND ORDER BY `username` ASC LIMIT 0,10');
				}
			}
			if ($data)
			{ ?>
				{
					"status" : 0,
					"users" :
					[
						<?php
						$json = array();
						foreach($data as $row)
						{
							$json[] = '{"username" : "'.$row['username'].'", "id" : "'.$row['id'].'"}';
						}
						echo implode($json, ',');
						?>
					]
				}
			  <?php
			  exit;
			}
			else
			{
				echo '{"status" : 1, "error_msg" : "Brak wyników wyszukiwania."}'; exit; // brak wyników wyszukiwania
			}
		}
	}

	echo '{"status" : 2}'; exit; // brak reakcji - nie przesłano danych
}

echo '{"status" : 3, "error_msg" : "Błąd: sesja zalogowania straciła ważność. Należy zalogować się ponownie."}'; exit;
