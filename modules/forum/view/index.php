<?php



class Index_View extends Abstract_View
{
	public function index(Data $_pdo)
	{
		
		$this->assign('test', 'admin');
		$this->template('index');
	}
}