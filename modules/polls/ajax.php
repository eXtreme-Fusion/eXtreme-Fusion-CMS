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
require_once '../../system/sitecore.php';
$_locale->moduleLoad('polls', 'polls');
echo'<script src="'.ADDR_MODULES.'polls/templates/javascripts/polls_panel.js"></script>';

if ($_request->post('send'))
{
	$count = $_pdo->exec('INSERT INTO [polls_vote] (`poll_id`, `user_id`, `response`, `date`) VALUES (:polls, :user, :response, '.time().')',
		array(
			array(':polls', $_request->post('polls')->show(), PDO::PARAM_INT),
			array(':user', $_user->get('id'), PDO::PARAM_INT),
			array(':response', $_request->post('response')->show(), PDO::PARAM_INT)
		)
	);
}

$_ajax = new Ajax(__DIR__.DS.'templates'.DS);

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

			$_ajax->assign('polls_data', $polls);
			$_ajax->assign('polls_rsponse', $response);
		}

		if($_user->isLoggedIn())
		{
			$_ajax->assign('login', TRUE);
		}

		$i++;
	}
}
$_ajax->assign('ajax', TRUE);
$_ajax->template('polls_panel.tpl');
