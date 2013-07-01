<?php

class Thread_Model extends Abstract_Model {

	public function fetchByID($id)
	{
		$threads = $this->_pdo->getData('
			SELECT 
				t.*,
				u.username as username,
				(SELECT COUNT(e.id)-1 FROM [entries] e WHERE e.thread_id=t.id) as entries
			FROM [threads] t
			LEFT JOIN [users] u
			ON u.id=t.user_id
			WHERE category_id = :id
			ORDER BY
				t.is_pinned DESC,
				t.id DESC 
		', array(':id', $id, PDO::PARAM_INT));

		if ( ! $this->_pdo->getRowsCount($threads))
			return FALSE;

		return $threads;
	}

	public function findByID($id)
	{
		return $this->_pdo->getRow('
			SELECT
				t.*,
				c.id as category_id,
				c.title as category,
				b.id as board_id,
				b.title as board,
				e.content as entry,
				e.id as entry_id
			FROM [threads] t
			LEFT JOIN [board_categories] c
			ON c.id=t.category_id
			LEFT JOIN [boards] b
			ON b.id=c.board_id
			LEFT JOIN [entries] e
			ON e.thread_id=t.id AND e.user_id=t.user_id
			WHERE t.id = :id
		', array(':id', $id, PDO::PARAM_INT));
	}

}