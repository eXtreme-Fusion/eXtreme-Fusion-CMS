<?php defined('EF5_SYSTEM') || exit;

class Admin_Controller extends Forum_Controller {

	public function index()
	{
		$board    = $this->model('board');
		$category = $this->model('category');

		return $this->view('admin', array(
			'board'    => $board,
			'category' => $category,
		));
	}

	public function category()
	{
		$category = $this
			->model('category')
			->findByID($id = $this->params[1]);

		if ( ! $category)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		switch ($this->params[0])
		{
			case 'remove':
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

				if ($_category && $_entries && $_thread)
				{
					return $this->router->redirect(array('module' => 'forum', 'controller' => 'admin'));
				}
			break;
		}
	}

}