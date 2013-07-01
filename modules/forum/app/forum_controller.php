<?php

abstract class Forum_Controller extends Abstract_Controller {

	protected $request;

	protected $pdo;

	protected $bbcode;

	public function render()
	{
		$this->ec->header->set('<link href="'.ADDR_MODULES.'forum/assets/forum.css" rel="stylesheet">');

		$this->locale->moduleLoad('lang', 'forum');

		$this->request = $this->ec->request;

		$this->pdo = $this->ec->pdo;

		$this->bbcode = $this->ec->sbb;

		return parent::render();
	}

	public function model($name, $params = array())
	{
		return parent::model($name, $params)
			->setData($this->pdo);
	}

}