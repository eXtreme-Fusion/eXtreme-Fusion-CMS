<?php

class Index_View extends Abstract_View
{
	public function index(Forum_Data $_data)
	{
		
		$this->assign('test', 'admin');
		$this->template('index');
	}
}