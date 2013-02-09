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

	$_locale->moduleLoad('admin', 'warnings');

	if ( ! $_user->hasPermission('module.warnings.admin'))
	{
		throw new userException(__('Access denied'));
	}

	$_tpl = new AdminModuleIframe('warnings');
	
	include DIR_MODULES.'warnings'.DS.'class'.DS.'Sett.php';
	include DIR_MODULES.'warnings'.DS.'config.php';
	
	$_tpl->assign('config', $mod_info);
	
	$periods = array(
		array('value' => 1, 'description' => __('1 day')),
		array('value' => 2, 'description' => __('2 days')),
		array('value' => 7, 'description' => __('1 week')),
		array('value' => 14, 'description' => __('2 weeks')),
		array('value' => 30, 'description' => __('1 month')),
		array('value' => 60, 'description' => __('2 months')),
		array('value' => 90, 'description' => __('3 months')),
		array('value' => 120, 'description' => __('4 months')),
		array('value' => 240, 'description' => __('8 months')),
		array('value' => 360, 'description' => __('1 year'))
	);

	// Inicjalizacja klas
	! class_exists('WarningsSett') || $_warnings_sett = New WarningsSett($_system, $_pdo);
	
	// --> Zakładki
	if($_request->get('page')->show() === 'cats')
	{
		// Dodawanie kategorii
		
		// Wyświetlenie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			// Wyświetli komunikat
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
				array(
					'add' => array(
						__('The category has been added.'), __('Error! The category has not been added.')
					),
					'edit' => array(
						__('The category has been edited.'), __('Error! The category has not been edited.')
					),
					'delete' => array(
						__('The category has been deleted.'), __('Error! The category has not been deleted.')
					)
				)
			);
		}

		// Sprawdzenie akcji usuwania kategorii
		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum(TRUE))
		{
			// Sprawdzenie czy usuwana kategoria nie zawiera w sobie ostrzeżeń
			$query = $_pdo->getData('SELECT `title` FROM [warnings] WHERE `cat`=  :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Nie zawiera ostrzeżeń
			if ( ! $_pdo->getRowsCount($query))
			{							
				// Usuń kategorię
				$count = $_pdo->exec('DELETE FROM [warnings_cats] WHERE `id` = :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				
				// Jeśli usunęłeś...
				if ($count)
				{
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('delete', __('The category has been deleted.'));
					$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'delete', 'status' => 'ok'));
				}
				
				// Nie usunąłeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('delete',  __('Error! The category has not been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'delete', 'status' => 'error'));
				
			} 
			else
			{
				// Wyświetl wyjątek o istniejących ostrzeżeniach w usuwanej kategorii
				foreach($query as $row)
				{
					$warnings[] = $row['title'];
				}
				
				throw new userException(__('Not empty cat', array(':warnings' => implode(', ', $warnings))));
			}
		}
		
		// Sprawdzanie czy przesłano formularz
		if ($_request->post('save')->show()) 
		{
			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum(TRUE)) 
			{
				// Wykonaj zapytania
				$count = $_pdo->exec('
					UPDATE [warnings_cats]
					SET `title` = :title, `description` = :description, `period` = :period
					WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':title', $_request->post('title')->strip(), PDO::PARAM_STR),
						array(':description', $_request->post('description')->strip(), PDO::PARAM_STR),
						array(':period', $_request->post('period')->strip(), PDO::PARAM_STR)
					)
				);
				
				// Jeśli edytował...
				if ($count)
				{
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('edit', __('The category has been edited.'));
					$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'edit', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('edit',  __('Error! The category has not been edited.'));
				$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'edit', 'status' => 'error'));
				
			}
			else
			{
				// Tu będą zapytania PDO
				$count = $_pdo->exec('INSERT INTO [warnings_cats] (`title`, `description`, `period`) VALUES (:title, :description, :period)',
					array(
						array(':title', $_request->post('title')->strip(), PDO::PARAM_STR),
						array(':description', $_request->post('description')->strip(), PDO::PARAM_STR),
						array(':period', $_request->post('period')->strip(), PDO::PARAM_STR)
					)
				);
				
				// Jeśli dodałeś...
				if ($count)
				{
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('add', __('The category has been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'add', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('add',  __('Error! The category has not been added.'));
				$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'add', 'status' => 'error'));
			}
		}
		
		// Sprawdzanie czy przesłano formularz edycji
		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum(TRUE))
		{
			// Pobranie kolumny z danego identyfikatora
			$row = $_pdo->getRow('SELECT `id`, `title`, `description`, `period` FROM [warnings_cats] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Sprawdzanie czy pobrano dane
			if ($row)
			{
				// Umieszczenie pobranych danych w templatece
				$_tpl->assignGroup(array(
						'id' => $row['id'],
						'title' => $row['title'],
						'description' => $row['description'],
						'period' => $row['period']
					)
				);
			} 
			else
			{
				// Wyświetlenie wyjątku o braku identyfikatora
				throw new userException(__('There is no record with ID: :id!', array(':id' => $_request->get('id')->show())));
			}
		}
		
		if ($query = $_pdo->getData('SELECT `id`, `title`, `description`, `period` FROM [warnings_cats] ORDER BY `title`'))
		{
			foreach($query as $row)
            {	
				foreach ($periods as $val)
				{
					if($val['value'] === intval($row['period']))
					{
						$period = $val['description'];
						break;
					}
				}

				$cats_list[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => HELP::trimlink($row['description'], 50),
					'period' => $period
				);
			
			}
			
			$_tpl->assign('cats_list', $cats_list);
		}
		
		$_tpl->assign('periods', $periods);
	}
	elseif($_request->get('page')->show() === 'sett')
	{
		//Sekcja ustawień
	}
	elseif ($_request->get('page')->show() === 'warnings')
	{
		// Wyświetlenie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			// Wyświetli komunikat
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
				array(
					'add' => array(
						__('The warnings has been added.'), __('Error! The warnings has not been added.')
					),
					'edit' => array(
						__('The warnings has been edited.'), __('Error! The warnings has not been edited.')
					),
					'delete' => array(
						__('The warnings has been deleted.'), __('Error! The warnings has not been deleted.')
					)
				)
			);
		}

		// Sprawdzenie akcji usuwania albumu
		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum(TRUE))
		{
			// Usuń wpis
			$count = $_pdo->exec('DELETE FROM [warnings] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Jeśli usunęłeś...
			if ($count)
			{
				// Przekierowanie dla komunikatu sukcesu
				$_log->insertSuccess('delete', __('The warnings has been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'warnings', 'act' => 'delete', 'status' => 'ok'));
			}
			
			// Nie usunąłeś... Przekierowanie dla komunikatu błędu
			$_log->insertFail('delete',  __('Error! The warnings has not been deleted.'));
			$_request->redirect(FILE_PATH, array('page' => 'warnings', 'act' => 'delete', 'status' => 'error'));
		}
		
		// Sprawdzanie czy przesłano formularz
		if ($_request->post('save')->show()) 
		{
			if ($_request->post('title')->strip() === '')
			{
				throw new systemException('Empty field title');
			}
			
			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum(TRUE)) 
			{
				// Wykonaj zapytania
				$count = $_pdo->exec('
					UPDATE [warnings]
					SET `guilty` = :guilty, `title` = :title, `description` = :description, `cat` = :cat, `datestamp` = :datestamp, `expiry` = :expiry, `notification` = :notification
					WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':guilty', $_request->post('guilty')->isNum(TRUE), PDO::PARAM_INT),
						array(':title', $_request->post('title')->strip(), PDO::PARAM_STR),
						array(':description', $_request->post('description')->strip(), PDO::PARAM_STR),
						array(':cat', $_request->post('cat')->strip(), PDO::PARAM_STR),
						array(':datestamp', time(), PDO::PARAM_INT),
						array(':expiry', strtotime($_request->post('expiry')->strip()), PDO::PARAM_INT),
						array(':notification', $_request->post('notification')->show() ? intval($_request->post('notification')->show()) : 0, PDO::PARAM_INT)
					)
				);

				// Jeśli edytował...
				if ($count)
				{
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('edit', __('The warning has been edited.'));
					$_request->redirect(FILE_PATH, array('page' => 'warnings', 'act' => 'edit', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('edit',  __('Error! The warning has not been edited.'));
				$_request->redirect(FILE_PATH, array('page' => 'warnings', 'act' => 'edit', 'status' => 'error'));
				
			}
			else
			{		
				// Tu będą zapytania PDO
				$count = $_pdo->exec('INSERT INTO [warnings] (`guilty`, `sender`, `title`, `description`, `cat`, `datestamp`, `expiry`, `notification`) VALUES (:guilty, :sender, :title, :description, :cat, :datestamp, :expiry, :notification)',
					array(
						array(':guilty', $_request->post('guilty')->isNum(TRUE), PDO::PARAM_INT),
						array(':sender', $_request->post('sender')->isNum(TRUE), PDO::PARAM_STR),
						array(':title', $_request->post('title')->strip(), PDO::PARAM_STR),
						array(':description', $_request->post('description')->strip(), PDO::PARAM_STR),
						array(':cat', $_request->post('cat')->strip(), PDO::PARAM_STR),
						array(':datestamp', time(), PDO::PARAM_INT),
						array(':expiry', strtotime($_request->post('expiry')->strip()), PDO::PARAM_INT),
						array(':notification', $_request->post('notification')->show() ? intval($_request->post('notification')->show()) : 0, PDO::PARAM_INT)
					)
				);
				
				// Jeśli dodałeś...
				if ($count)
				{
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('add', __('The warning has been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'warnings', 'act' => 'add', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('add',  __('Error! The warning has not been added.'));
				$_request->redirect(FILE_PATH, array('page' => 'warnings', 'act' => 'add', 'status' => 'error'));
			}
		}
		
		// Sprawdzanie czy przesłano formularz edycji
		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum(TRUE))
		{
			// Pobranie kolumny z danego identyfikatora
			$row = $_pdo->getRow('SELECT * FROM [warnings] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Sprawdzanie czy pobrano dane
			if ($row)
			{	
				// Umieszczenie pobranych danych w templatece
				$_tpl->assignGroup(array(
						'id' => $row['id'],
						'guilty' => $row['guilty'],
						'sender' => $row['sender'],
						'title' => $row['title'],
						'description' => $row['description'],
						'expiry' => date('d.m.Y', $row['expiry']),
						'notification' => $row['notification']
					)
				);
				$_tpl->assign('edit', TRUE);
			} 
			else
			{
				// Wyświetlenie wyjątku o braku identyfikatora
				throw new userException(__('There is no record with ID: :id!', array(':id' => $_request->get('id')->show())));
			}
		}
		
		if ($query = $_pdo->getData('SELECT `id`, `title`, `period` FROM [warnings_cats] ORDER BY `title`'))
		{
			foreach($query as $row)
			{
				foreach ($periods as $val)
				{
					if($val['value'] === intval($row['period']))
					{
						$period = $val['description'];
						break;
					}
				}
				
				$cats[] = array(
					'title' => $row['title'],
					'id' => $row['id'],
					'period' => $period
				);
			}
			
			$_tpl->assign('cats', $cats);
		}
			
		// Sprawdzanie czy pobrano dane
		if ($query = $_pdo->getData('SELECT * FROM [warnings] ORDER BY `datestamp`'))
		{
			foreach($query as $row)
            {
				// Umieszczenie pobranych danych w templatece
				$warnings_list[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => $row['description'],
					'datestamp' => $row['datestamp'],
					'expiry' => $row['expiry'],
					'guilty' => $_user->getUsername($row['guilty'])
				);
			}
			$_tpl->assign('warnings_list', $warnings_list);
		}
		
		$_tpl->assign('sender', 
			array(
				'name' => $_user->getUsername(),
				'id' => $_user->get('id')
			)
		);
	}
	else
	{
		// Przekierowanie na domyślną podstronę
		$_request->redirect(FILE_PATH, array('page' => 'warnings'));
	}

	$_tpl->assignGroup(array(
		'page' => $_request->get('page')->show(),
	));	
	
	$_tpl->template('admin.tpl');	
}
catch(uploadException $exception)
{
    uploadErrorHandler($exception);
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