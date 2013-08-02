<?php defined('EF5_SYSTEM') || exit;

class Board_Controller extends Forum_Controller {

	protected $admin_area = TRUE;

	public function add()
	{
		if ($this->request->post('title')->show())
		{
			$_board = $this->pdo->exec('INSERT INTO [boards] (`title`, `order`) VALUES (:title, :order)', array(
				array(':title', HELP::wordsProtect($this->request->post('title')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':order', $this->request->post('order')->show(), PDO::PARAM_INT),
			));

			if ($_board && $id = $this->pdo->lastInsertId())
			{
				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'acp')).'#board-'.$id);
			}
		}

		return $this->router->redirect(array('module' => 'forum', 'controller' => 'acp'));
	}

	public function edit()
	{
		$board = $this
			->model('board')
			->findByID($id = $this->params[0]);

		if ( ! $board)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		if ($this->request->post('title')->show())
		{
			$_board = $this->pdo->exec('UPDATE [boards] SET `title` = :title, `order` = :order WHERE `id` = :id', array(
				array(':title', HELP::wordsProtect($this->request->post('title')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':order', $this->request->post('order')->show(), PDO::PARAM_INT),
				array(':id', $id, PDO::PARAM_INT),
			));

			if ($_board)
			{
				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'acp')).'#board-'.$id);
			}
		}

		return $this->view('acp/board', array(
			'board' => $board,
		));
	}

	public function remove()
	{
		$board = $this
			->model('board')
			->findByID($id = $this->params[0]);

		if ( ! $board)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$_board = $this->pdo->exec('DELETE FROM [boards] WHERE `id` = :id',
			array(':id', $id, PDO::PARAM_INT));

		$_threads = $this->pdo->getData('
			SELECT t.*
			FROM [threads] t
			LEFT JOIN [board_categories] c
			ON c.id = t.category_id
			WHERE c.board_id = :id
		', array(':id', $id, PDO::PARAM_INT));

		if ($this->pdo->getRowsCount($_threads))
		{
			foreach ($_threads as $thread)
			{
				$_entries = $this->pdo->exec('DELETE FROM [entries] WHERE `thread_id` = :id',
					array(':id', $thread['id'], PDO::PARAM_INT));

				$_thread = $this->pdo->exec('DELETE FROM [threads] WHERE `id` = :id',
					array(':id', $thread['id'], PDO::PARAM_INT));
			}
		}

		$_categories = $this->pdo->exec('DELETE FROM [board_categories] WHERE `board_id` = :id',
			array(':id', $id, PDO::PARAM_INT));

		return $this->router->redirect(array('module' => 'forum', 'controller' => 'acp'));
	}

}