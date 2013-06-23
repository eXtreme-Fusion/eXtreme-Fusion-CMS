<?php

class Index_Controller extends Forum_Controller {

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
				'board'    => array($this->ec->getService('pdo')),
				'category' => array($this->ec->getService('pdo')),
			),
		));
	}

}