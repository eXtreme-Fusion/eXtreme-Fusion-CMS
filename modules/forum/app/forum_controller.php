<?php

abstract class Forum_Controller extends Abstract_Controller {

	public function render()
	{
		// Tymczasowo
		$this->ec->header->set('<link href="'.ADDR_MODULES.'forum/assets/forum.css" rel="stylesheet">');

		return parent::render();
	}

}