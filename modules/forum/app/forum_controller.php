<?php

abstract class Forum_Controller extends Abstract_Controller {

	protected $pdo;

	public function render()
	{
		$this->ec->header->set('<link href="'.ADDR_MODULES.'forum/assets/forum.css" rel="stylesheet">');

		$this->locale->moduleLoad('lang', 'forum');

		$this->pdo = $this->ec->getService('pdo');

		return parent::render();
	}

	public function model($name, $params = array())
	{
		return parent::model($name, $params)
			->setData($this->pdo);
	}

}