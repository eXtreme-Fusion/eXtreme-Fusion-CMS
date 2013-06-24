<?php

class Thread_Data {

	protected $_pdo;

	public function __construct(Data $_pdo)
	{
		$this->_pdo = $_pdo;
	}

	public function fetchByID($id)
	{
		$threads = $this->_pdo->getData('
			SELECT t.*,
			(SELECT COUNT(e.id)-1 FROM [entries] e WHERE e.thread_id=t.id) as entries,
			(SELECT u.username FROM [users] u WHERE t.user_id=u.id) as username
			FROM [threads] t
			WHERE category_id = :id
		', array(':id', $id, PDO::PARAM_INT));

		if ( ! $this->_pdo->getRowsCount($threads))
			return FALSE;

		return $threads;
	}

}