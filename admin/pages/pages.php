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

// Paweł jak będziesz dokańczać strony swoje to pole z nazwą strony niech nie nazywa się 'name' tylko 'title'
// W innym przypadku koliduje to z API tagów. To samo ma się do opisu tzw. description
// root/system/class/tag.php ~110.
try
{
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';

    if ( ! $_user->hasPermission('admin.pages'))
    {
        throw new userException(__('Access denied'));
    }

	/**
	 * Załączam najpierw plik językowy, gdyż w klasie może wystąpić błąd.
	 * Wtedy komunikat zostanie wyświetlony w odpowiednim języku.
	 */
	$_locale->load('pages');

	/** Załączam model-klasę do obsługi Stron **/
	require DIR_CLASS.'Pages.php';

    $_tpl = new Iframe;

	// Zarządzanie typami treści
	if ($_request->get('page')->show() === 'types')
	{
		//------------------------------------

		// Wyświetlanie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
				array(
					'add' => array(
						__('The entry has been added.'), __('Error! The entry has not been added.')
					),
					'edit' => array(
						__('The entry has been edited.'), __('Error! The entry has not been edited.')
					),
					'delete' => array(
						__('The entry has been deleted.'), __('Error! The entry has not been deleted.')
					)
				)
			);
		}

		//------------------------------------

		// Zapis danych
		if ($_request->post('save')->show())
		{
			$bind = array(
				array(':name', $_request->post('name')->strip(), PDO::PARAM_STR),
				array(':for_news_page', $_request->post('for_news_page')->isNum(TRUE), PDO::PARAM_INT),
				array(':user_allow_edit_own', $_request->post('user_allow_edit_own')->isNum(TRUE), PDO::PARAM_INT),
				array(':user_allow_use_wysiwyg', $_request->post('user_allow_use_wysiwyg')->isNum(TRUE), PDO::PARAM_INT),
				array(':insight_groups', $_request->post('insight_groups')->filters('getNumArray', 'implode'), PDO::PARAM_STR),
				array(':editing_groups', $_request->post('editing_groups')->filters('getNumArray', 'implode'), PDO::PARAM_STR),
				array(':submitting_groups', $_request->post('submitting_groups')->filters('getNumArray', 'implode'), PDO::PARAM_STR),
				array(':show_preview', $_request->post('show_preview')->isNum(TRUE), PDO::PARAM_INT),
				array(':add_author', $_request->post('add_author')->isNum(TRUE), PDO::PARAM_INT),
				array(':change_author', $_request->post('change_author')->isNum(TRUE), PDO::PARAM_INT),
				array(':add_last_editing_date', $_request->post('add_last_editing_date')->isNum(TRUE), PDO::PARAM_INT),
				array(':change_date', $_request->post('change_date')->isNum(TRUE), PDO::PARAM_INT),
				array(':show_author', $_request->post('show_author')->isNum(TRUE), PDO::PARAM_INT),
				array(':show_date', $_request->post('show_date')->isNum(TRUE), PDO::PARAM_INT),
				array(':show_category', $_request->post('show_category')->isNum(TRUE), PDO::PARAM_INT),
				array(':show_tags', $_request->post('show_tags')->isNum(TRUE), PDO::PARAM_INT),
				array(':show_type', $_request->post('show_type')->isNum(TRUE), PDO::PARAM_INT),
				array(':user_allow_comments', $_request->post('user_allow_comments')->isNum(TRUE), PDO::PARAM_INT)
			);

			// Zapis edycji
			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
			{
				$_pdo->exec('UPDATE [pages_types] SET `name` = :name, `for_news_page` = :for_news_page, `user_allow_edit_own` = :user_allow_edit_own, `user_allow_use_wysiwyg` = :user_allow_use_wysiwyg, `insight_groups` = :insight_groups, `editing_groups` = :editing_groups, `submitting_groups` = :submitting_groups, `show_preview` = :show_preview, `add_author` = :add_author, `change_author` = :change_author, `add_last_editing_date` = :add_last_editing_date, `change_date` = :change_date, `show_author` = :show_author, `show_date` = :show_date, `show_category` = :show_category, `show_tags` = :show_tags, `show_type` = :show_type, `user_allow_comments` = :user_allow_comments WHERE `id` = '.$_request->get('id')->show(), $bind);
				$_log->insertSuccess('edit', __('Data has been updated.'));
				$_request->redirect(FILE_PATH, array('page' => 'types', 'act' => 'edit', 'status' => 'ok'));
			}
			// Zapis nowego typu treści
			else
			{
				$_pdo->exec('
					INSERT INTO [pages_types] (`name`, `for_news_page`, `user_allow_edit_own`, `user_allow_use_wysiwyg`, `insight_groups`, `editing_groups`, `submitting_groups`, `show_preview`, `add_author`, `change_author`, `add_last_editing_date`, `change_date`, `show_author`, `show_date`, `show_category`, `show_tags`, `show_type`, `user_allow_comments`)
					VALUES (:name, :for_news_page, :user_allow_edit_own, :user_allow_use_wysiwyg, :insight_groups, :editing_groups, :submitting_groups, :show_preview, :add_author, :change_author, :add_last_editing_date, :change_date, :show_author, :show_date, :show_category, :show_tags, :show_type, :user_allow_comments)',
					$bind
				);

				$_log->insertSuccess('edit', __('Data has been saved.'));
				$_request->redirect(FILE_PATH, array('page' => 'types', 'act' => 'add', 'status' => 'ok'));
			}
		}
		// Tworzenie nowego materiału
		elseif ($_request->get('action')->show() === 'add')
		{
			$groups = $_tpl->getMultiSelect($_user->getViewGroups(), '0', TRUE);

			// Przesyłanie danych do routera
			$_tpl->assignGroup(array(
				/**
                 * Musimy przesłać trzy razy to samo, gdyż jest ten sam formularz edycji i dodawania materiału.
				 * Przy edycji pola te mogą mieć odmienne wartości, stąd są różne indeksy.
				 */
				'insight_groups' => $_tpl->getMultiSelect($_user->getViewGroups(), '3', TRUE),
				'editing_groups' => $groups,
				'submitting_groups' => $groups,
				// Ustawianie wartości domyślnych
				'show_preview' => 1,
				'add_author' => 1,
				'show_author' => 1,
				'show_date' => 1,
				'show_category' => 1,
				'show_tags' => 1,
				'show_type' => 1,
				'user_allow_comments' => 1
			));

		}
		// Edycja materiału - przekazywanie danych z bazy do szablonu
		elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			if ($row = $_pdo->getRow('SELECT * FROM [pages_types] WHERE id = '.$_request->get('id')->show()))
			{
				$_tpl->assignGroup(array(
					'name' => $row['name'],
					'for_news_page' => $row['for_news_page'],
					'user_allow_edit_own' => $row['user_allow_edit_own'],
					'user_allow_use_wysiwyg' => $row['user_allow_use_wysiwyg'],
					'insight_groups' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['insight_groups']), TRUE),
					'editing_groups' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['editing_groups']), TRUE),
					'submitting_groups' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['submitting_groups']), TRUE),
					'show_preview' => $row['show_preview'],
					'add_author' => $row['add_author'],
					'change_author' => $row['change_author'],
					'add_last_editing_date' => $row['add_last_editing_date'],
					'change_date' => $row['change_date'],
					'show_author' => $row['show_author'],
					'show_date' => $row['show_date'],
					'show_category' => $row['show_category'],
					'show_tags' => $row['show_tags'],
					'show_type' => $row['show_type'],
					'user_allow_comments' => $row['user_allow_comments']
				));
			}
		}
		// + Usuwanie materiału
		elseif ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
		{
			$_pdo->exec('DELETE FROM [pages_types] WHERE id = '.$_request->get('id')->show());
			$_log->insertSuccess('edit', __('Data has been removed.'));
			$_request->redirect(FILE_PATH, array('page' => 'types', 'act' => 'delete', 'status' => 'ok'));
			
		}
		// Przegląd wszystkich materiałów
		else if ($_request->get('action', NULL)->show() === NULL)
		{
			$_tpl->assign('type', $_pdo->getData('SELECT * FROM [pages_types]'));

		}
		// Błąd! Nieznan podstrona
		else
		{

		}
	}
	// Zarządzanie kategoriami
	elseif ($_request->get('page')->show() === 'categories')
	{
		// Wyświetlanie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
				array(
					'add' => array(
						__('The entry has been added.'), __('Error! The entry has not been added.')
					),
					'edit' => array(
						__('The entry has been edited.'), __('Error! The entry has not been edited.')
					),
					'delete' => array(
						__('The entry has been deleted.'), __('Error! The entry has not been deleted.')
					)
				)
			);
		}

		// Zapis nowej kategorii lub danych z edycji istniejącej
		if ($_request->post('save')->show())
		{
			$bind = array(
				array(':name', $_request->post('name')->strip(), PDO::PARAM_STR),
				array(':description', $_request->post('description')->strip(), PDO::PARAM_STR),
				array(':submitting_groups', $_request->post('submitting_groups')->filters('getNumArray', 'implode'), PDO::PARAM_STR),
			);

			if ($_request->upload('thumbnail'))
			{
				// Uploaduje plik na serwer i zwracana nazwę po zapisaniu w miejscu docelowym
				$thumbnail = Image::sendFile($_request->_file('thumbnail')->show(), DIR_UPLOAD.'images'.DS, 'custom_pages_', TRUE);
			}
			elseif ($_request->post('delete_thumbnail')->show())
			{
				$thumbnail = '';
				Image::delFile(DIR_UPLOAD.'images'.DS.$_request->post('thumbnail')->strip());
			}

			if (isset($thumbnail))
			{
				$bind[] = array(':thumbnail', $thumbnail, PDO::PARAM_STR);
			}
			
			// Zapis edycji
			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
			{
				$_pdo->exec('UPDATE [pages_categories] SET `name` = :name, `description` = :description, `submitting_groups` = :submitting_groups '.(isset($thumbnail) ? ', `thumbnail` = :thumbnail' : '').' WHERE `id` = '.$_request->get('id')->show(), $bind);
				$_log->insertSuccess('edit', __('Data has been updated.'));
				$_request->redirect(FILE_PATH, array('page' => 'categories', 'act' => 'edit', 'status' => 'ok'));
			}
			// Zapis nowej kategorii
			else
			{
				$_pdo->exec('
					INSERT INTO [pages_categories] (`name`, `description`, `submitting_groups`'.(isset($thumbnail) ? ', `thumbnail`' : '').')
					VALUES (:name, :description, :submitting_groups'.(isset($thumbnail) ? ', :thumbnail' : '').')',
					$bind
				);

				$_log->insertSuccess('edit', __('Data has been saved.'));
				$_request->redirect(FILE_PATH, array('page' => 'categories', 'act' => 'add', 'status' => 'ok'));
			}
		}
		// Tworzenie nowej kategorii
		elseif ($_request->get('action')->show() === 'add')
		{
			$groups = $_tpl->getMultiSelect($_user->getViewGroups(), '0', TRUE);

			// Przesyłanie danych do routera
			$_tpl->assignGroup(array(
				'submitting_groups' => $groups,
				// Ustawianie wartości domyślnych
				//'show_image' => 0
			));

		}
		// Edycja kategorii - przekazywanie danych z bazy do szablonu
		elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			if ($row = $_pdo->getRow('SELECT * FROM [pages_categories] WHERE id = '.$_request->get('id')->show()))
			{
				$_tpl->assignGroup(array(
					'name' => $row['name'],
					'description' => $row['description'],
					'submitting_groups' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['submitting_groups']), TRUE),
					'thumbnail' => $row['thumbnail'],
					//'show_image' => $row['show_image']
				));
			}
		}
		// Usuwanie kategorii z zabezpieczeniem przed usunięciem systemowej
		elseif ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
		{
			$row = $_pdo->getRow('SELECT `thumbnail` FROM [pages_categories] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			Image::delFile(DIR_UPLOAD.'images'.DS.$row['thumbnail']);
			
			$_pdo->exec('UPDATE [pages] SET `categories` = :categories WHERE `id` = :id',
				array(
					array(':categories', 1, PDO::PARAM_INT),
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);
			
			$_pdo->exec('DELETE FROM [pages_categories] WHERE id = '.$_request->get('id')->show().' AND `is_system` = 0');

			$_log->insertSuccess('edit', __('Data has been removed.'));
			$_request->redirect(FILE_PATH, array('page' => 'categories', 'act' => 'delete', 'status' => 'ok'));
		}
		// Przegląd wszystkich kategorii
		else if ($_request->get('action', NULL)->show() === NULL)
		{
			$_tpl->assign('category', $_pdo->getData('SELECT `name`, `description` FROM [pages_categories]'));

		}
		// Błąd! Nieznana podstrona
		else
		{

		}
	}
	// Wpisy
	elseif ($_request->get('page')->show() === 'entries')
	{
		if ($types = $_pdo->getData('SELECT `id`, `name`, `insight_groups` FROM [pages_types]'))
		{

			// Wyświetlanie komunikatów
			if ($_request->get(array('status', 'act'))->show())
			{
				$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
					array(
						'add' => array(
							__('The entry has been added.'), __('Error! The entry has not been added.')
						),
						'edit' => array(
							__('The entry has been edited.'), __('Error! The entry has not been edited.')
						),
						'delete' => array(
							__('The entry has been deleted.'), __('Error! The entry has not been deleted.')
						)
					)
				);
			}

			// Zapis nowego wpisu lub danych z edycji istniejącego
			if ($_request->post('save')->show())
			{
				// Lista wartości pól, które nie mogą być puste
				$field_required = array(
					$_request->post('title')->show(),
					$_request->post('description')->show(),
					$_request->post('categories')->show(),
					$_request->post('type')->show(),
					$_request->post('content')->show(),
					$_request->post('preview')->show()
				);

				if (in_array(FALSE, $field_required))
				{//todo: zamienić na metodę wyświetlania błędu z parsera
					$_tpl->assign('message', 'Nie wszystkie pola zostały wypełnione');
					$_tpl->assign('status', 'error');
				}
				else
				{
					! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);
					$bind = array(
						array(':title', $_request->post('title')->strip(), PDO::PARAM_STR),
						array(':description', $_request->post('description')->strip(), PDO::PARAM_STR),
						array(':url', $_request->post('url')->strip(), PDO::PARAM_STR),
						array(':categories', $_request->post('categories')->filters('getNumArray', 'implode'), PDO::PARAM_STR),
						array(':type', $_request->post('type')->isNum(TRUE), PDO::PARAM_INT),
						array(':content', $_request->post('content')->show(), PDO::PARAM_STR),
						array(':preview', $_request->post('preview')->show(), PDO::PARAM_STR)
					);

					if ($_request->upload('thumbnail'))
					{
						// Uploaduje plik na serwer i zwracana nazwę po zapisaniu w miejscu docelowym
						$thumbnail = Image::sendFile($_request->_file('thumbnail')->show(), DIR_UPLOAD.'images'.DS, 'custom_pages_', TRUE);
					}
					elseif ($_request->post('delete_thumbnail')->show())
					{
						$thumbnail = '';
						Image::delFile(DIR_UPLOAD.'images'.DS.$_request->post('thumbnail')->strip());
					}

					if (isset($thumbnail))
					{
						$bind[] = array(':thumbnail', $thumbnail, PDO::PARAM_STR);
					}

					if ($keyword = $_request->post('keywords')->strip())
					{
						// Pobiera informację, kto będzie miał dostęp do słów kluczowych
						foreach($types as $type)
						{
							if ($type['id'] === $_request->post('type')->show())
							{
								$access = $type['insight_groups'];
							}
							else
							{
								$access = '0';
							}
						}
					}

					// Zapis edycji
					if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
					{
						if (isset($thumbnail))
						{
							if ($old_thumbnail = $_pdo->getField('SELECT `thumbnail` FROM [pages] WHERE `id` = '.$_request->get('id')->show()))
							{
								$file = realpath(DIR_UPLOAD.'images'.DS.$old_thumbnail);

								if (file_exists($file) && is_file($file))
								{
									unlink(DIR_UPLOAD.'images'.DS.$old_thumbnail);
								}
							}
						}

						$_pdo->exec('UPDATE [pages] SET `title` = :title, `description` = :description, `url` = :url, `categories` = :categories, `type` = :type, '.(isset($thumbnail) ? '`thumbnail` = :thumbnail, ' : '').'`content` = :content, `preview` = :preview WHERE `id` = '.$_request->get('id')->show(), $bind);
						$_log->insertSuccess('edit', __('Data has been updated.'));

						if (isset($access))
						{
							$_tag->updTag('PAGES', $_request->get('id')->show(), $keyword, $access);
						}
						else
						{
							$_tag->delTag('PAGES', $_request->get('id')->show());
						}

						$_request->redirect(FILE_PATH, array('page' => 'entries', 'act' => 'edit', 'status' => 'ok'));
					}
					// Zapis nowego wpisu
					else
					{
						$_pdo->exec('
							INSERT INTO [pages] (`title`, `description`, `url`, `categories`, `type`, '.(isset($thumbnail) ? '`thumbnail`, ' : '').'`content`, `preview`, `date`, `author`)
							VALUES (:title, :description, :url, :categories, :type, '.(isset($thumbnail) ? ':thumbnail, ' : '').':content, :preview, '.time().', '.$_user->get('id').')',
							$bind
						);

						$_log->insertSuccess('edit', __('Data has been saved.'));

						// Czy mają zostać zapisane jakieś słowa kluczowe?
						if (isset($access))
						{
							$_tag->addTag('PAGES', $_pdo->getMaxValue('SELECT max(`id`) FROM [pages]'), $keyword, $access);
						}

						$_request->redirect(FILE_PATH, array('page' => 'entries', 'act' => 'add', 'status' => 'ok'));
					}
				}
			}

			// Elementy uwspólnione
			if ($_request->get('action')->show() === 'add' || $_request->get('action')->show() === 'edit')
			{
				$category = array();
				foreach($_pdo->getData('SELECT `id`, `name` FROM [pages_categories]') as $row)
				{
					if ($row['id'] === '1')
					{
						$category[$row['id']] = '--'.$row['name'].'--';
					}
					else
					{
						$category[$row['id']] = $row['name'];
					}
				}

				$type = array();
				foreach($types as $row)
				{
					$type[$row['id']] = $row['name'];
				}
			}

			// Tworzenie nowego wpisu
			if ($_request->get('action')->show() === 'add')
			{
				// Przesyłanie danych do routera
				$_tpl->assignGroup(array(
					'categories' => $_tpl->getMultiSelect($category, '1', FALSE),
					'types' => $_tpl->createSelectOpts($type, NULL, TRUE),
				));

			}
			// Edycja kategorii - przekazywanie danych z bazy do szablonu
			elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
			{
				if ($row = $_pdo->getRow('SELECT * FROM [pages] WHERE id = '.$_request->get('id')->show()))
				{
					! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

					$keywords = array();
					if ($keys = $_tag->getTag('PAGES', $_request->get('id')->show())){
						foreach($keys as $var)
						{
							$keywords[] = $var['value'];
						}
					}

					$_tpl->assignGroup(array(
						'title' => $row['title'],
						'description' => $row['description'],
						'type' => $row['type'],
						'content' => $row['content'],
						'preview' => $row['preview'],
						'categories' => $_tpl->getMultiSelect($category, HELP::explode($row['categories']), FALSE),
						'types' => $_tpl->createSelectOpts($type, $row['type'], TRUE),
						'thumbnail' => $row['thumbnail'],
						'url' => $row['url'],
						'keywords' => $keywords
					));
				}
			}
			// Usuwanie kategorii z zabezpieczeniem przed usunięciem systemowej
			elseif ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
			{
				$row = $_pdo->getRow('SELECT `thumbnail` FROM [pages] WHERE `id` = :id',
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				);
				$_pdo->exec('DELETE FROM [comments] WHERE `content_type` = "pages" AND `content_id` = '.$_request->get('id')->show());
				Image::delFile(DIR_UPLOAD.'images'.DS.$row['thumbnail']);
				$_pdo->exec('DELETE FROM [pages] WHERE id = '.$_request->get('id')->show());

				////$_tag->delTag('NEWS', $_request->get('id')->show());

				$_log->insertSuccess('edit', __('Data has been removed.'));
				$_request->redirect(FILE_PATH, array('page' => 'entries', 'act' => 'delete', 'status' => 'ok'));
			}
			// Przegląd wszystkich kategorii
			else if ($_request->get('action', NULL)->show() === NULL)
			{
				$_tpl->assign('category', $_pdo->getData('SELECT `name`, `description` FROM [pages_categories]'));

			}
			// Błąd! Nieznana podstrona
			else
			{

			}
		}
		else
		{
			$_tpl->assign('types_error', TRUE);
		}
	}
	// Strona główna
	elseif ($_request->get('page', NULL)->show() === NULL)
	{

	}
	// Błąd! Nieznana podstrona
	else
	{
		throw new userException('Błąd! Ta strona nie istnieje.');
	}


    $_tpl->template('pages');
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
    pdoErrorHandler($exception);
}
