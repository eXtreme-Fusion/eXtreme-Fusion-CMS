<?php

class Index_View extends Abstract_View {

	public function index(Board_Data $_board, Category_Data $_category)
	{
		$this
			->assign('board', $_board)
			->assign('category', $_category)
			->template('index');
	}

}