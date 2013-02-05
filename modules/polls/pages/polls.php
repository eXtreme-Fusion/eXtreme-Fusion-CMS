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
#***********
// Wczytywanie plików językowych
$_locale->moduleLoad('lang', 'polls');

// Nagłówek HEAD: tytuł podstrony, słowa kluczowe, opis
$_tpl->assign('Theme', array(
	'Title' => __('Polls'),
	'Keys' => '',
	'Desc' => ''
));

// Określa, czy blokować wykonanie pliku TPL z katalogu szablonu
define('THIS', FALSE);

// Definiowanie katalogu z szablonami TPL
$_tpl->setPageCompileDir(DIR_MODULES.'polls'.DS.'templates'.DS);
#***********

if ($_user->isLoggedIn())
{
	$archive = $_route->getByID(1);

	// Wyniki wybranej ankiety
	if ($archive === 'archive' && $_route->getByID(2) && isNum($_route->getByID(2)))
	{
		$row = $_pdo->getRow('
				SELECT `id`, `question`, `response`, `date_start`, `date_end`
				FROM [polls]
				WHERE `date_end` != 0 AND `id` = :id
				ORDER BY `date_start`',
			array(':id', $_route->getByID(2), PDO::PARAM_INT)
		);

		if ($_pdo->getRowsCount($row))
		{
			$i = 0; $polls = array();
			if($row)
			{
				$query = $_pdo->getRow('SELECT * FROM [polls_vote] WHERE `poll_id` = :id AND `user_id` = :user',
					array(
						array(':id', $row['id'], PDO::PARAM_INT),
						array(':user', $_user->get('id'), PDO::PARAM_INT)
					)
				);

				if($query)
				{
					$query2 = $_pdo->getData('SELECT `id` FROM [polls_vote] WHERE `poll_id` = :id',
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
						$query = $_pdo->getData('SELECT * FROM [polls_vote] WHERE `poll_id` = :id AND `response` = :respo',
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
	else
	{
		if ($_request->post('vote_'.$_request->post('polls')->show())->show())
		{
			$count = $_pdo->exec('INSERT INTO [polls_vote] (`poll_id`, `user_id`, `response`, `date`) VALUES (:polls, :user, :response, '.time().')',
				array(
					array(':polls', $_request->post('polls')->show(), PDO::PARAM_INT),
					array(':user', $_user->get('id'), PDO::PARAM_INT),
					array(':response', $_request->post('response')->show(), PDO::PARAM_INT)
				)
			);
			HELP::redirect(ADDR_SITE.'polls.html');
		}
	}

	if ($_route->getAction())
	{
		include DIR_MODULES.'polls'.DS.'class'.DS.'polls.php';

		$_polls = new Module_Polls($_pdo, $_user);

		$_polls->setLocalData();

		// Wyświetlanie ankiet, w których się brało udział, zarówno zakończonych, jak i aktywnych
		if ($_route->getAction() === 'my_votes')
		{
			$_tpl->assign('page', 'my_votes');

			// Trwające ankiety
			$polls = array(); $response = array(); $i = 0;
			foreach($_polls->parseResponse($_polls->getActiveVoted()) as $row)
			{

				$query2 = $_pdo->getData('SELECT `id` FROM [polls_vote] WHERE `poll_id` = :id',
					array(':id', $row['id'], PDO::PARAM_INT)
				);

				$polls[$i] = array(
					'ID' => $row['id'],
					'Question' => $row['question'],
					'DateStart' => HELP::showDate('shortdate', $row['date_start']),
					'Votes' => $_pdo->getRowsCount($query2),
					'ShowResults' => $row['show_results']
				);

				// Czy prezentować wyniki ankiety przed jej zakończeniem?
				if ($row['show_results'])
				{

					foreach($row['response'] as $key => $val)
					{
						$query = $_pdo->getData('SELECT * FROM [polls_vote] WHERE `poll_id` = :id AND `response` = :respo',
							array(
								array(':id', $row['id'], PDO::PARAM_INT),
								array(':respo', $n, PDO:: PARAM_INT)
							)
						);

						$percent = round($_pdo->getRowsCount($query)/$_pdo->getRowsCount($query2)*100);

						$response[$i][] = array(
							'val' => $val,
							'key' => $key,
							'L' => $_pdo->getRowsCount($query),
							'P' => $percent,
						);
					}
				}
				else
				{
					foreach($row['response'] as $key => $val)
					{
						$response[$i][] = array(
							'key' => $key,
							'val' => $val,
						);
					}
				}

				$i++;
			}

			$_tpl->assign('Response', $response);
			$_tpl->assign('Data', $polls);


			// Zakończone ankiety

			$polls = array(); $response = array(); $i = 0;
			foreach($_polls->parseResponse($_polls->getInactiveVoted()) as $row)
			{
				$query2 = $_pdo->getData('SELECT `id` FROM [polls_vote] WHERE `poll_id` = :id',
					array(':id', $row['id'], PDO::PARAM_INT)
				);

				$polls[$i] = array(
					'ID' => $row['id'],
					'Question' => $row['question'],
					'DateStart' => HELP::showDate('shortdate', $row['date_start']),
					'Votes' => $_pdo->getRowsCount($query2)
				);

				$n = 0;
				foreach($row['response'] as $key => $val)
				{
					$query = $_pdo->getData('SELECT * FROM [polls_vote] WHERE `poll_id` = :id AND `response` = :respo',
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

				$i++;
			}

			$_tpl->assign('Data2', $polls);
			$_tpl->assign('Response2', $response);
		}
		// Wyświetlanie wszystkich zakończonych ankiet z zaznaczeniem tych, w których się brało udział
		elseif ($_route->getAction() === 'closed')
		{
			$_tpl->assign('page', 'closed');

			// Pobieranie ankiet
			$data = $_polls->parseResponse($_polls->getInactive());

			if ($data)
			{
				// Pobieranie uporządkowanych głosów w poszczególnych ankietach
				$voting = $_polls->getVotingData($data);

				$polls = array(); $response = array(); $i = 0;
				foreach($data as $row)
				{
					// Porządkowanie danych odnośnie oddanych głósów w danej ankiecie
					$response[$i] = $_polls->parseResponses($row, $voting);

					$polls[$i] = array(
						'ID' => $row['id'],
						'Question' => $row['question'],
						'DateStart' => HELP::showDate('shortdate', $row['date_start']),
						'Votes' => count($voting[$row['id']])
					);

					$i++;
				}

				$_tpl->assign('Data2', $polls);
				$_tpl->assign('Response2', $response);
			}


		}
		// Wyświetlanie wszystkich ankiet, w których się jeszcze nie brało udziału
		elseif ($_route->getAction() === 'active')
		{
			$_tpl->assign('page', 'active');

			$polls = array(); $response = array(); $i = 0;
			foreach($_polls->parseResponse($_polls->getActiveNotVoted()) as $row)
			{
				$polls[$i] = array(
					'ID' => $row['id'],
					'Question' => $row['question'],
					'DateStart' => HELP::showDate('shortdate', $row['date_start'])
				);


				$n = 0; $response = array();
				foreach($row['response'] as $key => $val)
				{
					$response[$i][$key] = array(
						'Val' => $val,
						'N' => $n
					);
					$n++;
				}

				$i++;
			}

			$_tpl->assign('Data', $polls);
			$_tpl->assign('Response', $response);
		}
	}
}
else
{
	$_user->onlyForUsers($_route);
}