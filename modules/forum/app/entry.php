<?php defined('EF5_SYSTEM') || exit;

class Entry_Controller extends Forum_Controller {

	public function edit()
	{
		if ( ! $this->logged_in)
		{
			return $this->user->onlyForUsers($this->router);
		}

		$entry = $this
			->model('entry')
			->findByID($id = $this->params[0]);

		$user = $this->model('user', array($this->user));

		if ( ! $entry || $entry['is_main'] || ! $user->isAuthor($entry['user_id']))
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$thread = $this
			->model('thread')
			->findByID($entry['thread_id']);

		if ($this->request->post('content')->show())
		{
			$_entry = $this->pdo->exec('UPDATE [entries] SET `content` = :content WHERE `id` = :id', array(
				array(':content', HELP::wordsProtect($this->request->post('content')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':id', $id, PDO::PARAM_INT),
			));

			if ($_entry)
			{
				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'thread', $entry['thread_id'])).'#entry-'.$id);
			}
		}

		return $this->view('entry/edit', array(
			'entry'   => $entry,
			'thread'  => $thread,
			'bbcodes' => $this->bbcode->bbcodes(),
			'smileys' => $this->bbcode->smileys(),
		));
	}

	public function remove()
	{
		if ( ! $this->is_admin)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$entry = $this
			->model('entry')
			->findByID($id = $this->params[0]);

		if ( ! $entry || $entry['is_main'])
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$_entry = $this->pdo->exec('DELETE FROM [entries] WHERE `id` = :id',
			array(':id', $id, PDO::PARAM_INT));

		if ($_entry)
		{
			return $this->router->redirect(array('module' => 'forum', 'controller' => 'thread', $entry['thread_id']));
		}

		return $this->router->trace(array('controller' => 'error', 'action' => 500));
	}

}