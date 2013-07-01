<?php

class User_Model extends Abstract_Model {

	protected $_user;
	protected $_thread;

	public function __construct(User $user, $thread)
	{
		$this->_user   = $user;
		$this->_thread = $thread;
	}

	public function isAuthor()
	{
		return ($this->_thread['user_id'] === $this->_user->get('id'));
	}

	public function getCount()
	{
		if ($this->_user->iGUEST() === TRUE)
		{
			throw new systemException(__('User is not logged in'));
		}

		return $this->_pdo->getMatchRowsCount('
			SELECT e.id
			FROM [entries] e
			WHERE e.user_id = :id
		', array(':id', $this->_user->get('id'), PDO::PARAM_INT));
	}

}