<?php

/**
foreach($data as $r)
{
	if ($right && $r['right'] < $right)
	{
		$z[] = $right;
	} 
	elseif ($z && $r['right'] > $z[count($z)-1])
	{
		array_pop($z);
	}
	
	foreach($z as $val)
	{
		echo '+';
	}
	
	echo $r['nazwa'].' <br />';
	
	$right = $r['right'];
}
**/

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
}

$_tree = new Tree($_pdo, 'drzewko');

$_tpl->assign('tree', $_tree->get(1));

/*
	foreach($_tree->get(1) as $val)
	{
		if (isset($val['new']))
		{
			echo '<ul>';
		}
		elseif (isset($val['end']))
		{
			echo '</ul>';
		}
		
		echo '<li>'.$val['name'].'</li>';
	}
*/

	