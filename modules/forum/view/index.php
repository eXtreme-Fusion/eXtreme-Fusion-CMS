<?php

class Index_View extends Abstract_View {

	public function index($board, $category)
	{
		$this
			->assign(array(
				'board'    => $board,
				'category' => $category,
			))
			->template('index');
	}

}