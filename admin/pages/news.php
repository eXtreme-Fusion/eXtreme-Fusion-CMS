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

	$_locale->load('news');

	$_tpl = new Iframe;

	if($_request->get('page')->show() === 'news')
	{
		if ( ! $_user->hasPermission('admin.news'))
		{
			throw new userException(__('Access Denied'));
		}

		! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

		// Wyświetlenie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			// Wyświetli komunikat
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
				array(
					'add' => array(
						__('News has been added.'), __('Error! News has not been added.')
					),
					'edit' => array(
						__('News has been edited.'), __('Error! News has not been edited.')
					),
					'delete' => array(
						__('News has been deleted.'), __('Error! News has not been deleted.')
					)
				)
			);
		}

		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum(TRUE))
		{
			$count = $_pdo->exec('DELETE FROM [news] WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);

			if ($count)
			{
				$_system->clearCache('news');
				$_system->clearCache('news_cats');
				$_tag->delTag('NEWS', $_request->get('id')->show());
				$_log->insertSuccess('delete', __('News has been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'news', 'act' => 'delete', 'status' => 'ok'));
			}

			$_log->insertFail('delete', __('Error! News has not been deleted.'));
			$_request->redirect(FILE_PATH, array('page' => 'news', 'act' => 'delete', 'status' => 'error'));
		}

		// Pobranie kategori newsów
		$query = $_pdo->getData('SELECT `id`, `name` FROM [news_cats] ORDER BY `id`');

		$category = array();
		if ($_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				$category[$row['id']] = $row['name'];
			}
		}
		
		if ($_request->post('save')->show())
		{
			if ($_request->post('content')->show() && $_request->post('title')->show())
			{
				$title = $_request->post('title')->filters('trim', 'strip');
				$description = $_request->post('description')->filters('trim', 'strip');
				$language = $_request->post('language')->filters('trim', 'strip');
				$keyword = $_request->post('tag')->strip();
				$access = $_request->post('access')->show() ? $_request->post('access')->getNumArray() : array(0 => '0');
				$content = HELP::formatOrphan($_request->post('content')->show());
				$content_extended = HELP::formatOrphan($_request->post('content_extended')->show());
				$category = $_request->post('category')->isNum(TRUE, FALSE) ? $_request->post('category')->show() : '';
				$author = $_user->get('id');
				$source = $_request->post('source')->filters('trim', 'strip');
				$breaks = $_request->post('breaks')->show() ? 1 : 0;
				$draft = $_request->post('draft')->show() ? 1 : 0;
				$sticky = $_request->post('sticky')->show() ? 1 : 0;
				$allow_comments = $_request->post('allow_comments')->show() ? 1 : 0;
				$allow_ratings = $_request->post('allow_ratings')->show() ? 1 : 0;

				if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum(TRUE))
				{
					$count = $_pdo->exec('
						UPDATE [news]
						SET `title` = :title, `link` = :link, `category` = :category, `language` = :language, `content` = :content, `content_extended` = :content_extended, `source` = :source, `description` = :description, `access` = :access, `draft` = :draft, `sticky` = :sticky, `allow_comments` = :allow_comments, `allow_ratings` = :allow_ratings
						WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':title', $title, PDO::PARAM_STR),
							array(':link', HELP::Title2Link($title), PDO::PARAM_STR),
							array(':category', $category, PDO::PARAM_INT),
							array(':language', $language, PDO::PARAM_STR),
							array(':content', $content, PDO::PARAM_STR),
							array(':content_extended', $content_extended, PDO::PARAM_STR),
							array(':source', $source, PDO::PARAM_STR),
							array(':description', $description, PDO::PARAM_STR),
							array(':access', HELP::implode($access), PDO::PARAM_STR),
							array(':draft', $draft, PDO::PARAM_INT),
							array(':sticky', $sticky, PDO::PARAM_INT),
							array(':allow_comments', $allow_comments, PDO::PARAM_INT),
							array(':allow_ratings', $allow_ratings, PDO::PARAM_INT)
						)
					);

					if ($count)
					{
						$_system->clearCache('news');
						$_system->clearCache('news_cats');
						$_tag->updTag('NEWS', $_request->get('id')->show(), $keyword, $access);
						$_log->insertSuccess('edit', __('News has been edited.'));
						$_request->redirect(FILE_PATH, array('page' => 'news', 'act' => 'edit', 'status' => 'ok'));
					}

					$_log->insertFail('edit', __('Error! News has not been edited.'));
					$_request->redirect(FILE_PATH, array('page' => 'news', 'act' => 'edit', 'status' => 'error'));

				}
				else
				{
					$count = $_pdo->exec('
						INSERT INTO [news]
						(`title`, `link`, `category`, `language`, `content`, `content_extended`, `source`, `description`, `author`, `access`, `datestamp`, `draft`, `sticky`, `allow_comments`, `allow_ratings`)
						VALUES
						(:title, :link, :category, :language, :content, :content_extended, :source, :description, :author, :access, '.time().', :draft, :sticky, :allow_comments, :allow_ratings)',
						array(
							array(':title', $title, PDO::PARAM_STR),
							array(':link', HELP::Title2Link($title), PDO::PARAM_STR),
							array(':category', $category, PDO::PARAM_INT),
							array(':language', $language, PDO::PARAM_STR),
							array(':content', $content, PDO::PARAM_STR),
							array(':content_extended', $content_extended, PDO::PARAM_STR),
							array(':source', $source, PDO::PARAM_STR),
							array(':description', $description, PDO::PARAM_STR),
							array(':author', $author, PDO::PARAM_INT),
							array(':access', HELP::implode($access), PDO::PARAM_STR),
							array(':draft', $draft, PDO::PARAM_INT),
							array(':sticky', $sticky, PDO::PARAM_INT),
							array(':allow_comments', $allow_comments, PDO::PARAM_INT),
							array(':allow_ratings', $allow_ratings, PDO::PARAM_INT)
						)
					);

					if ($count)
					{
						$_system->clearCache('news');
						$_system->clearCache('news_cats');
						$_tag->addTag('NEWS', $_pdo->lastInsertId(), $keyword, $access);
						$_log->insertSuccess('add', __('News has been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'news', 'act' => 'add', 'status' => 'ok'));
					}

					$_log->insertFail('add', __('Error! News has not been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'news', 'act' => 'add', 'status' => 'error'));
				}
			}
			else
			{
				//var_dump($_tpl->getMultiSelect($_user->getViewGroups(), $_request->post('access')->show(), TRUE)); exit;
				$_tpl->assignGroup(array(
					'title' => $_request->post('title')->show(),
					'description' => $_request->post('description')->show(),
					'category' => $_tpl->createSelectOpts($category, intval($_request->post('category')->show()), TRUE, TRUE),
					'language' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $_request->post('language')->show(), FALSE, TRUE),
					'content' => $_request->post('content')->show(),
					'content_extended' => $_request->post('content_extended')->show(),
					'access' => $_tpl->getMultiSelect($_user->getViewGroups(), $_request->post('access')->show(), TRUE),
					'keyword' => $_request->post('tag')->show(),
					'allow_comments' => $_request->post('allow_comments')->show(),
					'allow_ratings' => $_request->post('allow_ratings')->show(),
					'source' => $_request->post('source')->filters('trim', 'strip'),
					'draft' =>  $_request->post('draft')->show(),
					'sticky' => $_request->post('sticky')->show()
				));
				
				$_tpl->printMessage('error', 'Nie wypełniono wymaganych pól: tytuł oraz treść.');
			}
		}
		elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$row = $_pdo->getRow('SELECT * FROM [news] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);

			if ($row)
			{
				$keyword = array();
				if ($keys = $_tag->getTag('NEWS', $_request->get('id')->show())){
					foreach($keys as $var){
						$keyword[] = $var['value'];
					}
				}
				
		

				$_tpl->assignGroup(array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => $row['description'],
					'category' => $_tpl->createSelectOpts($category, intval($row['category']), TRUE, TRUE),
					'language' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $row['language'], FALSE, TRUE),
					'content' => $row['content'],
					'content_extended' => $row['content_extended'],
					'access' => $_tpl->getMultiSelect($_user->getViewGroups(), HELP::explode($row['access']), TRUE),
					'keyword' => $keyword,
					'allow_comments' => $row['allow_comments'],
					'allow_ratings' => $row['allow_ratings'],
					'source' => $row['source'],
					'draft' => $row['draft'],
					'sticky' => $row['sticky']
				));
			}
			else
			{
				// Wyświetlenie wyjątku o braku identyfikatora
				throw new userException(__('Brak rekordu o ID: :id!', array(':id' => $_request->get('id')->show())));
			}
		}
		else
		{
			$_tpl->assignGroup(array(
				'category' => $_tpl->createSelectOpts($category, NULL, TRUE, TRUE),
				'access' => $_tpl->getMultiSelect($_user->getViewGroups(), NULL, TRUE),
				'language' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $_sett->get('locale'), FALSE, TRUE)
			));
		}

		$items_per_page = 20;
		$current = intval($_request->get('current', NULL)->show() !== NULL ? $_request->get('current')->show() : 1);

		$rows = $_pdo->getMatchRowsCount('SELECT `id`, `title`, `datestamp`, `author`, `access` FROM [news] ORDER BY `datestamp`');

		if ($rows)
		{
			$rowstart = $_request->get('current', NULL)->show() !== NULL ? PAGING::getRowStart(intval($_request->get('current')->show()), $items_per_page) : 0;

			$query = $_pdo->getData('SELECT `id`, `title`, `datestamp`, `author`, `access` FROM [news] ORDER BY `datestamp` DESC LIMIT :rowstart, :items_per_page',
				array(
					array(':rowstart', $rowstart, PDO::PARAM_INT),
					array(':items_per_page', $items_per_page, PDO::PARAM_INT)
				)
			);

			$news_list = array();
			if ($_pdo->getRowsCount($query))
			{
				$i = 0;
				foreach($query as $row)
				{
					$news_list[] = array(
						'id' => $row['id'],
						'title' => $row['title'],
						'date' => HELP::showDate('shortdate', $row['datestamp']),
						'author' => $_user->getUsername($row['author']),
						'access' => $_user->groupArrIDsToNames(HELP::explode($row['access']))
					);
				}

			}

			$_pagenav = new PageNav(new Paging($rows, $current, $items_per_page), $_tpl, 10, array('page=news', 'current=', FALSE));
			$_pagenav->get($_pagenav->create(), 'page_nav', DIR_ADMIN_TEMPLATES.'paging'.DS);

			$_tpl->assign('news_list', $news_list);
		}
	}
	elseif($_request->get('page')->show() === 'cats')
	{
		if ( ! $_user->hasPermission('admin.news_cats'))
		{
			throw new userException(__('Access denied'));
		}

		// Wyświetlenie komunikatów
		if ($_request->get(array('status', 'act'))->show())
		{
			// Wyświetli komunikat
			$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(),
				array(
					'add' => array(
						__('News category has been added.'), __('Error! News category has not been added.')
					),
					'edit' => array(
						__('News category has been edited.'), __('Error! News category has not been edited.')
					),
					'delete' => array(
						__('News category has been deleted.'), __('Error! News category has not been deleted.')
					)
				)
			);
		}

		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
		{
			$row = $_pdo->getRow('SELECT `category` FROM [news] WHERE `category` = :category',
				array(
					array(':category', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);

			if ( ! $row)
			{
				$count = $_pdo->exec('DELETE FROM [news_cats] WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					)
				);

				if ($count)
				{
					$_system->clearCache('news_cats');
					$_log->insertSuccess('delete', __('News category has been deleted.'));
					$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'delete', 'status' => 'ok'));
				}

				$_log->insertFail('delete', __('Error! News category has not been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'delete', 'status' => 'error'));
			}
			else
			{
				throw new userException(__('Error! News category has not been deleted. There are news in category.'));
			}
		}

		if ($_request->post('save')->show())
		{
			$cat_name = $_request->post('cat_name')->filters('trim', 'strip');
			$cat_image = $_request->post('cat_image')->filters('trim', 'strip');

			if ($cat_name)
			{
				if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
				{
					$count = $_pdo->exec('UPDATE [news_cats] SET `name` = :name, `link` = :link, `image` = :image WHERE `id` = :id',
						array(
							array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
							array(':name', $cat_name, PDO::PARAM_STR),
							array(':link', HELP::Title2Link($cat_name), PDO::PARAM_STR),
							array(':image', $cat_image, PDO::PARAM_STR)
						)
					);

					if ($count)
					{
						$_system->clearCache('news_cats');
						$_log->insertSuccess('edit', __('News category has been edited.'));
						$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'edit', 'status' => 'ok'));
					}

					$_log->insertFail('edit',  __('Error! News category has not been edited.'));
					$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'edit', 'status' => 'error'));
				}
				else
				{
					$count = $_pdo->exec('INSERT INTO [news_cats] (name, link, image) VALUES (:name, :link, :image)',
						array(
							array(':name', $cat_name, PDO::PARAM_STR),
							array(':link', HELP::Title2Link($cat_name), PDO::PARAM_STR),
							array(':image', $cat_image, PDO::PARAM_STR)
						)
					);

					if ($count)
					{
						$_system->clearCache('news_cats');
						$_log->insertSuccess('add', __('News category has been added.'));
						$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'add', 'status' => 'ok'));
					}

					$_log->insertFail('add',  __('Error! News category has not been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'cats', 'act' => 'add', 'status' => 'error'));
				}
			}
			else
			{
				$_request->redirect(FILE_PATH, array('page' => 'cats'));
			}
		}

		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$row = $_pdo->getRow('SELECT `id`, `image`, `name` FROM [news_cats] WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_STR)
				)
			);

			if ($row)
			{
				$_tpl->assignGroup(array(
					'cat_name' => $row['name'],
					'cat_image' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.DS.'templates'.DS.'images'.DS.'news_cats'.DS.$_sett->get('locale'), array('.', '..', 'index.php', 'Thumbs.db', '.svn'), TRUE, 'files'), $row['image'], FALSE),
					'language' => $_sett->get('locale')
				));
			}
		}
		else
		{
			$_tpl->assignGroup(array(
					'language' => $_sett->get('locale'),
					'cat_image' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.DS.'templates'.DS.'images'.DS.'news_cats'.DS.$_sett->get('locale'), array('.', '..', 'index.php', 'Thumbs.db', '.svn'), TRUE, 'files'), 0, FALSE)
			));
		}

		$query = $_pdo->getData('SELECT * FROM [news_cats] ORDER by `id`');

		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $cat = array();
			foreach($query as $row)
			{
				$cat[] = array(
					'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
					'id' => $row['id'],
					'name' => $row['name'],
					'locale' => $_sett->get('locale'),
					'image' => $row['image']
				);
				$i++;
			}
			$_tpl->assign('cat', $cat);
		}
	}
	else
	{
		$_request->redirect(FILE_PATH, array('page' => 'news'));
		exit;
	}

	$_tpl->template('news');
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