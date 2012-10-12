<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
if (!defined("EF5_SYSTEM")) { die("Access denied"); }

class Points
{
	private $_pdo;
	private $_system;

	public function __construct($pdo, $system)
	{
		$this->_pdo = $pdo;
		$this->_system = $system;
	}

	/*-------------------
	| PL: Dodaje i odejmuje punkty użytkownikowi.
	| EN: Adds and substracts user points.
	+------------------*/
	public function add($id, $points, $comment)
	{
		if(isNum($id) && isNum($points))
		{
			$this->_pdo->exec('UPDATE [users] SET `points`=`points`+:points WHERE `id` = :id',
				array(
					array(':points', $points, PDO::PARAM_INT),
					array(':id', $id, PDO::PARAM_INT)
				)
			);

			if($points > 0)
			{
				$this->_pdo->exec('INSERT INTO [points_history] (`user_id`, `points`, `text`, `date`) VALUES (:user, :points, :text, '.time().')',
					array(
						array(':user', $id, PDO::PARAM_INT),
						array(':points', $points, PDO::PARAM_INT),
						array(':text', __('Added :points point with :section.', array(':points' => $points, ':section' => $comment)), PDO::PARAM_STR)
					)
				);
			}
			else
			{
				$this->_pdo->exec('INSERT INTO [points_history] (`user_id`, `points`, `text`, `date`) VALUES (:user, :points, :text, '.time().')',
					array(
						array(':user', $id, PDO::PARAM_INT),
						array(':points', $points, PDO::PARAM_INT),
						array(':text', __('Subtracted :points point with :section.', array(':points' => $points, ':section' => $comment)), PDO::PARAM_STR)
					)
				);
			}
			
			$this->_system->clearCache('point_system');
			
			return TRUE;
		}
		else
		{
			throw new systemException('Error: There was not given numeric value.');
		}
	}    

	/*-------------------
	| PL: Usuwa wszystkie punkty użytkownikowi.
	| EN: Removes all user points.
	+------------------*/
	public function deleteAll($id)
	{
		$this->_pdo->exec('UPDATE [users] SET `points`=0 WHERE `id` = :id',
			array(':id', $id, PDO::PARAM_INT)
		);
		
		$this->_pdo->exec('INSERT INTO [points_history] (`user_id`, `points`, `text`, `date`) VALUES (:user, :points, :text, '.time().')',
			array(
				array(':user', $id, PDO::PARAM_INT),
				array(':points', '0', PDO::PARAM_INT),
				array(':text', __('All user points have been deleted.'), PDO::PARAM_STR)
			)
		);
		
		$this->_system->clearCache('point_system');

		return TRUE;
	}

	/*-------------------
	| PL: Usuwa punkty wszystkim użytkownikom.
	| EN: Removes all users points.
	+------------------*/
	public function clearAll()
	{
		$this->_pdo->exec('UPDATE [users] SET `points`=0');
		$this->_pdo->exec('TRUNCATE TABLE [points_history]');
		$this->_pdo->exec('INSERT INTO [points_history] (`user_id`, `points`, `text`, `date`) VALUES (0, 0 :text, '.time().')',
			array(':text', __('All users points have been deleted.'), PDO::PARAM_STR)
		);
		
		$this->_system->clearCache('point_system');

		return TRUE;
	}
	
	/*-------------------
	| PL: Usuwa historie punktów użytkownika.
	| EN: Removes all history users points.
	+------------------*/
	public function clearHistory($id)
	{
		$this->_pdo->exec('DELETE FROM [points_history] WHERE `user_id` = :id',
			array(':id', $id, PDO::PARAM_INT)
		);
		
		$this->_pdo->exec('INSERT INTO [points_history] (`user_id`, `points`, `text`, `date`) VALUES (:user, :points, :text, '.time().')',
			array(
				array(':user', $id, PDO::PARAM_INT),
				array(':points', '0', PDO::PARAM_INT),
				array(':text', __('Points history has been deleted.'), PDO::PARAM_STR)
			)
		);
		
		$this->_system->clearCache('point_system');
		
		return TRUE;
	}
	
	/*-------------------
	| PL: Pokazuje punkty użytkownika.
	| EN: Shows user points.
	+------------------*/
	public function show($id)
	{
		$points = $this->_pdo->getRow('SELECT `points` FROM [users] WHERE `id` = '.$id);
		return $points['points'];
	}

	/*-------------------
	| PL: Pokazuje rangę użytkownika.
	| EN: Show user rank.
	+------------------*/
	public function showRank($id)
	{
		$points = $this->_pdo->getRow('SELECT `points` FROM [users] WHERE `id` = '.$id);
		$rank = $this->_pdo->getRow('SELECT `ranks` FROM [ranks] WHERE `points` <= '.$points['points'].' ORDER BY `points` DESC');
		return $rank['ranks'];
	}
}
