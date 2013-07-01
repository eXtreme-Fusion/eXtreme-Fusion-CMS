<?php

class Board_Model extends Abstract_Model {

	public function fetchAll()
	{
		$boards = $this->_pdo->getData('SELECT b.* FROM [boards] b ORDER BY b.order ASC');

		if ( ! $this->_pdo->getRowsCount($boards))
			return FALSE;

		return $boards;
	}

}