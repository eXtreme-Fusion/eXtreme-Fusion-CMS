<?php

class Entry_Model extends Abstract_Model {

	public function fetchByID($id)
	{
		$entries = $this->_pdo->getData('
			SELECT
				e.*,
				u.username as username,
				u.role as role,
				(SELECT COUNT(entry.id) FROM [entries] entry WHERE entry.user_id=e.user_id) as entries
			FROM [entries] e
			LEFT JOIN [users] u
			ON u.id=e.user_id
			WHERE thread_id = :id
			ORDER BY e.id ASC
		', array(':id', $id, PDO::PARAM_INT));

		if ( ! $this->_pdo->getRowsCount($entries))
			return FALSE;

		return $entries;
	}

}