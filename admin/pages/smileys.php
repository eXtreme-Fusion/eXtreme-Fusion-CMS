<?php
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
| 
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
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

	$_fav->setFavByLink('smileys.php', $_user->get('id'));

	$_tpl = new Iframe;

	if ($_request->get(array('status', 'act'))->show())
	{
		// Wyświetli komunikat
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
    elseif ($_request->post('save')->show() && $_request->post('text')->show() && $_request->post('image')->show() && $_request->post('code')->show())
    {
        $code = str_replace(array("\"", "'", "\\", '\"', "\'", "<", ">"), "", $_request->post('code')->show());
        $image = $_request->post('image')->filters('trim', 'strip');
        $text = $_request->post('text')->filters('trim', 'strip');

		if ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum())
		{
			$count = $_pdo->exec('UPDATE [smileys] SET `code` = :code, `image` = :image, `text` = :text WHERE `id` = :id',
				array(
					array(':id', $_request->get('id')->show(), PDO::PARAM_INT),
					array(':code', $code, PDO::PARAM_STR),
					array(':image', $image, PDO::PARAM_STR),
					array(':text', $text, PDO::PARAM_STR)
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
					array(':code', $code, PDO::PARAM_STR),
					array(':image', $image, PDO::PARAM_STR),
					array(':text', $text, PDO::PARAM_STR)
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
    elseif ($_request->get('action')->show() === 'edit' && $_request->get('id')->isNum() && $_request->get('id') !== '15')
    {
		$row = $_pdo->getRow('SELECT * FROM [smileys] WHERE `id` = :id', 
			array(':id', $_request->get('id')->show(), PDO::PARAM_INT)
		);

		if ($row)
		{
			$code = $row['code'];
			$image = $row['image'];
			$text = $row['text'];        
		}
    }
    else
    {
        $code = '';
        $image = '';
        $text = '';
    }
	
	//print_r($image);
	//print_r($_files->createFileList(DIR_IMAGES.'smiley', array('.', '..', 'index.php', 'Thumbs.db', '.svn', '.gitignore')));
	
	$_tpl->assignGroup(array(
		'code' => $code,
		'text' => $text,
		'image' => $_tpl->createSelectOpts($_files->createFileList(DIR_IMAGES.'smiley', array('.', '..', 'index.php', 'Thumbs.db', '.svn', '.gitignore'), TRUE, 'files'), $image)
	));
	
	$query = $_pdo->getData('SELECT * FROM [smileys] WHERE `id` != 15 ORDER by `id`');
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
