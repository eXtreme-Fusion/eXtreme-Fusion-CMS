<?php defined('EF5_SYSTEM') || exit;

class Category_Model extends Abstract_Model {

	public function fetchByID($id)
	{
		$categories = $this->_pdo->getData('
			SELECT
				c.*,
				e.id as entry,
				e.user_id as user,
				e.timestamp as timestamp,
				e.thread_id as thread
			FROM [board_categories] c
			LEFT JOIN (SELECT e.* FROM [entries] e ORDER BY e.id DESC) e
			ON e.thread_id = (SELECT t.id FROM [threads] t WHERE t.category_id = c.id ORDER BY t.timestamp DESC LIMIT 1)
			WHERE c.board_id = :id
			GROUP BY c.id
			ORDER BY c.order ASC
		', array(':id', $id, PDO::PARAM_INT));

		$_categories = array();

		foreach ($categories as $category)
		{
			$_categories[] = array_merge($category, $this->getCount($category['id']));
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
			ON b.id = c.board_id
			WHERE c.id = :id
		', array(':id', $id, PDO::PARAM_INT));
	}

	public function getCount($id)
	{
		$count = $this->_pdo->getData('
			SELECT
				t.id,
				(SELECT COUNT(e.id) FROM [entries] e WHERE e.thread_id = t.id) as entries
			FROM [threads] t
			WHERE t.category_id = :id
		', array(':id', $id, PDO::PARAM_INT));

		$threads = $entries = 0;

		foreach ($count as $row)
		{
			$entries = $entries + $row['entries'];
			$threads++;
		}

		return array(
			'threads' => $threads,
			'entries' => $entries,
		);
	}

}