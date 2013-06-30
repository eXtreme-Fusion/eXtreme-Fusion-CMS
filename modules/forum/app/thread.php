<?php

class Thread_Controller extends Forum_Controller {

	public function index()
	{
		$thread = $this
			->model('thread')
			->findByID($this->params[0]);

		if ($thread === FALSE)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$entries = $this
			->model('entry')
			->fetchByID($thread['id']);

		$user = $this->model('user', array($this->user));

		return $this->view('thread', array(
			'thread'  => $thread,
			'entries' => $entries,
			'user'    => $user,
		));
	}

	public function reply()
	{
		if ($this->user->iGUEST() === TRUE)
		{
			return $this->router->redirect(array('controller' => 'login'));
		}

		$thread = $this
			->model('thread')
			->findByID($id = $this->params[0]);

		if ($thread === FALSE)
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

			if ($reply AND $entry = $this->pdo->lastInsertId())
			{
				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'thread', $thread['id'])).'#entry-'.$entry);
			}
		}

		return $this->view('reply', array(
			'thread'  => $thread,
			'bbcodes' => $this->bbcode->bbcodes(),
			'smileys' => $this->bbcode->smileys()
		));
	}

}