<?php defined('EF5_SYSTEM') || exit;

class User_Model extends Abstract_Model {

	protected $_user;

	public function __construct(User $user)
	{
		$this->_user   = $user;
	}

	public function isAuthor($id)
	{
		return ($this->_user->get('id') === $id);
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