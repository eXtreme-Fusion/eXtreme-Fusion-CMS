<?php

abstract class Forum_Controller extends Abstract_Controller {

	public function render()
	{
		$this->ec->header->set('<link href="'.ADDR_MODULES.'forum/assets/forum.css" rel="stylesheet">');

		$this->locale->moduleLoad('lang', 'forum');

		return parent::render();
	}

}