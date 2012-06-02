<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
$_locale->moduleLoad('lang', 'polls');

#*********** Settings
$theme = array(
	'Title' => __('Polls'),
	'Keys' => '',
	'Desc' => ''
);
$_tpl->assign('Theme', $theme);

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

// Definiowanie katalogu templatek modułu
$_tpl->setPageCompileDir(DIR_MODULES.'polls'.DS.'templates'.DS);

#***********

if ($_user->isLoggedIn())
{
	$archive = $_route->getByID(1);
	
	if($archive === 'archive' && isNum($_route->getByID(2)))
	{
		$poll = $_route->getByID(2);
		$row = $_pdo->getRow('
				SELECT `id`, `question`, `response`, `date_start`, `date_end`
				FROM [polls]
				WHERE `date_end` != 0 AND `id` = :id
				ORDER BY `date_start`',
			array(':id', $poll, PDO::PARAM_INT)
		);
	
		if ($_pdo->getRowsCount($row))
		{			
			$i = 0; $polls = array();
			if($row)
			{
				$query = $_pdo->getRow('SELECT * FROM [polls_vote] WHERE `polls` = :id AND `user_id` = :user',
					array(
						array(':id', $row['id'], PDO::PARAM_INT),
						array(':user', $_user->get('id'), PDO::PARAM_INT)
					)
				);
			
				if($query)
				{
					$query2 = $_pdo->getData('SELECT `id` FROM [polls_vote] WHERE `polls` = :id',
						array(
							array(':id', $row['id'], PDO::PARAM_INT),
						)
					);
					
					$polls[$i] = array(
						'ID' => $row['id'],
						'Question' => $row['question'],
						'DateStart' => HELP::showDate('shortdate', $row['date_start']),
						'DateEnd' => HELP::showDate('shortdate', $row['date_end']),
						'Votes' => $_pdo->getRowsCount($query2)
					);
			
					$n = 0;
					foreach(unserialize($row['response']) as $key => $val)
					{
						$query = $_pdo->getData('SELECT * FROM [polls_vote] WHERE `polls` = :id AND `response` = :respo',
							array(
								array(':id', $row['id'], PDO::PARAM_INT),
								array(':respo', $n, PDO:: PARAM_INT)
							)
						);
					
						$percent = round($_pdo->getRowsCount($query)/$_pdo->getRowsCount($query2)*100);
					
						$response[$i][$key] = array(
							'Val' => $val,
							'N' => $n,
							'L' => $_pdo->getRowsCount($query),
							'P' => $percent,
						);
					
						$n++;
					}
				
					$_tpl->assign('Data', $polls);
					$_tpl->assign('Response', $response);
				}
			
				$i++;
			}
		}
		
		$_tpl->assign('Archive', TRUE);
	}
	elseif($archive === 'archive')
	{
		$query = $_pdo->getData('
			SELECT `id`, `question`, `response`, `date_start`
			FROM [polls]
			WHERE `date_end` != 0
			ORDER BY `date_start`
		');
	
		if ($_pdo->getRowsCount($query))
		{			
			$i = 0; $polls = array();
			foreach($query as $row)
			{
				$polls[$i] = array(
					'ID' => $row['id'],
					'Question' => $row['question'],
					'DateStart' => HELP::showDate('shortdate', $row['date_start'])
				);
			
				
				$_tpl->assign('Data', $polls);
			
				$i++;
			}
		}
		
		$_tpl->assign('Archives', TRUE);
	}
	else
	{
      
		if ($_request->post('vote_'.$_request->post('polls')->show())->show())
		{
			$count = $_pdo->exec('INSERT INTO [polls_vote] (`polls`, `user_id`, `response`, `date`) VALUES (:polls, :user, :response, '.time().')',
				array(
					array(':polls', $_request->post('polls')->show(), PDO::PARAM_INT),
					array(':user', $_user->get('id'), PDO::PARAM_INT),
					array(':response', $_request->post('response')->show(), PDO::PARAM_INT)
				)
			);
			HELP::redirect(ADDR_SITE.'polls.html');
		}
    
    
		$query = $_pdo->getData('
			SELECT `id`, `question`, `response`, `date_start`
			FROM [polls]
			WHERE `date_end` = 0
			ORDER BY `date_start`
		');
	
		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $polls = array();
			foreach($query as $row)
			{
				$query = $_pdo->getRow('SELECT * FROM [polls_vote] WHERE `polls` = :id AND `user_id` = :user',
					array(
						array(':id', $row['id'], PDO::PARAM_INT),
						array(':user', $_user->get('id'), PDO::PARAM_INT)
					)
				);
				
				if( ! $query)
				{
					$polls[$i] = array(
						'ID' => $row['id'],
						'Question' => $row['question'],
						'DateStart' => HELP::showDate('shortdate', $row['date_start'])
					);
			
					$n = 0;
					foreach(unserialize($row['response']) as $key => $val)
					{
						$response[$i][$key] = array(
							'Val' => $val,
							'N' => $n
						);
						$n++;
					}
				
					$_tpl->assign('Data', $polls);
					$_tpl->assign('Response', $response);
				}
			
				$i++;
			}
		}
	
	
		$query = $_pdo->getData('
			SELECT `id`, `question`, `response`, `date_start`
			FROM [polls]
			WHERE `date_end` = 0
			ORDER BY `date_start`
		');
	
		if ($_pdo->getRowsCount($query))
		{
			$i = 0; $polls = array();
			foreach($query as $row)
			{
				$query = $_pdo->getRow('SELECT * FROM [polls_vote] WHERE `polls` = :id AND `user_id` = :user',
					array(
						array(':id', $row['id'], PDO::PARAM_INT),
						array(':user', $_user->get('id'), PDO::PARAM_INT)
					)
				);
			
				if($query)
				{
					$query2 = $_pdo->getData('SELECT `id` FROM [polls_vote] WHERE `polls` = :id',
						array(':id', $row['id'], PDO::PARAM_INT)
					);
					
					$polls[$i] = array(
						'ID' => $row['id'],
						'Question' => $row['question'],
						'DateStart' => HELP::showDate('shortdate', $row['date_start']),
						'Votes' => $_pdo->getRowsCount($query2)
					);
			
					$n = 0;
					foreach(unserialize($row['response']) as $key => $val)
					{
						$query = $_pdo->getData('SELECT * FROM [polls_vote] WHERE `polls` = :id AND `response` = :respo',
							array(
								array(':id', $row['id'], PDO::PARAM_INT),
								array(':respo', $n, PDO:: PARAM_INT)
							)
						);
					
						$percent = round($_pdo->getRowsCount($query)/$_pdo->getRowsCount($query2)*100);
					
						$response[$i][$key] = array(
							'Val' => $val,
							'N' => $n,
							'L' => $_pdo->getRowsCount($query),
							'P' => $percent,
						);
				
						$n++;
					}
				
					$_tpl->assign('Data2', $polls);
					$_tpl->assign('Response2', $response);
				}
			
				$i++;
			}
		}
	}
}
else
{
	$_tpl->assign('login', TRUE);
}