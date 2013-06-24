<?php

class Category_Data {

	protected $_pdo;

	public function __construct(Data $_pdo)
	{
		$this->_pdo = $_pdo;
	}

	public function fetchByID($id)
	{
		$categories = $this->_pdo->getData('
			SELECT c.*
			FROM [board_categories] c
			WHERE board_id = :id
			ORDER BY `order` DESC
		', array(':id', $id, PDO::PARAM_INT));

		$_categories = array();

		foreach ($categories as $category)
		{
			$count = $this->getCount($category['id']);

			$_categories[] = array_merge($category, array(
				'threads' => $count['threads'],
				'entries' => $count['entries'],
			));
		}

		return $_categories;
	}

	public function findByID($id)
	{
		return $this->_pdo->getRow('
			SELECT
				c.*,
				b.title as board
			FROM [board_categories] c
			LEFT JOIN [boards] b
			ON c.board_id=b.id
			WHERE c.id = :id
		', array(':id', $id, PDO::PARAM_INT));
	}

	public function getCount($id)
	{
		return $this->_pdo->getRow('
			SELECT
				t.id,
				COUNT(t.id) as threads,
				(SELECT COUNT(e.id) FROM [entries] e WHERE e.thread_id=t.id) as entries
			FROM [threads] t
			WHERE t.category_id = :id
		', array(':id', $id, PDO::PARAM_INT));
	}

}