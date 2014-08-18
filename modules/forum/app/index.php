<?php defined('EF5_SYSTEM') || exit;

class Index_Controller extends Forum_Controller {

	public function index()
	{
		$board    = $this->model('board');
		$category = $this->model('category');
		$move = $this->model('move');

		return $this->view('index', array(
			'board'    => $board,
			'category' => $category,
			'move' => $move
		));
	}

}