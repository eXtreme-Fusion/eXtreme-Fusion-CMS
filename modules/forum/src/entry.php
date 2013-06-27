<?php

class Entry_Model extends Abstract_Model {

	public function fetchByID($id)
	{
		$entries = $this->_pdo->getData('
			SELECT
				e.*,
				u.username as username,
				u.role as role
			FROM [entries] e
			LEFT JOIN [users] u
			ON u.id=e.user_id
			WHERE thread_id = :id
		', array(':id', $id, PDO::PARAM_INT));

		if ( ! $this->_pdo->getRowsCount($entries))
			return FALSE;

		return $entries;
	}

}