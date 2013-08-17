<?php defined('EF5_SYSTEM') || exit;

class Board_Model extends Abstract_Model {

	
	
	public function findByID($id)
	{
		return $this->_pdo->getRow('
			SELECT b.*
			FROM [boards] b
			WHERE b.id = :id
		', array(':id', $id, PDO::PARAM_INT));
	}

	public function fetchAll()
	{
		$boards = $this->_pdo->getData('SELECT b.* FROM [boards] b ORDER BY b.order ASC');

		if ($this->_pdo->getRowsCount($boards))
		{	
			$data = array(); $i = 0;
			foreach ($boards as $index => $var)
			{
				$data[$index] = $var;
				
				HELP::array_push_associative($data[$index], array('row_color' => $i % 2 == 0 ? 'cals1' : 'cals2'));
				
				$i++;
			}
			
			return $data;
		}
		else
		{
			return FALSE;
		}
	}

}