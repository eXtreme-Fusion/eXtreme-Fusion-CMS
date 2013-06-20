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
			// Nazwa pliku widoku bez rozszerzenia
			'class' => 'index',
			// Parametry konstruktora (opcjonalne)
			'construct' => array(),
			// Metoda widoku, która ma zostać użyta (opcjonalne)
			'method' => 'index',
			// Modele dla metody widoku (opcjonalne)
			'models' => array(
				'forum' => array($this->get('ec')->getService('pdo')),
			),
		));
	}
}