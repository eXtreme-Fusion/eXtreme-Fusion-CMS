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

		return $this->view('thread', array(
			'thread'  => $thread,
			'entries' => $entries,
		));
	}

}