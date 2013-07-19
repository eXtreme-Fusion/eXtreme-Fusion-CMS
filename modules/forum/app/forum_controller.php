<?php defined('EF5_SYSTEM') || exit;

abstract class Forum_Controller extends Abstract_Controller {

	protected $request;

	protected $pdo;

	protected $bbcode;

	protected $logged_in = FALSE;

	protected $is_admin = FALSE;

	public function render()
	{
		$this->ec->header->set('<link href="'.ADDR_MODULES.'forum/assets/forum.css" rel="stylesheet">');

		$this->locale->moduleLoad('lang', 'forum');

		$this->request = $this->ec->request;

		$this->pdo = $this->ec->pdo;

		$this->bbcode = $this->ec->sbb;

		$this->logged_in = $logged_in = $this->user->iUSER();
		$this->is_admin  = ($logged_in && $this->user->iADMIN());

		return parent::render();
	}

	public function model($name, $params = array())
	{
		return parent::model($name, $params)
			->setData($this->pdo);
	}

}