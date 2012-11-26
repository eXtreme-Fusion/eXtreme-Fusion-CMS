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
				$data = str_replace(array('Column_'), array(''), $_request->post('SortOrder')->show());

				$columns = explode('|', $data);


				foreach ($columns as $cid => $cval)
				{
					$data = explode('=', $cval);

					$side = $data[0];
					if ( !isset($data[1]) || !$data[1]) continue;

					$panel = explode(',', $data[1]);

					for($i = 0, $c = count($panel); $i < $c; $i++)
					{
						$pdata = explode('_', $panel[$i]);
						if (count($pdata) < 2) continue;
						// Czy panel przeciągnięto do nieaktywnych?
						if ($side == '5')
						{
							// Usuwanie panelu, który został przed momentem z nieaktywnych przeniesiony do aktywnych
							if ($pdata[0] === 'New')
							{
								unset($pdata[0]);

								$delete = $_pdo->exec('DELETE FROM [panels] WHERE filename = :filename', array(':filename', implode('_', $pdata), PDO::PARAM_STR));

							}
							// Usuwanie panelu, który od dłuższego czasu był aktywny
							else
							{
								$delete = $_pdo->exec('DELETE FROM [panels] WHERE id = :id', array(':id', $pdata[1], PDO::PARAM_INT));
							}
						}
						// Panel przeciągnięto do aktywnych lub w aktywnych
						else
						{
							// Czy panel przeciągnięto do aktywnych?
							//echo $side.'->';

							if ($pdata[0] === 'New')
							{
								unset($pdata[0]);
								$file = implode('_', $pdata);

								// Czy plik z informacjami o panelu istnieje?
								if (file_exists(DIR_MODULES.$file.DS.'config.php'))
								{
									include DIR_MODULES.$file.DS.'config.php';
									$result = $_pdo->exec('INSERT INTO [panels] (`name`, `filename`, `side`, `type`, `access`, `status`) VALUES (:name, :file, :side, "file", :access, :status)',
										array(
											array(':side', $side, PDO::PARAM_INT),
											array(':name', $panel_info['title'], PDO::PARAM_STR),
											array(':file', $file, PDO::PARAM_STR),
											array(':access', $panel_info['access'], PDO::PARAM_STR),
											array(':status', (int) $panel_info['status'], PDO::PARAM_INT)
										)
									);_e('zapis');
								}else _e('plik nie istnieje');
							}
							// Panel przeciągnięto w grupie aktywnych
							else
							{
								// Aktualizacja położenia poszczególnych paneli
								$_pdo->exec('UPDATE [panels] SET `order` = '.($i+1).', `side` = :side WHERE id = :id',
								array(
									array(':id', $pdata[1], PDO::PARAM_INT),
									array(':side', $side, PDO::PARAM_STR)
								));
								echo '|'.$pdata[1].'-'.$i+1;
							}
						}
					}
				}

				echo $data;
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