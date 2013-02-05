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

	$_locale->moduleLoad('admin', 'gallery');

        if ( ! $_user->hasPermission('module.gallery.admin'))
        {
            throw new userException(__('Access denied'));
        }

        $_tpl = new AdminModuleIframe('gallery');
	
	include DIR_MODULES.'gallery'.DS.'class'.DS.'Sett.php';
	include DIR_MODULES.'gallery'.DS.'class'.DS.'Image.php';
	include DIR_MODULES.'gallery'.DS.'config.php';
	
	$_tpl->assign('config', $mod_info);
	
	// Inicjalizacja klas
	! class_exists('GallerySett') || $_gallery_sett = New GallerySett($_system, $_pdo);
	! class_exists('Image') || $_image = New Image($_gallery_sett);
	! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);
	
	// --> Statystyki 
	$stats['cats'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_cats]');
	$stats['albums'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_albums]');
	$stats['photos'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [gallery_photos]');
	
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
			// Sprawdzenie czy usuwana kategoria nie zawiera w sobie albumów
			$query = $_pdo->getData('SELECT `title` FROM [gallery_albums] WHERE `cat`=  :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Nie zawiera albumów
			if ( ! $_pdo->getRowsCount($query))
			{			
				// Pobranie kolejności kategorii dla wszystkich elementów
				$row = $_pdo->getRow('SELECT `order`, `file_name` FROM [gallery_cats] WHERE `id`=  :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				
				// Jeśli są wyniki...
				if ($row)
				{
					// Zmień kolejność wszystkich kategorii o większej kolejności od usuwanej kategorii
					$_pdo->exec('UPDATE [gallery_cats] SET `order`=`order`-1 WHERE `order` >= :order AND `order` > 0',
						array(':order', $row['order'], PDO::PARAM_INT)
					);
					
					$_image->removePhotos(DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS.'cats'.DS, $row['file_name']);
				}
				
				// Usuń kategorię
				$count = $_pdo->exec('DELETE FROM [gallery_cats] WHERE `id` = :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				
				// Jeśli usunęłeś...
				if ($count)
				{
					// Usunięcie tagu
					$_tag->delTag('GALLERY_CATS', $_request->get('id')->show());
					
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
				// Wyświetl wyjątek o istniejących albumach w usuwanej kategorii
				foreach($query as $row)
				{
					$albums[] = $row['title'];
				}
				
				throw new userException(__('Not empty cat', array(':albums' => implode(', ', $albums))));
			}
		}
		
		// Sprawdzanie czy przesłano formularz
		if ($_request->post('save')->show()) 
		{
			$title = $_request->post('title')->strip();
			$description = $_request->post('description')->strip();
			$file_name = strtolower($_request->post('title')->strip());
			$keyword = $_request->post('tag')->strip();
			$access = $_request->post('access')->show() ? $_request->post('access')->getNumArray() : array(0 => '0');
			$order = $_request->post('order')->isNum(TRUE);
			
			if (($_request->get('action')->show() === "edit") && $_request->get('id')->isNum(TRUE)) 
			{
				// Zapisz edytowane dane
				
				// Pobierz kolejność edytowanego rekordu
				$row = $_pdo->getRow('SELECT `order`, `file_name`  FROM [gallery_cats] WHERE `id`= :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				
				if ($_request->upload('file'))
				{
					$_image->removePhotos(DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS.'cats'.DS, $row['file_name']);
					
					$file_name = $file_name !== '' ? $_image->setPhotoName($file_name).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show())) : $_image->setPhotoName($_image->getPhotoNameWithExtension(strtolower($_request->file('file', 'name')->show()))).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show()));
				
					$path_upload = DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS;

					$_image->createDir($path_upload, 'cats', 0777);
					$_image->createDir($path_upload.'cats'.DS, 'thumbnail', 0777);

					$path_thumbnail = $path_upload.DS.'cats'.DS.'thumbnail'.DS;

					$path_url = ADDR_SITE.'modules/gallery/templates/images/upload/cats/';

					if (file_exists($path_thumbnail.'_thumbnail_'.$file_name))
					{
						throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_thumbnail_'.$file_name)));
					}

					if (file_exists($path_thumbnail.'_square_thumbnail_'.$file_name))
					{
						throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_square_thumbnail_'.$file_name)));
					}

					// Przenieś podany plik w wskazaną lokalizacje, zmień jego nazwę
					move_uploaded_file($_request->file('file', 'tmp_name')->show(), $path_thumbnail.$file_name);

					// Utwórz miniaturkę o podanej wysokości i szerokości
					// Do podglądu małego
					$_image->createThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'), $_gallery_sett->get('thumbnail_hight'));

					// Utwórz miniaturkę o podanym wymiarza szerokość i wysokość taka sama
					// Tworzy miniaturkę podglądową do galeri
					$_image->createSquareThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_square_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'));

					// Usuwa oryginalny plik
					unlink($path_thumbnail.$file_name);
				}
				else
				{
					$file_name = $row['file_name'];
				}
				
				// Zmiana kolejności rekordów jesli jest potrzeba
				if ($order > $row['order']) 
				{
					$_pdo->exec('UPDATE [gallery_cats] SET `order`=`order`-1 WHERE `order` > :old_order AND `order` <= :new_order AND `order` > 0',
						array(
							array(':new_order', $order, PDO::PARAM_INT),
							array(':old_order', $row['order'], PDO::PARAM_INT)
						)
					);
				} 
				elseif ($order < $row['order']) 
				{
					$_pdo->exec('UPDATE [gallery_cats] SET `order`=`order`-1  WHERE `order` < :old_order AND `order` >= :new_order AND `order` > 0',
						array(
							array(':new_order', $order, PDO::PARAM_INT),
							array(':old_order', $row['order'], PDO::PARAM_INT)
						)
					);
				}
				
				// Wykonaj zapytania
				$count = $_pdo->exec('
					UPDATE [gallery_cats]
					SET `title` = :title, `description` = :description,  `file_name` = :file_name, `access` = :access, `order` = :order, `datestamp` = '.time().'
					WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':file_name', $file_name, PDO::PARAM_STR),
						array(':access', HELP::implode($access), PDO::PARAM_STR),
						array(':order', $order, PDO::PARAM_INT)
					)
				);
				
				// Jeśli edytował...
				if ($count)
				{
					// Aktualizacja tagu
					$_tag->updTag('GALLERY_CATS', $_request->get('id')->show(), $keyword, $access);
					
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
				$file_name = $file_name !== '' ? $_image->setPhotoName($file_name).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show())) : $_image->setPhotoName($_image->getPhotoNameWithExtension(strtolower($_request->file('file', 'name')->show()))).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show()));
			
				$path_upload = DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS;

				$_image->createDir($path_upload, 'cats', 0777);
				$_image->createDir($path_upload.'cats'.DS, 'thumbnail', 0777);

				$path_thumbnail = $path_upload.DS.'cats'.DS.'thumbnail'.DS;

				$path_url = ADDR_SITE.'modules/gallery/templates/images/upload/cats/';

				if (file_exists($path_thumbnail.'_thumbnail_'.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_thumbnail_'.$file_name)));
				}

				if (file_exists($path_thumbnail.'_square_thumbnail_'.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_square_thumbnail_'.$file_name)));
				}

				// Przenieś podany plik w wskazaną lokalizacje, zmień jego nazwę
				move_uploaded_file($_request->file('file', 'tmp_name')->show(), $path_thumbnail.$file_name);

				// Utwórz miniaturkę o podanej wysokości i szerokości
				// Do podglądu małego
				$_image->createThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'), $_gallery_sett->get('thumbnail_hight'));

				// Utwórz miniaturkę o podanym wymiarza szerokość i wysokość taka sama
				// Tworzy miniaturkę podglądową do galeri
				$_image->createSquareThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_square_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'));

				// Usuwa oryginalny plik
				unlink($path_thumbnail.$file_name);
			
				// Nie podano kolejności
				if ( ! $order) 
				{
					// Pobranie maskymalnego elementu z pola order
					$order = $_pdo->getMaxValue('SELECT MAX(`order`) FROM [gallery_cats]');
				}
				
				// Zmiana kolejności elementów większych-równych elementowi dodawanemu
				$_pdo->exec('UPDATE [gallery_cats] SET `order`=`order`+1 WHERE `order`>= :order',
					array(':order', $order, PDO::PARAM_INT)
				);
				
				// Tu będą zapytania PDO
				$count = $_pdo->exec('INSERT INTO [gallery_cats] (`title`, `description`, `file_name`, `access`, `order`, `datestamp`) VALUES (:title, :description, :file_name, :access, :order, '.time().')',
					array(
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':file_name', $file_name, PDO::PARAM_STR),
						array(':access', HELP::implode($access), PDO::PARAM_STR),
						array(':order', $order, PDO::PARAM_INT)
					)
				);
				
				// Jeśli dodałeś...
				if ($count)
				{
					// Dodanie tagu
					$_tag->addTag('GALLERY_CATS', $_pdo->lastInsertId(), $keyword, $access);
					
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
		if (($_request->get('action')->show() === 'edit') && $_request->get('id')->isNum(TRUE))
		{
			// Pobranie kolumny z danego identyfikatora
			$row = $_pdo->getRow('SELECT `id`, `title`, `description`, `access`, `order` FROM [gallery_cats] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Sprawdzanie czy pobrano dane
			if ($row)
			{
				$keyword = array();
				if ($keys = $_tag->getTag('GALLERY_CATS', $_request->get('id')->show())){
					foreach($keys as $var){
						$keyword[] = $var['value'];
					}
				}
				
				// Umieszczenie pobranych danych w templatece
				$_tpl->assignGroup(array(
						'id' => $row['id'],
						'title' => $row['title'],
						'description' => $row['description'],
						'keyword' => $keyword,
						'access' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['access']), TRUE),
						'order' => $row['order']
					)
				);
			} 
			else
			{
				// Wyświetlenie wyjątku o braku identyfikatora
				throw new userException(__('There is no record with ID: :id!', array(':id' => $_request->get('id')->show())));
			}
		}
		else
		{
			$_tpl->assign('order', $_pdo->getMaxValue('SELECT MAX(`order`) FROM [gallery_cats]')+1);
			$_tpl->assign('access', $_tpl->getMultiSelect($_user->getViewGroups(), '0', TRUE));
		}
		
		// Persowanie danych do templateki
		$query = $_pdo->getData('SELECT * FROM [gallery_cats] ORDER BY `order`');
		
		if ($_pdo->getRowsCount($query))
		{
			foreach($query as $row)
            {
				$cats_list[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => HELP::trimlink($row['description'], 100),
					'order' => $row['order'],
					'datestamp' => $row['datestamp'],
					'access' => $_user->groupArrIDsToNames(HELP::explode($row['access'])),
				);
			
			}
			$_tpl->assign('cats_list', $cats_list);
		}
	}
	elseif($_request->get('page')->show() === 'albums')
	{
		// Dodawania albumów
		
		// Wyświetlenie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			// Wyświetli komunikat
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
				array(
					'add' => array(
						__('The album has been added.'), __('Error! The album has not been added.')
					),
					'edit' => array(
						__('The album has been edited.'), __('Error! The album has not been edited.')
					),
					'delete' => array(
						__('The album has been deleted.'), __('Error! The album has not been deleted.')
					)
				)
			);
		}

		// Sprawdzenie akcji usuwania albumu
		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum(TRUE))
		{
			// Sprawdzenie czy usuwana kategoria nie zawiera w sobie albumów
			$query = $_pdo->getData('SELECT `title` FROM [gallery_photos] WHERE `album`= '.$_request->get('id')->show());
			
			// Nie zawiera albumów
			if ( ! $_pdo->getRowsCount($query))
			{			
				// Pobranie kolejności albumu dla wszystkich elementów
				$row = $_pdo->getRow('SELECT `order`, `file_name` FROM [gallery_albums] WHERE `id`= '.$_request->get('id')->show());
				
				// Jeśli są wyniki...
				if ($row)
				{
					$_image->removePhotos(DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS.'albums'.DS, $row['file_name']);
				
					// Zmień kolejność wszystkich albumu o większej kolejności od usuwanej albumu
					$_pdo->exec('UPDATE [gallery_albums] SET `order`=`order`-1 WHERE `order` >= :order AND `order` > 0',
						array(':order', $row['order'], PDO::PARAM_INT)
					);
				}
				
				// Usuń kategorię
				$count = $_pdo->exec('DELETE FROM [gallery_albums] WHERE `id` = :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				
				// Jeśli usunęłeś...
				if ($count)
				{
					// Usunięcie tagu
					$_tag->delTag('GALLERY_ALBUMS', $_request->get('id')->show());
					
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('delete', __('The album has been deleted.'));
					$_request->redirect(FILE_PATH, array('page' => 'albums', 'act' => 'delete', 'status' => 'ok'));
				}
				
				// Nie usunąłeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('delete',  __('Error! The album has not deleted'));
				$_request->redirect(FILE_PATH, array('page' => 'albums', 'act' => 'delete', 'status' => 'error'));
				
			} 
			else
			{
				// Wyświetl wyjątek o istniejących albumach w usuwanej albumu
				foreach($query as $row)
				{
					$photos[] = $row['title'];
				}
				
				throw new userException(__('Not empty album', array(':images' => implode(', ', $photos))));
			}
		}
		
		// Sprawdzanie czy przesłano formularz
		if ($_request->post('save')->show()) 
		{
			$title = $_request->post('title')->strip();
			$description = $_request->post('description')->strip();
			$file_name = strtolower($_request->post('title')->strip());
			$keyword = $_request->post('tag')->strip();
			$cat = $_request->post('cat')->isNum(TRUE);
			$access = $_request->post('access')->show() ? $_request->post('access')->getNumArray() : array(0 => '0');
			$order = $_request->post('order')->isNum(TRUE);
			
			if (($_request->get('action')->show() === "edit") && $_request->get('id')->isNum(TRUE)) 
			{
				// Zapisz edytowane dane
				
				// Pobierz kolejność edytowanego rekordu
				$row = $_pdo->getRow('SELECT `order`, `file_name` FROM [gallery_albums] WHERE `id`= :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				
				if ($_request->upload('file'))
				{
					$_image->removePhotos(DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS.'albums'.DS, $row['file_name']);
					
					$file_name = $file_name !== '' ? $_image->setPhotoName($file_name).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show())) : $_image->setPhotoName($_image->getPhotoNameWithExtension(strtolower($_request->file('file', 'name')->show()))).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show()));
				
					$path_upload = DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS;

					$_image->createDir($path_upload, 'albums', 0777);
					$_image->createDir($path_upload.'albums'.DS, 'thumbnail', 0777);

					$path_thumbnail = $path_upload.DS.'albums'.DS.'thumbnail'.DS;

					$path_url = ADDR_SITE.'modules/gallery/templates/images/upload/albums/';

					if (file_exists($path_thumbnail.'_thumbnail_'.$file_name))
					{
						throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_thumbnail_'.$file_name)));
					}

					if (file_exists($path_thumbnail.'_square_thumbnail_'.$file_name))
					{
						throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_square_thumbnail_'.$file_name)));
					}

					// Przenieś podany plik w wskazaną lokalizacje, zmień jego nazwę
					move_uploaded_file($_request->file('file', 'tmp_name')->show(), $path_thumbnail.$file_name);

					// Utwórz miniaturkę o podanej wysokości i szerokości
					// Do podglądu małego
					$_image->createThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'), $_gallery_sett->get('thumbnail_hight'));

					// Utwórz miniaturkę o podanym wymiarza szerokość i wysokość taka sama
					// Tworzy miniaturkę podglądową do galeri
					$_image->createSquareThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_square_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'));

					// Usuwa oryginalny plik
					unlink($path_thumbnail.$file_name);
				}
				else
				{
					$file_name = $row['file_name'];
				}
				
				// Zmiana kolejności rekordów jesli jest potrzeba
				if ($order > $row['order']) 
				{
					$_pdo->exec('UPDATE [gallery_albums] SET `order`=`order`-1 WHERE `order` > :old_order AND `order` <= :new_order AND `order` > 0',
						array(
							array(':new_order', $order, PDO::PARAM_INT),
							array(':old_order', $row['order'], PDO::PARAM_INT)
						)
					);
				} 
				elseif ($order < $row['order']) 
				{
					$_pdo->exec('UPDATE [gallery_albums] SET `order`=`order`-1  WHERE `order` < :old_order AND `order` >= :new_order AND `order` > 0',
						array(
							array(':new_order', $order, PDO::PARAM_INT),
							array(':old_order', $row['order'], PDO::PARAM_INT)
						)
					);
				}
				
				// Wykonaj zapytania
				$count = $_pdo->exec('
					UPDATE [gallery_albums]
					SET `title` = :title, `description` = :description, `file_name` = :file_name, `cat` = :cat, `access` = :access, `order` = :order, `datestamp` = '.time().'
					WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':file_name', $file_name, PDO::PARAM_STR),
						array(':cat', $cat, PDO::PARAM_INT),
						array(':access', HELP::implode($access), PDO::PARAM_STR),
						array(':order', $order, PDO::PARAM_INT)
					)
				);
				
				// Jeśli edytował...
				if ($count)
				{
					// Edycja tagów
					$_tag->updTag('GALLERY_ALBUMS', $_request->get('id')->show(), $keyword, $access);
					
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('edit', __('The album has been edited.'));
					$_request->redirect(FILE_PATH, array('page' => 'albums', 'act' => 'edit', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('edit',  __('Error! The album has not been edited.'));
				$_request->redirect(FILE_PATH, array('page' => 'albums', 'act' => 'edit', 'status' => 'error'));
				
			}
			else
			{
				$file_name = $file_name !== '' ? $_image->setPhotoName($file_name).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show())) : $_image->setPhotoName($_image->getPhotoNameWithExtension(strtolower($_request->file('file', 'name')->show()))).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show()));
				
				$path_upload = DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS;

				$_image->createDir($path_upload, 'albums', 0777);
				$_image->createDir($path_upload.'albums'.DS, 'thumbnail', 0777);
				
				$path_thumbnail = $path_upload.DS.'albums'.DS.'thumbnail'.DS;
			
				$path_url = ADDR_SITE.'modules/gallery/templates/images/upload/albums/';
				
				if (file_exists($path_thumbnail.'_thumbnail_'.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_thumbnail_'.$file_name)));
				}
				
				if (file_exists($path_thumbnail.'_square_thumbnail_'.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_square_thumbnail_'.$file_name)));
				}
				
				// Przenieś podany plik w wskazaną lokalizacje, zmień jego nazwę
				move_uploaded_file($_request->file('file', 'tmp_name')->show(), $path_thumbnail.$file_name);
				
				// Utwórz miniaturkę o podanej wysokości i szerokości
				// Do podglądu małego
				$_image->createThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'), $_gallery_sett->get('thumbnail_hight'));
				
				// Utwórz miniaturkę o podanym wymiarza szerokość i wysokość taka sama
				// Tworzy miniaturkę podglądową do galeri
				$_image->createSquareThumbnail($path_thumbnail.$file_name, $path_thumbnail.'_square_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'));
				
				// Usuwa oryginalny plik
				unlink($path_thumbnail.$file_name);

				// Nie podano kolejności
				if ( ! $order) 
				{
					// Pobranie maskymalnego elementu z pola order
					$order = $_pdo->getMaxValue('SELECT MAX(`order`) FROM [gallery_albums]');
				}
				
				// Zmiana kolejności elementów większych-równych elementowi dodawanemu
				$_pdo->exec('UPDATE [gallery_albums] SET `order`=`order`+1 WHERE `order`>= :order',
					array(':order', $order, PDO::PARAM_INT)
				);
				
				// Tu będą zapytania PDO
				$count = $_pdo->exec('INSERT INTO [gallery_albums] (`title`, `description`, `file_name`, `cat`, `access`, `order`, `datestamp`) VALUES (:title, :description, :file_name, :cat, :access, :order, '.time().')',
					array(
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':file_name', $file_name, PDO::PARAM_STR),
						array(':cat', $cat, PDO::PARAM_INT),
						array(':access', HELP::implode($access), PDO::PARAM_STR),
						array(':order', $order, PDO::PARAM_INT)
					)
				);
				
				// Jeśli dodałeś...
				if ($count)
				{
					// Dodanie tagu
					$_tag->addTag('GALLERY_ALBUMS', $_pdo->lastInsertId(), $keyword, $access);
					
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('add', __('The album has been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'albums', 'act' => 'add', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('add',  __('Error! The album has not been added.'));
				$_request->redirect(FILE_PATH, array('page' => 'albums', 'act' => 'add', 'status' => 'error'));
			}
		}
		
		// Sprawdzanie czy przesłano formularz edycji
		if (($_request->get('action')->show() === "edit") && $_request->get('id')->isNum(TRUE))
		{
			// Pobranie kolumny z danego identyfikatora
			$row = $_pdo->getRow('SELECT `id`, `title`, `description`, `cat`, `access`, `order` FROM [gallery_albums] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Sprawdzanie czy pobrano dane
			if ($row)
			{
				$keyword = array();
				if ($keys = $_tag->getTag('GALLERY_ALBUMS', $_request->get('id')->show())){
					foreach($keys as $var){
						$keyword[] = $var['value'];
					}
				}
				
				// Umieszczenie pobranych danych w templatece
				$_tpl->assignGroup(array(
						'id' => $row['id'],
						'title' => $row['title'],
						'description' => $row['description'],
						'keyword' => $keyword,
						'cat' => $row['cat'],
						'access' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['access']), TRUE),
						'order' => $row['order']
					)
				);
			} 
			else
			{
				// Wyświetlenie wyjątku o braku identyfikatora
				throw new userException(__('There is no record with ID: :id!', array(':id' => $_request->get('id')->show())));
			}
		}
		else
		{
			$_tpl->assign('order', $_pdo->getMaxValue('SELECT MAX(`order`) FROM [gallery_albums]')+1);
			$_tpl->assign('access', $_tpl->getMultiSelect($_user->getViewGroups(), '0', TRUE));
		}
		
		if ($stats['cats'])
		{
			// Persowanie danych do templateki
			
			$cats = array(); $query = $_pdo->getData('SELECT `id`, `title` FROM [gallery_cats] ORDER BY `id`');
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					$cats[] = array(
						'title' => $row['title'],
						'id' => $row['id']
					);
				}
			}
			
			$_tpl->assign('cats', $cats);
			
			$query = $_pdo->getData('
				SELECT ga.*, gc.`title` AS cat_name FROM [gallery_albums] ga
				LEFT JOIN [gallery_cats] gc ON gc.`id`=ga.`cat`
				ORDER BY ga.`order`
			');
			
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					$albums_list[] = array(
						'id' => $row['id'],
						'title' => $row['title'],
						'cat_name' => $row['cat_name'],
						'description' => HELP::trimlink($row['description'], 100),
						'cat' => $row['cat'],
						'order' => $row['order'],
						'datestamp' => $row['datestamp'],
						'access' => $_user->groupArrIDsToNames(HELP::explode($row['access'])),
					);
				}
				$_tpl->assign('albums_list', $albums_list);
			}
		}
	}
	elseif($_request->get('page')->show() === 'photos')
	{
	//Dodawanie obazów
	
		// Wyświetlenie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			// Wyświetli komunikat
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
				array(
					'add' => array(
						__('The image has been added.'), __('Error! The image has not been added.')
					),
					'edit' => array(
						__('The image has been edited.'), __('Error! The image has not been edited.')
					),
					'delete' => array(
						__('The image has been deleted.'), __('Error! The image has not been deleted.')
					)
				)
			);
		}

		// Sprawdzenie akcji usuwania albumu
		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum(TRUE))
		{
			// Pobranie kolejności albumu dla wszystkich elementów
			$row = $_pdo->getRow('SELECT `file_name`, `path_absolute`, `order` FROM [gallery_photos] WHERE `id`= '.$_request->get('id')->show());
			
			// Jeśli są wyniki...
			if ($row)
			{
				// Zmień kolejność wszystkich zdjęc o większej kolejności od usuwanej zdjęcia
				$_pdo->exec('UPDATE [gallery_photos] SET `order`=`order`-1 WHERE `order` >= :order AND `order` > 0',
					array(':order', $row['order'], PDO::PARAM_INT)
				);
				
				$_image->removePhotos($row['path_absolute'], $row['file_name']);
			}
			
			// Tutaj funkcje i metody usuwające zdjęcia dla podanego ID.
			// Na następnej zmianie :D
			
			// Usuń wpis
			$count = $_pdo->exec('DELETE FROM [gallery_photos] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Jeśli usunęłeś...
			if ($count)
			{
				// Usunięcie tagu
				$_tag->delTag('GALLERY_PHOTOS', $_request->get('id')->show());
					
				// Przekierowanie dla komunikatu sukcesu
				$_log->insertSuccess('delete', __('The image has been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'photos', 'act' => 'delete', 'status' => 'ok'));
			}
			
			// Nie usunąłeś... Przekierowanie dla komunikatu błędu
			$_log->insertFail('delete',  __('Error! The image has not been deleted.'));
			$_request->redirect(FILE_PATH, array('page' => 'photos', 'act' => 'delete', 'status' => 'error'));
		}
		
		// Sprawdzanie czy przesłano formularz
		if ($_request->post('save')->show()) 
		{
			$title = $_request->post('title')->strip();
			$file_name = strtolower($_request->post('file_name')->strip());
			$description = $_request->post('description')->strip();
			$keyword = $_request->post('tag')->strip();;
			$album = $_request->post('album')->isNum(TRUE);
			$access = $_request->post('access')->show() ? $_request->post('access')->getNumArray() : array(0 => '0');
			$order = $_request->post('order')->isNum(TRUE);
			$width = $_request->post('width')->show() ? $_request->post('width')->show() : $_gallery_sett->get('photo_max_width');
			$hight = $_request->post('hight')->show() ? $_request->post('hight')->show() : $_gallery_sett->get('photo_max_hight');
			$watermark = $_request->post('watermark')->show() ? intval($_request->post('watermark')->show()) : 0;
			$comment = $_request->post('comment')->show() ? intval($_request->post('comment')->show()) : 0;
			$rating = $_request->post('rating')->show() ? intval($_request->post('rating')->show()) : 0;

			if ($title === '')
			{
				throw new systemException('Empty field');
			}
			
			if (($_request->get('action')->show() === "edit") && $_request->get('id')->isNum(TRUE)) 
			{
				// Zapisz edytowane dane
				
				// Pobierz kolejność edytowanego rekordu
				$row = $_pdo->getRow('SELECT `order` FROM [gallery_photos] WHERE `id`= :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);

				// Zmiana kolejności rekordów jesli jest potrzeba
				if ($order > $row['order']) 
				{
					$_pdo->exec('UPDATE [gallery_photos] SET `order`=`order`-1 WHERE `order` > :old_order AND `order` <= :new_order AND `order` > 0',
						array(
							array(':new_order', $order, PDO::PARAM_INT),
							array(':old_order', $row['order'], PDO::PARAM_INT)
						)
					);
				} 
				elseif ($order < $row['order']) 
				{
					$_pdo->exec('UPDATE [gallery_photos] SET `order`=`order`-1  WHERE `order` < :old_order AND `order` >= :new_order AND `order` > 0',
						array(
							array(':new_order', $order, PDO::PARAM_INT),
							array(':old_order', $row['order'], PDO::PARAM_INT)
						)
					);
				}
				
				// Wykonaj zapytania
				$count = $_pdo->exec('
					UPDATE [gallery_photos]
					SET `title` = :title, `description` = :description, `watermark` = :watermark, `comment` = :comment, `rating` = :rating, `album` = :album, `access` = :access, `order` = :order, `datestamp` = '.time().'
					WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':title', $title, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':watermark', $watermark, PDO::PARAM_INT),
						array(':comment', $comment, PDO::PARAM_INT),
						array(':rating', $rating, PDO::PARAM_INT),
						array(':album', $album, PDO::PARAM_INT),
						array(':access', HELP::implode($access), PDO::PARAM_STR),
						array(':order', $order, PDO::PARAM_INT)
					)
				);
				
				// Jeśli edytował...
				if ($count)
				{
					// Edycja tagów
					$_tag->updTag('GALLERY_PHOTOS', $_request->get('id')->show(), $keyword, $access);
					
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('edit', __('The image has been edited.'));
					$_request->redirect(FILE_PATH, array('page' => 'photos', 'act' => 'edit', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('edit',  __('Error! The image has not been edited.'));
				$_request->redirect(FILE_PATH, array('page' => 'photos', 'act' => 'edit', 'status' => 'error'));
				
			}
			else
			{
				if ($_request->file('file', 'error')->show())
				{
					throw new uploadException($_request->file('file', 'error')->show());
				}
				
				// Dozwolone nowe roszerzenia plików
				$_image->newImageExt(explode(', ', $_gallery_sett->get('allow_ext')));
				
				// Walidacja dopuszczalnego rozszerzenia pliku
				$_image->validExt(strtolower($_request->file('file', 'name')->show()));
				
				// Walidacja dopuszczalnej wagi pliku
				$_image->validSize($_request->file('file', 'size')->show());
				
				$file_name = $file_name !== '' ? $_image->setPhotoName($file_name).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show())) : $_image->setPhotoName($_image->getPhotoNameWithExtension(strtolower($_request->file('file', 'name')->show()))).$_image->getPhotoExt(strtolower($_request->file('file', 'name')->show()));
				
				$path_upload = DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'upload'.DS;
				
				$_image->createDir($path_upload, 'photos', 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y'), 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m'), 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m'), 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m').DS.date('d'), 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m').DS.date('d'), 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m').DS.date('d').DS.'original', 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m').DS.date('d').DS.'thumbnail', 0777);
				$_image->createDir($path_upload, 'photos'.DS.date('Y').DS.date('m').DS.date('d').DS.'watermark', 0777);
				
				$path_absolute 	= $path_upload.'photos'.DS.date('Y').DS.date('m').DS.date('d').DS;
				$path_original 	= $path_upload.'photos'.DS.date('Y').DS.date('m').DS.date('d').DS.'original'.DS;
				$path_thumbnail = $path_upload.'photos'.DS.date('Y').DS.date('m').DS.date('d').DS.'thumbnail'.DS;
				$path_watermark = $path_upload.'photos'.DS.date('Y').DS.date('m').DS.date('d').DS.'watermark'.DS;
				
				$path_url = ADDR_SITE.'modules/gallery/templates/images/upload/photos/'.date('Y').'/'.date('m').'/'.date('d').'/';
				
				if (file_exists($path_original.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_original.$file_name)));
				}
				
				if (file_exists($path_thumbnail.'_thumbnail_'.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_thumbnail_'.$file_name)));
				}
				
				if (file_exists($path_thumbnail.'_square_thumbnail_'.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_thumbnail.'_square_thumbnail_'.$file_name)));
				}
				
				if (file_exists($path_watermark.$file_name))
				{
					throw new systemException(__('Error: Image with that title (:iname) is already existing!', array(':iname' => $path_watermark.$file_name)));
				}
				
				// Przenieś podany plik w wskazaną lokalizacje, zmień jego nazwę
				move_uploaded_file($_request->file('file', 'tmp_name')->show(), $path_original.$file_name);
				
				$image_upload = $_image->getWidthAndHight($path_original.$file_name, TRUE);

				// Walidacja rozmarów wgranego pliku, jeśli za duży dokonaj zmieny rozmieru.
				if ($image_upload['x'] > $_gallery_sett->get('photo_max_width') || $image_upload['y'] > $_gallery_sett->get('photo_max_hight'))
				{
					if ($width !== '' || $hight !== '')
					{
						// Zmiana nazwy pliku na tymczasową _file.*
						rename($path_original.$file_name, $path_original.'_tmp_'.$file_name);
						
						// Wykorzystanie funkcji tworzącej miniaturkę do skalowania obrazka do wybranego rozmiaru
						$_image->createThumbnail($path_original.'_tmp_'.$file_name, $path_original.$file_name, $width, $hight);
						
						// Usunięcie pliku tymczasowego
						unlink($path_original.'_tmp_'.$file_name);
					}
				}

				// Znak wodny
				if ($watermark)
				{
					// Ustaw znak wondy
					$_image->setWatermark($_gallery_sett->get('watermark_logo'));
					
					// Wybierz plik na którym utworzysz znak wodny
					$_image->setInputPhoto($file_name, $path_original);
					
					// Wybierz ścieżkę w której zapiszesz stworzony plik z znakiem wodnym
					$_image->setOutputPhoto(NULL, $path_watermark);
				
					// Utwórz znak wodny
					$_image->createWatermark();
				}
				
				// Utwórz miniaturkę o podanej wysokości i szerokości
				// Do podglądu małego
				$_image->createThumbnail($path_original.$file_name, $path_thumbnail.'_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'), $_gallery_sett->get('thumbnail_hight'));
				
				// Utwórz miniaturkę o podanym wymiarza szerokość i wysokość taka sama
				// Tworzy miniaturkę podglądową do galeri
				$_image->createSquareThumbnail($path_original.$file_name, $path_thumbnail.'_square_thumbnail_'.$file_name, $_gallery_sett->get('thumbnail_width'));

				// Nie podano kolejności
				if ( ! $order) 
				{
					// Pobranie maskymalnego elementu z pola order
					$order = $_pdo->getMaxValue('SELECT MAX(`order`) FROM [gallery_photos]');
				}
				
				// Zmiana kolejności elementów większych-równych elementowi dodawanemu
				$_pdo->exec('UPDATE [gallery_photos] SET `order`=`order`+1 WHERE `order`>= :order',
					array(':order', $order, PDO::PARAM_INT)
				);
				
				// Tu będą zapytania PDO
				$count = $_pdo->exec('INSERT INTO [gallery_photos] (`title`, `file_name`, `path_absolute`,  `path_url`, `description`, `watermark`, `comment`, `rating`, `album`, `user`, `access`, `order`, `datestamp`) VALUES (:title, :file_name, :path_absolute, :path_url, :description, :watermark, :comment, :rating, :album, :user, :access, :order, '.time().')',
					array(
						array(':title', $title, PDO::PARAM_STR),
						array(':file_name', $file_name, PDO::PARAM_STR),
						array(':path_absolute', $path_absolute, PDO::PARAM_STR),
						array(':path_url', $path_url, PDO::PARAM_STR),
						array(':description', $description, PDO::PARAM_STR),
						array(':watermark', $watermark, PDO::PARAM_INT),
						array(':comment', $comment, PDO::PARAM_INT),
						array(':rating', $rating, PDO::PARAM_INT),
						array(':album', $album, PDO::PARAM_INT),
						array(':user', $_user->get('id'), PDO::PARAM_INT),
						array(':access', HELP::implode($access), PDO::PARAM_STR),
						array(':order', $order, PDO::PARAM_INT)
					)
				);
				
				// Jeśli dodałeś...
				if ($count)
				{
					// Dodanie tagu
					$_tag->addTag('GALLERY_PHOTOS', $_pdo->lastInsertId(), $keyword, $access);
					
					// Przekierowanie dla komunikatu sukcesu
					$_log->insertSuccess('add', __('The album has been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'photos', 'act' => 'add', 'status' => 'ok'));
				}
				
				// Nie dodałeś... Przekierowanie dla komunikatu błędu
				$_log->insertFail('add',  __('Error! The album has not been added.'));
				$_request->redirect(FILE_PATH, array('page' => 'photos', 'act' => 'add', 'status' => 'error'));
			}
		}
		
		// Sprawdzanie czy przesłano formularz edycji
		if (($_request->get('action')->show() === "edit") && $_request->get('id')->isNum(TRUE))
		{
			// Pobranie kolumny z danego identyfikatora
			$row = $_pdo->getRow('SELECT `id`, `title`, `description`, `watermark`, `comment`, `rating`, `album`, `access`, `order` FROM [gallery_photos] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			// Sprawdzanie czy pobrano dane
			if ($row)
			{
				$keyword = array();
				if ($keys = $_tag->getTag('GALLERY_PHOTOS', $_request->get('id')->show())){
					foreach($keys as $var){
						$keyword[] = $var['value'];
					}
				}
				
				// Umieszczenie pobranych danych w templatece
				$_tpl->assignGroup(array(
						'id' => $row['id'],
						'title' => $row['title'],
						'description' => $row['description'],
						'keyword' => $keyword,
						'album' => $row['album'],
						'watermark' => $row['watermark'],
						'comment' => $row['comment'],
						'rating' => $row['rating'],
						'access' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['access']), TRUE),
						'order' => $row['order']
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
		else
		{
			$_tpl->assign('order', $_pdo->getMaxValue('SELECT MAX(`order`) FROM [gallery_photos]')+1);
			$_tpl->assign('access', $_tpl->getMultiSelect($_user->getViewGroups(), '0', TRUE));
		}
		
		if ($stats['albums'])
		{
			// Persowanie danych do templateki
			$query = $_pdo->getData('
				SELECT a.`title` album_name, a.`id` album_id, c.`id` cat_id, c.`title` cat_name FROM [gallery_cats] c 
				LEFT JOIN [gallery_albums] a ON c.`id` = a.`cat` 
				ORDER BY a.`title` ASC, c.`title` ASC
			');
			
			// Grupownia pól wyboru
			$albums = array(); 
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					if ( ! isset($albums[$row['cat_id']]))
					{
						$albums[$row['cat_id']] = array('title' => $row['cat_name'], 'albums' => array());
					}
					if ( ! empty($row['album_name']))
					{
						$albums[$row['cat_id']]['albums'][] = array('title' => $row['album_name'], 'id' => $row['album_id']); 
					}
				}
			}
			$_tpl->assign('sett', array('photos_width' => $_gallery_sett->get('photo_max_width'), 'photos_hight' => $_gallery_sett->get('photo_max_hight')));

			$_tpl->assign('albums', $albums);
			
			$query = $_pdo->getData('
				SELECT gp.*, ga.`title` AS album_name FROM [gallery_photos] gp
				LEFT JOIN [gallery_albums] ga ON ga.`id`=gp.`album`
				ORDER BY ga.`order`
			');
			
			if ($_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					$photos_list[] = array(
						'id' => $row['id'],
						'title' => $row['title'],
						'album_name' => $row['album_name'],
						'description' => HELP::trimlink($row['description'], 100),
						'album' => $row['album'],
						'order' => $row['order'],
						'datestamp' => $row['datestamp'],
						'access' => $_user->groupArrIDsToNames(HELP::explode($row['access'])),
					);
				}
				
				$_tpl->assign('photos_list', $photos_list);
			}
		}
	}
	elseif($_request->get('page')->show() === 'sett')
	{
		if ( ! $_user->hasPermission('module.gallery.sett'))
		{
			throw new userException(__('Access denied'));
		}

		$_tpl = new AdminModuleIframe('gallery');
		
		if ($_request->post('save')->show())
		{
			$_gallery_sett->update(array(
					'animation_speed' => $_request->post('animation_speed')->strip(),
					'slideshow' => $_request->post('slideshow')->strip(),
					'autoplay_slideshow' => $_request->post('autoplay_slideshow')->strip(),
					'opacity' => $_request->post('opacity')->strip(),
					'show_title' => $_request->post('show_title')->strip(),
					'allow_resize' => $_request->post('allow_resize')->strip(),
					'default_width' => $_request->post('default_width')->strip(),
					'default_hight' => $_request->post('default_hight')->strip(),
					'counter_separator_label' => $_request->post('counter_separator_label')->strip(),
					'theme' => $_request->post('theme')->strip(),
					'horizontal_padding' => $_request->post('horizontal_padding')->strip(),
					'hideflash' => $_request->post('hideflash')->strip(),
					'wmode' => $_request->post('wmode')->strip(),
					'autoplay' => $_request->post('autoplay')->strip(),
					'modal' => $_request->post('modal')->strip(),
					'deeplinking' => $_request->post('deeplinking')->strip(),
					'overlay_gallery' => $_request->post('overlay_gallery')->strip(),
					'keyboard_shortcuts' => $_request->post('keyboard_shortcuts')->strip(),
					'social_tools' => addslashes($_request->post('social_tools')->show()) === '' ? 'false' : addslashes($_request->post('social_tools')->show()),
					'ie6_fallback' => $_request->post('ie6_fallback')->strip(),
					'allow_comment'  => $_request->post('allow_comment')->strip(),
					'allow_rating'  => $_request->post('allow_rating')->strip(),
					'thumb_compression'  => $_request->post('thumb_compression')->strip(),
					'thumbnail_width'  => $_request->post('thumbnail_width')->isNum(TRUE),
					'thumbnail_hight'  => $_request->post('thumbnail_hight')->isNum(TRUE),
					'photo_max_width'  => $_request->post('photo_max_width')->isNum(TRUE),
					'photo_max_hight'  => $_request->post('photo_max_hight')->isNum(TRUE),
					'watermark_logo'  => $_request->post('watermark_logo')->strip(),
					'allow_ext'  => $_request->post('allow_ext')->strip(),
					'cache_expire'  => $_request->post('cache_expire')->strip(),
					'max_file_size'  => $_request->post('max_file_size')->isNum(TRUE),
					'cats_per_page'  => $_request->post('cats_per_page')->isNum(TRUE),
					'albums_per_page'  => $_request->post('albums_per_page')->isNum(TRUE),
					'photos_per_page'  => $_request->post('photos_per_page')->isNum(TRUE),
					'title'  => $_request->post('title')->strip(),
					'description'  => $_request->post('description')->strip()
				)
			);
			
			$_tag->updTag('GALLERY_GLOBAL', 1, $_request->post('tag')->strip());
			
			$_tpl->printMessage('valid', $_log->insertSuccess('edit', __('Data has been saved.')));
		}
		
		if($_request->get('clearstatcache')->show())
		{
			$_tpl->printMessage('valid', __('Cache has been cleaned.'));
		}

		$keyword = array();
		if ($keys = $_tag->getTagFromSupplement('GALLERY_GLOBAL')){
			foreach($keys as $var){
				$keyword[] = $var['value'];
			}
		}
		
		$_tpl->assignGroup(array(
			'animation_speed' => $_gallery_sett->get('animation_speed'),
			'slideshow' => $_gallery_sett->get('slideshow'),
			'autoplay_slideshow' => $_gallery_sett->get('autoplay_slideshow'),
			'opacity' => $_gallery_sett->get('opacity'),
			'show_title' => $_gallery_sett->get('show_title'),
			'allow_resize' => $_gallery_sett->get('allow_resize'),
			'default_width' => $_gallery_sett->get('default_width'),
			'default_hight' => $_gallery_sett->get('default_hight'),
			'counter_separator_label' => $_gallery_sett->get('counter_separator_label'),
			'theme' => $_tpl->createSelectOpts($_files->createFileList(DIR_MODULES.'gallery'.DS.'templates'.DS.'images'.DS.'prettyPhoto', array(), TRUE, 'folders'), $_gallery_sett->get('theme')),
			'horizontal_padding' => $_gallery_sett->get('horizontal_padding'),
			'hideflash' => $_gallery_sett->get('hideflash'),
			'wmode' => $_gallery_sett->get('wmode'),
			'autoplay' => $_gallery_sett->get('autoplay'),
			'modal' => $_gallery_sett->get('modal'),
			'deeplinking' => $_gallery_sett->get('deeplinking'),
			'overlay_gallery' => $_gallery_sett->get('overlay_gallery'),
			'keyboard_shortcuts' => $_gallery_sett->get('keyboard_shortcuts'),
			'social_tools' => stripslashes($_gallery_sett->get('social_tools')) === 'false' ? '' : stripslashes($_gallery_sett->get('social_tools')),
			'ie6_fallback' => $_gallery_sett->get('ie6_fallback'),
			'allow_comment' => $_gallery_sett->get('allow_comment'),
			'allow_rating' => $_gallery_sett->get('allow_rating'),
			'thumb_compression' => $_gallery_sett->get('thumb_compression'),
			'thumbnail_width' => $_gallery_sett->get('thumbnail_width'),
			'thumbnail_hight' => $_gallery_sett->get('thumbnail_hight'),
			'photo_max_width' => $_gallery_sett->get('photo_max_width'),
			'photo_max_hight' => $_gallery_sett->get('photo_max_hight'),
			'watermark_logo' => $_gallery_sett->get('watermark_logo'),
			'allow_ext' => $_gallery_sett->get('allow_ext'),
			'max_file_size' => $_gallery_sett->get('max_file_size'),
			'cache_expire' => $_gallery_sett->get('cache_expire'),
			'max_file_size_in_kb' => $_image->getConvertedSizeFromBytes($_gallery_sett->get('max_file_size')),
			'upload_max_filesize' => ini_get('upload_max_filesize'),
			'max_execution_time' => ini_get('max_execution_time'),
			'cats_per_page' => $_gallery_sett->get('cats_per_page'),
			'albums_per_page' => $_gallery_sett->get('albums_per_page'),
			'photos_per_page' => $_gallery_sett->get('photos_per_page'),
			'title' => $_gallery_sett->get('title'),
			'description' => $_gallery_sett->get('description'),
			'keyword' => $keyword
		));
		
		if($_request->get('action')->show() === 'clear_cache')
		{
			$_request->redirect(FILE_PATH, array('page' => 'sett', 'clearstatcache' => $_system->clearcache('gallery')));
		}
	}
	else
	{
		// Przekierowanie na domyślną podstronę
		$_request->redirect(FILE_PATH, array('page' => 'cats'));
	}

	$_tpl->assignGroup(array(
		'page' => $_request->get('page')->show(),
		'stats' => $stats
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