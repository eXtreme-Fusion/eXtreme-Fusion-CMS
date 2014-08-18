<?php defined('EF5_SYSTEM') || exit;

class Category_Controller extends Forum_Controller {

	public function index()
	{
		$category = $this
			->model('category')
			->findByID($this->params[0]);

		if ( ! $category)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}
		
		if ($category['url'] != NULL)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 403));
		}

		$threads = $this
			->model('thread')
			->fetchByID($category['id']);

		return $this->view('category', array(
			'category' => $category,
			'threads'  => $threads,
		));
	}

}