<?php

class Category_Data {

	protected $_pdo;

	public function __construct(Data $_pdo)
	{
		$this->_pdo = $_pdo;
	}

	public function fetchAll($id)
	{
		$categories = $this->_pdo->getData('SELECT * FROM [board_categories] WHERE `board_id`=:id ORDER BY `order` DESC',
			array(':id', $id, PDO::PARAM_INT));

		if ( ! $this->_pdo->getRowsCount($categories))
			return FALSE;

		return $categories;
	}

}