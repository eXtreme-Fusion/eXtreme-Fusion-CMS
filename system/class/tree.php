<?php

class Tree
{
	// Database object
	protected $_pdo;

	// Name of data table
	protected $table;

	public function __construct($_pdo, $table)
	{
		$this->_pdo = $_pdo;

		$table = new Edit($table);
		$this->table = $table->strip();
	}

	public function get($id)
	{
		if (isNum($id))
		{
			$row = $this->_pdo->getRow('SELECT `left`, `right` FROM ['.$this->table.'] WHERE id = :id', array(':id', $id, PDO::PARAM_INT));

			$data = $this->_pdo->getData('SELECT `name`, `right`, `left` FROM ['.$this->table.'] WHERE `left` BETWEEN '.$row['left'].' AND `right` ORDER BY `left`');

			$right = NULL; $z = NULL; $i = 0;
			$elem = array();

			foreach($data as $r)
			{
				$elem[$i]['name'] = $r['name'];

				if ($right && $r['right'] < $right)
				{
					$z = $right;
					$elem[$i]['new'] = TRUE;
				}
				elseif ($z && $r['right'] > $z)
				{
					$elem[$i]['end'] = TRUE;
				}

				$right = $r['right'];
				$i++;
			}

			return $elem;
		}

		return FALSE;
	}

	public function delete($id, $del_sub = FALSE)
	{
		if (isNum($id))
		{
			if ($data = $this->_pdo->getRow('SELECT `left`, `right` FROM ['.$this->table.'] WHERE id = :id', array('id', $id, PDO::PARAM_INT)))
			{
				if ($del_sub)
				{
					// Iloœc elementów podzbioru
					$count = ($data['right']-$data['left']-1);

					// Przesuniêcie left/right pozosta³ych elementów
					$order = $count + 2;

					$this->_pdo->exec('DELETE FROM ['.$this->table.'] WHERE `left` BETWEEN '.$data['left'].' AND '.$data['right'].' OR `id` = '.$id);

					$this->_pdo->exec('UPDATE ['.$this->table.'] SET `left` = `left`-'.$order.', `right` = `right`-'.$order.' WHERE `right` > '.$data['right'].' AND `left` > '.$data['left']);

					$this->_pdo->exec('UPDATE ['.$this->table.'] SET `right` = `right`-'.$order.' WHERE `right` > '.$data['right'].' AND `left` < '.$data['left']);

				}
				else
				{
					$this->_pdo->exec('UPDATE ['.$this->table.'] SET `left` = `left`-1, `right` = `right`-1 WHERE `left` > '.$data['left'].' AND `right` < '.$data['right']);

					$this->_pdo->exec('UPDATE ['.$this->table.'] SET `left` = `left`-2 WHERE `right` > '.$data['right'].' AND `left` > '.$data['left']);
					$this->_pdo->exec('UPDATE ['.$this->table.'] SET `right` = `right`-2 WHERE `right` > '.$data['right']);


				}

				$this->_pdo->exec('DELETE FROM ['.$this->table.'] WHERE `id` = '.$id);

				return TRUE;
			}
		}

		return FALSE;
	}
}