<?php

class Index_Controller extends Forum_Controller {

	public function index()
	{
		$board    = $this->model('board', array($this->ec->getService('pdo')));
		$category = $this->model('category', array($this->ec->getService('pdo')));

		return $this->view('index', array(
			'board'    => $board,
			'category' => $category,
		));
	}

}