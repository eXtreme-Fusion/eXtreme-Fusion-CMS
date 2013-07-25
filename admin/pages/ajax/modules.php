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

	/**
	 * Odnawianie sesji zalogowania
	 */
	if ($_request->post('Action')->show() === 'renewSession')
	{
		if ($_user->isLoggedIn())
		{
			$_pdo->exec('UPDATE [users] SET `admin_last_logged_in` = '.time().' WHERE `id` = '.$_user->get('id'));

			_e($_sett->getUns('loging', 'admin_loging_time'));
		}

		_e(__('Sesja wygasła. Proszę się ponownie zalogować.'));
	}

	if ($_user->isLoggedIn())
	{
		/**
		 * Pobieranie prywatnych wiadomości - home.php
		 * NIE USUWAĆ - informacje w pliku modules.js
		 */
		/*if ($_request->post('Action')->show() === 'GetMessages' && $_request->post('UserID')->isNum())
		{
			$count = $_pdo->getMatchRowsCount('SELECT `id` FROM [messages] WHERE `to` = :id AND `read` = 0 AND `folder` = 0',
				array(
					array(':id', $_request->post('UserID')->show(), PDO::PARAM_INT)
				)
			);

			_e($count);
		}*/

		/**
		 * Aktualizacja pozycji panelu - panels.php
		 */
		if ($_request->post('Action')->show() === 'UpdatePanels' && $_request->post('SortOrder')->show())
		{
			if ($_user->hasPermission('admin.panels'))
			{
				$req = json_decode($_request->post('SortOrder')->show());
				
				$_tag = new Tag($_system, $_pdo);
				$_modules = new Modules($_pdo, $_sett, $_user, $_tag, $_locale, $_system);
				$_panels = new Panels($_pdo);

				$_panels->setModulesInst($_modules);	
   
				// Tworzy listę modułów nieaktywnych 
				$inactive = $_panels->getInactivePanelsDir($_user, TRUE);

				// Walidacja danych wejściowych
				foreach($req as $column => $panels)
				{
					$data = explode('_', $column);
					
					if (count($data) < 2)
					{
						exit('ERROR 1');
					}
					
					foreach($panels as $panel)
					{
						if ($panel)
						{
							$panel = explode('_', $panel);
							if (count($panel) < 2)
							{
								exit('ERROR 2');
							}
							// Sprawdzanie, czy panel jest dostępny, gdy przeciągnięto go z grupy nieaktywnych
							elseif ($data[1] !== '5' && $panel[0] !== 'Item')
							{
								unset($panel[0]);
								if (!in_array(implode('_', $panel), $inactive))
								{
									exit('ERROR 3');
								}
							}
						}
					}
				}

				foreach ($req as $column => $panels)
				{
					if (!$panels)
					{
						continue;
					}

					$data = explode('_', $column);

					$side = $data[1];

					for ($i = 1, $c = count($panels); $i <= $c; $i++)
					{
						$pdata = explode('_', $panels[$i-1]);

						// Czy panel pochodzi z grupy nieaktywnych biorąc pod uwagę stan sprzed przeładowania strony?
						if ($pdata[0] === 'New')
						{
							$file = $pdata; unset($file[0]); $file = implode('_', $file);
						}

						// Czy panel przeciągnięto do nieaktywnych?
						if ($side === '5')
						{
							// Usuwanie panelu, który został przed momentem z nieaktywnych przeniesiony do aktywnych
							if ($pdata[0] === 'New')
							{
								// Usuwanie w celu otrzymania ściężki po sklejeniu

								$delete = $_pdo->exec('DELETE FROM [panels] WHERE filename = :filename', array(':filename', HELP::strip($file), PDO::PARAM_STR));
							}
							// Usuwanie panelu, który od dłuższego czasu był aktywny
							else
							{
								$delete = $_pdo->exec('DELETE FROM [panels] WHERE id = :id', array(':id', $pdata[1], PDO::PARAM_INT));
							}
						}
						// Panel przeciągnięto z nieaktywnych do aktywnych lub w polu aktywnych
						else
						{
							$exists = $_pdo->getRow('SELECT `id` FROM [panels] WHERE `filename` = :filename', array(
								':filename', HELP::strip($file), PDO::PARAM_STR
							));

							// Czy panel nie istnieje w bazie danych i przeciągnięto go z pola nieaktywnych?
							if (!$exists && $pdata[0] === 'New')
							{
								// Czy plik z informacjami o panelu istnieje?
								if (file_exists(DIR_MODULES.$file.DS.'config.php'))
								{
									include DIR_MODULES.$file.DS.'config.php';
									$result = $_pdo->exec('INSERT INTO [panels] (`name`, `filename`, `side`, `type`, `access`, `status`, `order`) VALUES (:name, :file, :side, "file", :access, :status, '.$i.')',
										array(
											array(':side', $side, PDO::PARAM_INT),
											array(':name', $panel_info['title'], PDO::PARAM_STR),
											array(':file', $file, PDO::PARAM_STR),
											array(':access', $panel_info['access'], PDO::PARAM_STR),
											array(':status', (int) $panel_info['status'], PDO::PARAM_INT)
										)
									);
									echo 'zapis';
								}
								else
								{
									echo 'plik '.$file.' nie istnieje';
								}
							}
							// Panel przeciągnięto w grupie aktywnych
							else
							{
								// Aktualizacja położenia poszczególnych paneli
								$_pdo->exec('UPDATE [panels] SET `order` = '.$i.', `side` = :side WHERE id = :id',
								array(
									array(':id', $pdata[0] === 'New' ? $exists['id'] : $pdata[1], PDO::PARAM_INT),
									array(':side', $side, PDO::PARAM_STR)
								));
							}
						}

					}
				}
			}
			else
			{
				throw new userException(__('Access denied'));
			}
		}

		/**
		 * Usuwanie panelu - panels.php
		 */
		if ($_request->post('Action')->show() === 'Delete')
		{
			if ($_request->post('PanelID')->isNum())
			{
				if ($_user->hasPermission('admin.panels'))
				{
					$data = $_pdo->getRow('SELECT `side`, `order` FROM [panels] WHERE `id` = :id',
						array(
							array(':id', $_request->post('PanelID')->show(), PDO::PARAM_INT)
						)
					);

					$count = $_pdo->exec('DELETE FROM [panels] WHERE `id` = :id',
						array(
							array(':id', $_request->post('PanelID')->show(), PDO::PARAM_INT)
						)
					);

					$_pdo->exec('UPDATE [panels] SET `order` = `order`-1 WHERE `side` = :side AND `order` > :order_w',
						array(
							array(':order_w', $data['order'], PDO::PARAM_INT),
							array(':side', $data['side'], PDO::PARAM_INT)
						)
					);

					if ($count)
					{
						_e('success');
					}

					_e('Usunieto nie prawidłowo...');
				}
				else
				{
					throw new userException(__('Access denied'));
				}
			}

			throw new userException(__('Entered the wrong data type.'));
		}

		/**
		 * Zmiana statusu panelu - panels.php
		 */
		if ($_request->post('Action')->show() === 'ChangeStatus')
		{
			if ($_request->post('PanelID')->show())
			{
				if ($_user->hasPermission('admin.panels'))
				{
					$status = ($_request->post('PanelStatus')->show() === '1' ? 0 : 1);
					$_pdo->exec('UPDATE [panels] SET `status` = :status WHERE `id` = :id',
						array(
							array(':id', $_request->post('PanelID')->show(), PDO::PARAM_INT),
							array(':status', $status, PDO::PARAM_INT)
						)
					);

					_e($status.'_'.$_request->post('PanelID')->show());
				}
				else
				{
					throw new userException(__('Access denied'));
				}
			}

			throw new userException(__('Entered the wrong data type.'));
		}

		/* DRAG & DROP w Nawigacji */
		if ($_request->post('ArrayOrderNavigation')->show() && $_request->post('UpdateOrderNavigations')->show() === 'Ok')
		{
			if ($_user->hasPermission('admin.navigations'))
			{
				$i = 1;
				foreach ($_request->post('ArrayOrderNavigation')->show() as $id)
				{
					$_pdo->exec('UPDATE [navigation] SET `order` = :order WHERE `id` = :id',
						array(
							array(':id', $id, PDO::PARAM_INT),
							array(':order', $i, PDO::PARAM_INT)
						)
					);

					$i++;
				}
				_e(__('Dane zostały pomyślnie zapisane'));
			}

			throw new userException(__('Access denied'));
		}

		/* DRAG & DROP w BBcode */
		if ($_request->post('ArrayOrderBBcode')->show() && $_request->post('UpdateOrderBBcode')->show() === 'Ok')
		{
			if ($_user->hasPermission('admin.bbcodes'))
			{
				$i = 1;
				foreach ($_request->post('ArrayOrderBBcode')->show() as $id)
				{
					$_pdo->exec('UPDATE [bbcodes] SET `order` = :order WHERE `id` = :id',
						array(
							array(':id', $id, PDO::PARAM_INT),
							array(':order', $i, PDO::PARAM_INT)
						)
					);

					$i++;
				}
				_e(__('Dane zostały pomyślnie zapisane'));
			}

			throw new userException(__('Access denied'));
		}

		/* DRAG & DROP w Kategori pól profilu */
		if ($_request->post('ArrayOrderUserFieldsCats')->show() && $_request->post('UpdateOrderUserFieldsCats')->show() === 'Ok')
		{
			if ($_user->hasPermission('admin.user_fields_cats'))
			{
				$i = 1;
				foreach ($_request->post('ArrayOrderUserFieldsCats')->show() as $id)
				{
					$_pdo->exec('UPDATE [user_field_cats] SET `order` = :order WHERE `id` = :id',
						array(
							array(':id', $id, PDO::PARAM_INT),
							array(':order', $i, PDO::PARAM_INT)
						)
					);

					$i++;
				}
				$_system->clearCache('profiles');
				_e(__('Dane zostały pomyślnie zapisane'));
			}

			throw new userException(__('Access denied'));
		}
	}

	if ($_request->get('term')->show())
	{
		$_tag = New Tag($_system, $_pdo);

		if ($array = $_tag->getAllTag())
		{
			$data = array(); $result = array();
			foreach($array as $row)
			{
				$data[] = $row['value'];
			}

			foreach(array_unique($data) as $key => $value)
			{
				if(strlen($_request->get('term')->show()) === 1 || strpos(strtolower($value), strtolower($_request->get('term')->show())) !== false)
				{
					$result[] = '{"id":"'.$key.'","label":"'.$value.'","value":"'.$value.'"}';
				}
			}

			_e('['.implode(',', $result).']');
		}

		_e('[]');
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
