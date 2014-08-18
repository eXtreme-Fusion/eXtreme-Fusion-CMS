<?php defined('EF5_SYSTEM') || exit;

class Move_Model extends Abstract_Model {

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
}