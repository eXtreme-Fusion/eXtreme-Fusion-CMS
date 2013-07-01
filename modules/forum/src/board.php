<?php

class Board_Model extends Abstract_Model {

	public function fetchAll()
	{
		$boards = $this->_pdo->getData('SELECT * FROM [boards] ORDER BY order ASC');

		if ( ! $this->_pdo->getRowsCount($boards))
			return FALSE;

		return $boards;
	}

}