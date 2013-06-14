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
		return $this->view('index')->index($this->get('ec')->getService('pdo'));
	}
}