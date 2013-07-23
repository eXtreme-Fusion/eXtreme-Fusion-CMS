<?php defined('EF5_SYSTEM') || exit;

class Acp_Controller extends Forum_Controller {

	protected $admin_area = TRUE;

	public function index()
	{
		$board    = $this->model('board');
		$category = $this->model('category');

		return $this->view('acp', array(
			'board'    => $board,
			'category' => $category,
		));
	}

}