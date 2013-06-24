<?php

class Category_Controller extends Forum_Controller {

	public function index()
	{
		$category = $this
			->model('category', array($this->ec->getService('pdo')))
			->findByID($this->params[0]);

		if ($category === FALSE)
		{
			return $this->router->trace(array('controller' => 'error', 'action' => 404));
		}

		$threads = $this
			->model('thread', array($this->ec->getService('pdo')))
			->fetchByID($category['id']);

		return $this->view('category', array(
			'category' => $category,
			'threads'  => $threads,
		));
	}

}