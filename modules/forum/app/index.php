<?php

class Index_Controller extends Abstract_Controller {

	public function render()
	{
		// Tymczasowo
		$this->get('ec')->header->set('<link href="'.ADDR_MODULES.'forum/assets/forum.css" rel="stylesheet">');

		return $this->{$this->action}();
	}

	public function index()
	{
		return $this->view(array(

			// Nazwa pliku widoku bez rozszerzenia
			'class' => 'index',

			// Parametry konstruktora (opcjonalne)
			'construct' => array(),

			// Metoda widoku, która ma zostać użyta (opcjonalne)
			'method' => 'index',

			// Modele dla metody widoku (opcjonalne)
			'models' => array(
				'board'    => array($this->get('ec')->getService('pdo')),
				'category' => array($this->get('ec')->getService('pdo')),
			),
		));
	}

}