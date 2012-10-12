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
	$_locale->moduleLoad('admin', 'faq');

    if ( ! $_user->hasPermission('module.faq.admin'))
    {
        throw new userException(__('Access denied'));
    }

    $_tpl = new AdminModuleIframe('faq');
	
	include DIR_MODULES.'faq'.DS.'config.php';
	
	$_tpl->assign('config', $mod_info);
	
	// Wyświetlenie komunikatów
	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'save' => array(
					__('Settings have been saved.'), __('Error! Settings have not been saved.')
				),
				'add' => array(
					__('Question has been added.'), __('Error! Question has not been added.')
				),
				'edit' => array(
					__('Question has been edited.'), __('Error! Question has not been edited.')
				),
				'delete' => array(
					__('Question has been deleted.'), __('Error! Question has not been deleted.')
				),
				'status' => array(
					__('Question status has been changed.'), __('Error! Question status has not been changed.')
				),
				'sticky' => array(
					__('Importance of question has been changed.'), __('Error! Importance of question has not been changed.')
				),
				'comments' => array(
					__('Privileges to comment have been changed.'), __('Error! Privileges to comment have not been changed.')
				),
			)
		);
	}
	
	if ($_request->get('page')->show() === 'manage') 
	{
		/*-------------------
		| PL: Zarządzanie pytaniami i odpowiedziami
		| EN: Manage with questions and answers
		+------------------*/
		if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
		{
			$count = $_pdo->exec('DELETE FROM [faq_questions] WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);
		
			if ($count)
			{
				$_log->insertSuccess('delete', __('Question has been deleted.'));
				$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'delete', 'status' => 'ok'));
			}

			$_log->insertFail('delete', __('Error! Question has not been deleted.'));
			$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'delete', 'status' => 'error'));
		}
		elseif ($_request->get('action')->show() == 'status' && $_request->get('id')->isNum() && $_request->get('val')->isNum())
		{
			$count = $_pdo->exec('UPDATE [faq_questions] SET `status` = :val WHERE `id` = :id',
				array(
					array(':val', $_request->get('val')->show(), PDO::PARAM_INT),
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);
			
			if ($count)
			{
				$_log->insertSuccess('edit', __('Question status has been changed.'));
				$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'status', 'status' => 'ok'));
			}
			$_log->insertFail('edit', __('Error! Question status has not been changed.'));
			$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'status', 'status' => 'error'));
		}
		elseif ($_request->get('action')->show() == 'sticky' && $_request->get('id')->isNum() && $_request->get('val')->isNum())
		{
			$count = $_pdo->exec('UPDATE [faq_questions] SET `sticky` = :val WHERE `id` = :id',
				array(
					array(':val', $_request->get('val')->show(), PDO::PARAM_INT),
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);
			
			if ($count)
			{
				$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'sticky', 'status' => 'ok'));
			}
			$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'sticky', 'status' => 'error'));
		}
		elseif ($_request->get('action')->show() == 'comments' && $_request->get('id')->isNum() && $_request->get('val')->isNum())
		{
			$count = $_pdo->exec('UPDATE [faq_questions] SET `comment` = :val WHERE `id` = :id',
				array(
					array(':val', $_request->get('val')->show(), PDO::PARAM_INT),
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
				)
			);
			
			if ($count)
			{
				$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'comments', 'status' => 'ok'));
			}
			$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'comments', 'status' => 'error'));
		}
		else 
		{
		
			$query = $_pdo->getData('SELECT * FROM [faq_questions]');
			
			$i = 0; $no = 1; $short; $data = array();
			if ($_pdo->getRowsCount($query))
			{
				foreach ($query as $row)
				{
					$question = $row['question'];
					if (strlen($row['question']) > 85) $row['question'] = substr($row['question'],0,85).'...';
					
					$data[] = array(
						'row_color' => ($i % 2 == 0 ? 'tbl1' : 'tbl2'),
						'id' => $row['id'],
						'question_long' => $question,
						'question_short' => $row['question'],
						'sticky' => $row['sticky'],
						'comments' => $row['comment'],
						'status' => $row['status'],
						'no' => $no
					);
					$i++;
					$no++;
					$short = '';
				}
				$_tpl->assign('data', $data);
			}
		}
		$_tpl->assign('manage', TRUE);
	}
	elseif ($_request->get('page')->show() === 'add') 
	{
		/*-------------------
		| PL: Dodawanie pytania
		| EN: Adding question
		+------------------*/
		
		if ($_request->post('save')->show() && $_request->post('question')->show() && $_request->post('answer')->show()) 
		{
			$question = $_request->post('question')->filters('trim', 'strip');
			$answer = nl2br($_request->post('answer')->filters('trim', 'strip'));
			
			if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
			{
				$count = $_pdo->exec('UPDATE [faq_questions] SET `question` = :question, `answer` = :answer WHERE `id` = :id',
					array(
						array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
						array(':question', $question, PDO::PARAM_STR),
						array(':answer', $answer, PDO::PARAM_STR)
					)
				);

				if ($count)
				{
					$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'edit', 'status' => 'ok'));
				}
			
				$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'edit', 'status' => 'ok'));
			}
			else
			{
				$count = $_pdo->exec('INSERT INTO [faq_questions] (`question`, `answer`) VALUES (:question, :answer)',
					array(
						array(':question', $question, PDO::PARAM_STR),
						array(':answer', $answer, PDO::PARAM_STR)
					)
				);
					
				if ($count)
				{
					$_log->insertSuccess('add', __('Question has been added.'));
					$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'add', 'status' => 'ok'));
				}

				$_log->insertFail('add', __('Error! Question has not been added.'));
				$_request->redirect(FILE_PATH, array('page' => 'manage', 'act' => 'add', 'status' => 'error'));
			}
			
			$_request->redirect(FILE_PATH);
		}
		elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$data = $_pdo->getRow('SELECT `question`, `answer` FROM [faq_questions] WHERE `id` = :id',
				array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
			);
			
			if($data)
			{
				$faq = array(
					'question' => $data['question'],
					'answer' => $data['answer']
				);
			}
			else
			{
				throw new userException(__('Error! There is no ID :id for the table faq_questions.', array(':id' => $_request->get('id')->isNum())));
			}
			$_tpl->assign('faq', $faq);
		}
		$_tpl->assign('add', TRUE);
		$_tpl->assign('bbcode', bbcodes('answer'));
	} 
	else 
	{	
		/*-------------------
		| PL: Ustawienia modułu FAQ.
		| EN: FAQ module settings's.
		+------------------*/
	
		if ($_request->post('save')->show()) 
		{	
			$array = array(
				'title' => $_request->post('title')->filters('trim', 'strip'),
				'description' => $_request->post('description')->filters('trim', 'strip'),
				'display' => $_request->post('display')->isNum(TRUE),
				'listing' => $_request->post('listing')->isNum(TRUE),
				'links' => $_request->post('links')->isNum(TRUE),
				'back' => $_request->post('back')->filters('trim', 'strip'),
				'sorting' => $_request->post('sorting')->filters('trim', 'strip')
			);
			
			foreach ($array as $key => $value)
			{
				$count = $_pdo->exec('UPDATE [faq_settings] SET `value` = :value WHERE `key` = :key',
					array(
						array(':value', serialize($value), PDO::PARAM_STR),
						array(':key', $key, PDO::PARAM_STR)
					)
				);
			}
			
			if ($count)
			{
				$_log->insertSuccess('edit', __('Settings have been changed.'));
				$_request->redirect(FILE_PATH, array('act' => 'save', 'status' => 'ok'));
			}
			$_request->redirect(FILE_PATH, array('act' => 'save', 'status' => 'error'));
		}
		
		$query = $_pdo->getData('SELECT * FROM [faq_settings]');
		
		foreach ($query as $setting)
		{
			$$setting['key'] = unserialize($setting['value']);
		}
		
		$_tpl->assignGroup(
			array(
				'title' => $title,
				'description' => $description,
				'display' => $display,
				'listing' => $listing,
				'back' => $back,
				'sorting' => $sorting
			)
		);
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