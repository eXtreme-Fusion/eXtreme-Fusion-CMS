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
$_locale->moduleLoad('lang', 'faq');

$_head->set('<link href="'.ADDR_SITE.'modules/faq/templates/stylesheet.css" media="screen" rel="stylesheet" />');
$_head->set('<script src="'.ADDR_SITE.'modules/faq/templates/script.js"></script>');

$id = $_route->getByID(1);

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

$query = $_pdo->getData('SELECT * FROM [faq_settings]');
foreach ($query as $row)
{
	$setting[$row['key']] = unserialize($row['value']);
}

if ($id)
{
	$row = $_pdo->getRow('SELECT * FROM [faq_questions] WHERE `id` = :id',
		array(':id', $id, PDO::PARAM_INT)
	);

	if ($row)
	{
		if ($_user->isLoggedIn() || $row['status'] === '0')
		{
			$theme = array(
				'Title' => $setting['title'].' - '.$row['question'],
				'Keys' => 'faq, '.$row['question'],
				'Desc' => $setting['description'].' - '.$row['question']
			);
			
			$query = $_pdo->getData('SELECT `id` FROM [comments] WHERE `content_type` = "faq" AND `content_id` = :id',
				array(':id', $row['id'], PDO::PARAM_INT)
			);
			
			$faq = array(
				'id' => $row['id'],
				'question' => $row['question'],
				'answer' => parseBBCode($row['answer']),
				'comment' => $row['comment'],
				'comments' => $_pdo->getRowsCount($query),
				'url_faq' => $_route->path(array('controller' => 'faq')),
				'url_answer' => $_route->path(array('controller' => 'faq', 'action' => $row['id'], HELP::Title2Link($row['answer']))),
				'sticky' => $row['sticky']
			);
			
			if ($row['comment'] === '1')
			{
				$_tpl->assign('comments', $_comment->get($_route->getFileName(), $row['id']));
				$_tpl->assign('bbcode', bbcodes());

				if (isset($_POST['comment']['save']))
				{
					$comment = array_merge($comment, array(
						'action'  => 'add',
						'author'  => $_user->get('id'),
						'content' => $_POST['comment']['content']
					));
				}
			}
		}
		else
		{
			$_request->redirect(ADDR_SITE.'faq.html');
		}
	}

	$_tpl->assign('question', $faq);
}
else
{
	$theme = array(
		'Title' => $setting['title'],
		'Keys' => 'faq, modules',
		'Desc' => $setting['description']
	);

	if ($_user->isLoggedIn())
	{
		$query = $_pdo->getData('SELECT `id`, `question`, `answer`, `sticky` FROM [faq_questions] ORDER BY `id` '.$setting['sorting']);
	}
	else
	{
		$query = $_pdo->getData('SELECT `id`, `question`, `answer`, `sticky` FROM [faq_questions] WHERE `status` != 1 ORDER BY `id` '.$setting['sorting']);
	}

	$i = 0; $questions = array();
	foreach($query as $row)
	{
		$query = $_pdo->getData('SELECT `id` FROM [comments] WHERE `content_type` = "faq" AND `content_id` = :id',
			array(':id', $row['id'], PDO::PARAM_INT)
		);
		
		$questions[] = array(
			'row_color' => ($i % 2 == 0 ? 'tbl1' : 'tbl2'),
			'id' => $row['id'],
			'question' => ((strlen($row['question']) > 85) ? substr($row['question'],0,85).'...' : $row['question']),
			'answer' => ((strlen($row['answer']) > 300) ? substr(parseBBCode($row['answer']),0,300).'...' : parseBBCode($row['answer'])),
			'sticky' => $row['sticky'],
			'url_faq' => $_route->path(array('controller' => 'faq')),
			'url_answer' => $_route->path(array('controller' => 'faq', 'action' => $row['id'], HELP::Title2Link($row['answer']))),
			'comments' => $_pdo->getRowsCount($query),
			'nr' => $i+1
		);

		$i++;
	}

	$_tpl->assign('questions', $questions);
}

$_tpl->assignGroup(
	array(
		'Theme' => $theme,
		'setting' => $setting
	)
);

// Definiowanie katalogu templatek modułu
$_tpl->setPageCompileDir(DIR_MODULES.'faq'.DS.'templates'.DS);