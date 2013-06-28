<?php

class Index_Controller extends Forum_Controller {

	public function index()
	{
		$board    = $this->model('board');
		$category = $this->model('category');

		return $this->view('index', array(
			'board'    => $board,
			'category' => $category,
		));
	}

}