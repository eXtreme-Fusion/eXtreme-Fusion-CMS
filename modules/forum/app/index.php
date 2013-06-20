<?php

class Index_Controller extends Abstract_Controller
{
	public function render()
	{
		$action = $this->action;
		return $this->$action();
	}
	
	public function index()
	{
		return $this->view(array(
			'class' => 'index', 
			'construct' => array(),
			'models' => array(
				'forum' => array($this->get('ec')->getService('pdo')),
			),
			'method' => 'index'
		));

		
	}
}