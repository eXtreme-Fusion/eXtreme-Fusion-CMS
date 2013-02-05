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
$_locale->moduleLoad('polls', 'polls');
// $_head->set('<script src="'.ADDR_MODULES.'polls_panel/polls_panel.js"></script>'); // nie wiem czemu nie chce dzia³aæ ;o
echo('<script src="'.ADDR_MODULES.'polls/templates/javascripts/polls_panel.js"></script>');
  
if ($_request->post('vote_'.$_request->post('polls')->show())->show())
{
	$count = $_pdo->exec('INSERT INTO [polls_vote] (`poll_id`, `user_id`, `response`, `date`) VALUES (:polls, :user, :response, '.time().')',
		array(
			array(':polls', $_request->post('polls')->show(), PDO::PARAM_INT),
			array(':user', $_user->get('id'), PDO::PARAM_INT),
			array(':response', $_request->post('response')->show(), PDO::PARAM_INT)
		)
	);
	
	$_request->redirect(ADDR_SITE.'polls.html');
}

$count = $_pdo->getData('
	SELECT `id`, `question`, `response`, `date_start`
	FROM [polls]
	WHERE `date_end` = 0
	ORDER BY `date_start` DESC
');

	$count = $_pdo->getRowsCount($count);

$query = $_pdo->getData('
	SELECT `id`, `question`, `response`, `date_start`
	FROM [polls]
	WHERE `date_end` = 0
	ORDER BY `date_start` DESC LIMIT 0,'.$count
);

if ($_pdo->getRowsCount($query))
{                       
	$i = 0; $polls = array();
	foreach($query as $row)
	{
		$query = $_pdo->getRow('SELECT * FROM [polls_vote] WHERE `poll_id` = :id AND `user_id` = :user',
			array(
				array(':id', $row['id'], PDO::PARAM_INT),
				array(':user', $_user->get('id'), PDO::PARAM_INT)
			)
		);

		if( ! $query)
		{
			$polls[$i] = array(
				'id' => $row['id'],
				'question' => $row['question'],
				'date_start' => HELP::showDate('shortdate', $row['date_start'])
			);

			$n = 0;
			foreach(unserialize($row['response']) as $key => $val)
			{
				$response[$i][$key] = array(
					'val' => $val,
					'n' => $n
				);
				$n++;
			}

			$_panel->assign('polls_data', $polls);
			$_panel->assign('polls_response', $response);
		}

		if($_user->isLoggedIn())
		{
			$_panel->assign('login', TRUE);
		}

		$i++;
	}
	
	$_panel->assign('polls_archive', $_route->path(array('controller' => 'polls', 'action' => 'archive')));
}