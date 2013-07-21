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

}