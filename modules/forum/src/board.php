<?php

class Board_Data {

	protected $_pdo;

	public function __construct(Data $_pdo)
	{
		$this->_pdo = $_pdo;
	}

	public function fetchAll()
	{
		$boards = $this->_pdo->getData('SELECT * FROM [boards] ORDER BY `order`');

		if ( ! $this->_pdo->getRowsCount($boards))
			return FALSE;

		return $boards;
	}

}