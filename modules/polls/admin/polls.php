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
try
{
	require_once '../../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	$_locale->moduleLoad('admin', 'polls');

    if ( ! $_user->hasPermission('module.polls.admin'))
    {
        throw new userException(__('Access denied'));
    }

	$_tpl = new AdminModuleIframe('polls');

	if ($_request->get('status')->show() && $_request->get('act')->show())
    {
		$_tpl->logAndShow($_request->get('status')->show(), $_request->get('act')->show(), array(
			'add' => array(__('Poll has been added.'), __('Error! Poll has not been added.')),
			'edit' => array(__('Poll has been edited.'), __('Error! Poll has not been edited.')),
			'end' => array(__('Poll has been ended.'), __('Error! Poll has not been ended.')),
		));
    }
	
    if ($_request->get('action')->show() === 'end' && isNum($_request->get('poll_id')->show()))
    {
		$count = $_pdo->exec('UPDATE [polls] SET `date_end` = '.time().' WHERE `id` = :id',
			array(
				array(':id', $_request->get('poll_id')->show(), PDO::PARAM_INT)
			)
		);

        if ($count)
		{
			HELP::redirect(FILE_SELF.'?act=end&status=ok');
		}
		
		HELP::redirect(FILE_SELF.'?act=end&status=error');
    }
    elseif ($_request->post('save')->show())
    {
		$question = HELP::strip(trim($_request->post('Question')->show()));
		$response = HELP::strip(trim($_request->post('Response')->show()));
		$response = explode("\n", $response);
        if ($question && $response)
        {
			$new = array();
			foreach($response as $d)
			{
				$d = trim($d);
				
				if ($d)
				{
					$new[] = $d;
				}
			}
			$response = serialize($new);
            if ($_request->get('action')->show() === 'edit' && isNum($_request->get('poll_id')->show()))
            {
               $count = $_pdo->exec('UPDATE [polls] SET `question` = :question, `response` = :response WHERE `id` = :id',
					array(
						array(':id', $_request->get('poll_id')->show(), PDO::PARAM_INT),
						array(':question', $question, PDO::PARAM_STR),
						array(':response', $response, PDO::PARAM_STR)
					)
				);

				if ($count)
				{
					HELP::redirect(FILE_SELF.'?act=edit&status=ok');
				}
				
				HELP::redirect(FILE_SELF.'?act=edit&status=error');
            }
            else
            {
				$count = $_pdo->exec('INSERT INTO [polls] (`question`, `response`, `date_start`) VALUES (:question, :response, '.time().')',
					array(
						array(':question', $question, PDO::PARAM_STR),
						array(':response', $response, PDO::PARAM_STR)
					)
				);

				if ($count)
				{
					HELP::redirect(FILE_SELF.'?act=add&status=ok');
				}
				
				HELP::redirect(FILE_SELF.'?act=add&status=error');
            }
        }
        else
        {
			throw new userException(__('Error! Incorrect data have been entered.'));
        }
    }
    elseif ($_request->get('action')->show() === 'edit' && isNum($_request->get('poll_id')->show()))
    {
		$data = $_pdo->getRow('SELECT `question`, `response`, `date_start` FROM [polls] WHERE `id` = :id',
			array(
				array(':id', $_request->get('poll_id')->show(), PDO::PARAM_INT)
			)
		);
	
        if ($data) 
		{
            $question = $data['question'];
			$response = implode("\n", unserialize($data['response']));;
            $form_action = URL_REQUEST;
        }
    }
    else
    {
        $question = '';
		$response = '';
        $FormAction = FILE_SELF;
    }
	
    $_tpl->assign('Question', $question);
	$_tpl->assign('Response', $response);

	$query = $_pdo->getData('
		SELECT `id`, `question`, `date_start`
		FROM [polls]
		WHERE `date_end` = 0
		ORDER BY `date_start`
	');

	if ($_pdo->getRowsCount($query))
	{
		$i = 0; $data = array();
		foreach($query as $row)
		{
			$data[] = array(
				'RowColor' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
				'ID' => $row['id'],
				'Question' => $row['question'],
				'DateStart' => HELP::showDate('shortdate', $row['date_start'])
			);
			$i++;
		}
		$_tpl->assign('Data', $data);
	}
	
	$query = $_pdo->getData('
		SELECT `id`, `question`, `date_start`, `date_end`
		FROM [polls]
		WHERE `date_end` != 0
		ORDER BY `date_start`
	');

	if ($_pdo->getRowsCount($query))
	{
		$i = 0; $data = array();
		foreach($query as $row)
		{
			$data[] = array(
				'RowColor' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
				'ID' => $row['id'],
				'Question' => $row['question'],
				'DateStart' => HELP::showDate('shortdate', $row['date_start']),
				'DateEnd' => HELP::showDate('shortdate', $row['date_end'])
			);
			$i++;
		}
		$_tpl->assign('End', $data);
	}

    $_tpl->template('admin.tpl');
}
catch(optException $exception)
{
    optErrorHandler($exception);
}
catch(systemException $exception)
{
    systemErrorHandler($exception);
}
catch(userException $exception)
{
    userErrorHandler($exception);
}
catch(PDOException $exception)
{
    PDOErrorHandler($exception);
}