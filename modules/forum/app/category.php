<?php

class Category_Controller extends Forum_Controller {

	public function index()
	{
		return $this->view('category');
	}

}