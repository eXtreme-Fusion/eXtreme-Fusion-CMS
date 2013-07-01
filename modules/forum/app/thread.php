<?php

class Thread_Controller extends Forum_Controller {

	public function index()
	{
		$thread = $this
			->model('thread')
			->findByID($this->params[0]);

		if ( ! $thread)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$entries = $this
			->model('entry')
			->fetchByID($thread['id']);

		$user = $this->model('user', array($this->user, $thread));

		return $this->view('thread', array(
			'thread'  => $thread,
			'entries' => $entries,
			'user'    => $user,
		));
	}

	public function create()
	{
		if ($this->user->iGUEST())
		{
			return $this->user->onlyForUsers($this->router);
		}

		$category = $this
			->model('category')
			->findByID($id = $this->params[0]);

		if ( ! $category)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		if ($this->request->post('title')->show() && $this->request->post('content')->show())
		{
			$thread = $this->pdo->exec('INSERT INTO [threads] (`category_id`, `user_id`, `title`, `is_pinned`, `timestamp`) VALUES (:category, :user, :title, :is_pinned, :timestamp)', array(
				array(':category', $id, PDO::PARAM_INT),
				array(':user', $user = $this->user->get('id'), PDO::PARAM_INT),
				array(':title', HELP::wordsProtect($this->request->post('title')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':is_pinned', ($this->user->iADMIN() && $this->request->post('is_pinned')->show()), PDO::PARAM_BOOL),
				array(':timestamp', $time = time(), PDO::PARAM_INT),
			));

			if ($thread && $_thread_id = $this->pdo->lastInsertId())
			{
				$entry = $this->pdo->exec('INSERT INTO [entries] (`thread_id`, `user_id`, `content`, `is_main`, `timestamp`) VALUES (:thread, :user, :content, 1, :timestamp)', array(
					array(':thread', $_thread_id, PDO::PARAM_INT),
					array(':user', $user, PDO::PARAM_INT),
					array(':content', HELP::wordsProtect($this->request->post('content')->filters('trim', 'strip')), PDO::PARAM_STR),
					array(':timestamp', $time, PDO::PARAM_INT),
				));

				if ($entry && $_entry_id = $this->pdo->lastInsertId())
				{
					return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'thread', $_thread_id)).'#entry-'.$_entry_id);
				}
			}
		}

		return $this->view('thread/create', array(
			'category' => $category,
			'bbcodes'  => $this->bbcode->bbcodes(),
			'smileys'  => $this->bbcode->smileys(),
		));
	}

	public function edit()
	{
		if ($this->user->iGUEST())
		{
			return $this->user->onlyForUsers($this->router);
		}

		$thread = $this
			->model('thread')
			->findByID($id = $this->params[0]);

		if ( ! $thread)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		if ($this->request->post('title')->show() && $this->request->post('content')->show())
		{
			$_thread = $this->pdo->exec('UPDATE [threads] SET `title` = :title, `is_pinned` = :is_pinned WHERE `id` = :id', array(
				array(':title', HELP::wordsProtect($this->request->post('title')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':is_pinned', ($this->user->iADMIN() && $this->request->post('is_pinned')->show()), PDO::PARAM_BOOL),
				array(':id', $id, PDO::PARAM_INT),
			));

			if ($_thread)
			{
				$entry = $this->pdo->exec('UPDATE [entries] SET `content` = :content WHERE `id` = :id AND `is_main` = 1', array(
					array(':content', HELP::wordsProtect($this->request->post('content')->filters('trim', 'strip')), PDO::PARAM_STR),
					array(':id', $_id = $thread['entry_id'], PDO::PARAM_INT),
				));

				if ($entry)
				{
					return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'thread', $id)).'#entry-'.$_id);
				}
			}
		}

		return $this->view('thread/edit', array(
			'thread'  => $thread,
			'bbcodes' => $this->bbcode->bbcodes(),
			'smileys' => $this->bbcode->smileys(),
		));
	}

	public function reply()
	{
		if ($this->user->iGUEST())
		{
			return $this->user->onlyForUsers($this->router);
		}

		$thread = $this
			->model('thread')
			->findByID($id = $this->params[0]);

		if ( ! $thread)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		if ($this->request->post('content')->show())
		{
			$reply = $this->pdo->exec('INSERT INTO [entries] (`thread_id`, `user_id`, `content`, `timestamp`) VALUES (:thread, :user, :content, :timestamp)', array(
				array(':thread', $id, PDO::PARAM_INT),
				array(':user', $this->user->get('id'), PDO::PARAM_INT),
				array(':content', HELP::wordsProtect($this->request->post('content')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':timestamp', time(), PDO::PARAM_INT),
			));

			if ($reply && $entry = $this->pdo->lastInsertId())
			{
				$this->pdo->exec('UPDATE [threads] SET `timestamp` = :timestamp WHERE `id` = :id', array(
					array(':id', $id, PDO::PARAM_INT),
					array(':timestamp', time(), PDO::PARAM_INT),
				));

				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'thread', $id)).'#entry-'.$entry);
			}
		}

		return $this->view('thread/reply', array(
			'thread'  => $thread,
			'bbcodes' => $this->bbcode->bbcodes(),
			'smileys' => $this->bbcode->smileys(),
		));
	}

}