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
	require_once '../../config.php';
	require DIR_SITE.'bootstrap.php';
	require_once DIR_SYSTEM.'admincore.php';
	
	$_locale->load('smileys');

    if ( ! $_user->hasPermission('admin.smileys'))
    {
        throw new userException(__('Access denied'));
    }

	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyœwietli komunikat
		$_tpl->getMessage($_request->get('status')->show(), $_request->get('act')->show(), 
			array(
				'add' => array(
					__('Smiley has been added.'), __('Error! Smiley has not been added.') 
				),
				'edit' => array(
					__('Smiley has been edited.'), __('Error! Smiley has not been edited.')
				),
				'delete' => array(
					__('Smiley has been deleted.'), __('Error! Smiley has not been deleted')
				)
		));
    }
	
    if ($_request->get('action')->show() === 'delete' && $_request->get('id')->isNum())
	{		
		$count = $_pdo->exec('DELETE FROM [smileys] WHERE `id` = :id',
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

		if ($count)
		{
			$_log->insertSuccess('delete', __('Smiley has been deleted.'));
			$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'ok'));
		}

		$_log->insertFail('delete',  __('Error! Smiley has not been deleted.'));
		$_request->redirect(FILE_PATH, array('act' => 'delete', 'status' => 'error'));
    }
    elseif ($_request->post('save')->show() && $_request->post('smiley_text')->show() && $_request->post('smiley_image')->show() && $_request->post('smiley_code')->show())
    {
        $smiley_code = str_replace(array("\"", "'", "\\", '\"', "\'", "<", ">"), "", $_request->post('smiley_code')->show());
        $smiley_image = $_request->post('smiley_image')->filters('trim', 'strip');
        $smiley_text = $_request->post('smiley_text')->filters('trim', 'strip');

		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$count = $_pdo->exec('UPDATE [smileys] SET `code` = :code, `image` = :image, `text` = :text WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					array(':code', $smiley_code, PDO::PARAM_STR),
					array(':image', $smiley_image, PDO::PARAM_STR),
					array(':text', $smiley_text, PDO::PARAM_STR)
				)
			);
	
			if ($count)
			{
				$_log->insertSuccess('edit', __('Smiley has been edited.'));
				$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'ok'));
			}
			
			$_log->insertFail('edit',  __('Error! Smiley has not been edited.'));
			$_request->redirect(FILE_PATH, array('act' => 'edit', 'status' => 'error'));
		}
		else
		{
			$count = $_pdo->exec('INSERT INTO [smileys] (code, image, text) VALUES (:code, :image, :text)',
				array(
					array(':code', $smiley_code, PDO::PARAM_STR),
					array(':image', $smiley_image, PDO::PARAM_STR),
					array(':text', $smiley_text, PDO::PARAM_STR)
				)
			);

			if ($count)
			{
				$_log->insertSuccess('add', __('Smiley has been added.'));
				$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'ok'));
			}
			
			$_log->insertFail('add',  __('Error! Smiley has not been added.'));
			$_request->redirect(FILE_PATH, array('act' => 'add', 'status' => 'error'));
        }
    }
    elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
    {
		$row = $_pdo->getRow('SELECT * FROM [smileys] WHERE `id` = :id', 
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

		if ($row)
		{
			$smiley_code = $row['code'];
			$smiley_image = $row['image'];
			$smiley_text = $row['text'];        
		}
    }
    else
    {
        $smiley_code = '';
        $smiley_image = '';
        $smiley_text = '';
    }
	
    $_tpl->assign('smiley_code', $smiley_code);
    $_tpl->assign('smiley_text', $smiley_text);
    $_tpl->assign('smiley_image', $_tpl->createSelectOpts($_files->createFileList(DIR_IMAGES.'smiley', array('.', '..', 'index.php', 'Thumbs.db', '.svn'), TRUE, 'files'), $smiley_code, FALSE));

	$query = $_pdo->getData('SELECT * FROM [smileys] ORDER by `id`');
    if ($query)
	{
        $i = 0; $data = array();
        foreach($query as $row)
        {
            $data[] = array(
                'row_color' => $i % 2 == 0 ? 'tbl1' : 'tbl2',
                'id' => $row['id'],
                'text' => $row['text'],
                'image' => $row['image'],
                'code' => $row['code']
            );
            $i++;
        }
		
        $_tpl->assign('smiley', $data);
    }

    $_tpl->template('smileys');
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