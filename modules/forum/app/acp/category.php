<?php defined('EF5_SYSTEM') || exit;

class Category_Controller extends Forum_Controller {

	protected $admin_area = TRUE;

	public function add()
	{
		$board = $this
			->model('board')
			->findByID($id = $this->params[0]);

		if ( ! $board)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		if ($this->request->post('title')->show() && $this->request->post('submit')->show())
		{
			$_category = $this->pdo->exec('INSERT INTO [board_categories] (`board_id`, `title`, `description`, `is_locked`, `order`) VALUES (:board, :title, :description, :is_locked, :order)', array(
				array(':board', $id, PDO::PARAM_INT),
				array(':title', HELP::wordsProtect($this->request->post('title')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':description', HELP::wordsProtect($this->request->post('description')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':is_locked', (bool) $this->request->post('is_locked', FALSE)->show(), PDO::PARAM_BOOL),
				array(':order', $this->request->post('order')->show(), PDO::PARAM_INT),
			));

			if ($_category)
			{
				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'acp')).'#board-'.$id);
			}
		}

		return $this->view('acp/category', array(
			'board_id' => $board['id'],
			'board'    => $board['title'],
			'title'    => $this->request->post('title')->show(),
		));
	}

	public function edit()
	{
		$category = $this
			->model('category')
			->findByID($id = $this->params[0]);

		if ( ! $category)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		if ($this->request->post('title')->show())
		{
			$_category = $this->pdo->exec('UPDATE [board_categories] SET `title` = :title, `description` = :description, `is_locked` = :is_locked, `order` = :order WHERE `id` = :id', array(
				array(':title', HELP::wordsProtect($this->request->post('title')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':description', HELP::wordsProtect($this->request->post('description')->filters('trim', 'strip')), PDO::PARAM_STR),
				array(':is_locked', (bool) $this->request->post('is_locked', FALSE)->show(), PDO::PARAM_BOOL),
				array(':order', $this->request->post('order')->show(), PDO::PARAM_INT),
				array(':id', $id, PDO::PARAM_INT),
			));

			if ($_category)
			{
				return HELP::redirect($this->url->path(array('module' => 'forum', 'controller' => 'acp')).'#board-'.$id);
			}
		}

		return $this->view('acp/category', array(
			'board_id' => $category['board_id'],
			'board'    => $category['board'],
			'category' => $category,
		));
	}

	public function remove()
	{
		$category = $this
			->model('category')
			->findByID($id = $this->params[0]);

		if ( ! $category)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$_category = $this->pdo->exec('DELETE FROM [board_categories] WHERE `id` = :id',
			array(':id', $id, PDO::PARAM_INT));

		$_entries = $this->pdo->exec('
			DELETE e.*
			FROM [entries] e
			LEFT JOIN [threads] t
			ON t.id = e.thread_id
			WHERE t.category_id = :id
		', array(':id', $id, PDO::PARAM_INT));

		$_thread = $this->pdo->exec('DELETE FROM [threads] WHERE `category_id` = :id',
			array(':id', $id, PDO::PARAM_INT));

		return $this->router->redirect(array('module' => 'forum', 'controller' => 'acp'));
	}

}