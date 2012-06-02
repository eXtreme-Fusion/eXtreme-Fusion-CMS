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

// $_head->set('<script src="'.ADDR_MODULES.'polls_panel/polls_panel.js"></script>'); // nie wiem czemu nie chce dzia³aæ ;o
echo('<script src="'.ADDR_MODULES.'polls/templates/javascripts/polls_panel.js"></script>');
  
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

			$_panel->assign('PanelData', $polls);
			$_panel->assign('PanelResponse', $response);
		}

		if($_user->isLoggedIn())
		{
			$_panel->assign('Login', TRUE);
		}

		$i++;
	}
}