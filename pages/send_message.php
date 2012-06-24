<?php 
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/

/*
defined('EF5_SYSTEM') || exit;

$_locale->load('messages');

if ( ! $_user->iUSER()) exit; // tu dac komunikat ze trzeba by zalogowanym

$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/messages.css" media="screen" rel="stylesheet" />');

$theme = array(
	'Title' => __('Messages'),
	'Keys' => '',
	'Desc' => ''
);

if ($_route->getByID(1) === 'send')
{
	$_tpl->printMessage('valid', __('Wiadomość została wysłana'));
}
else
{
	if ($_route->getByID(1) && isNum($_route->getByID(1)))
	{
		$_tpl->assignGroup(array(
			'user' => $_user->getByID($_route->getById(1)),
			'from' => $_route->getByID(1)
		));
	}
}

if ($_request->post('send')->show() && $_request->post('message')->show() && $_request->post('to')->show())
{
	if ($_route->getByID(2) && $_request->post('new', FALSE)->show() === FALSE)
	{
		$item_id = new Edit($_route->getByID(2));

		if ($item_id->isNum())
		{
			// Sprawdzanie, czy użytkownik rzeczywiście uczestniczy w wymianie wiadomości o podanym ID
			if ($_pdo->getSelectCount('SELECT Count(*) FROM [messages] WHERE `item_id` = '.$item_id->show().' AND (`to` = '.$_user->get('id').' OR `from` = '.$_user->get('id').')'))
			{
				$item_id = $item_id->show();
			}
			else
			{
				$item_id = $_pdo->getField('SELECT max(`item_id`) FROM [messages]') + 1;
			}
		}
	}
	else
	{
		$item_id = $_pdo->getField('SELECT max(`item_id`) FROM [messages]') + 1;
	}
	

	$_pdo->exec('INSERT INTO [messages] (`to`, `from`, `subject`, `message`, `datestamp`, `item_id`) VALUES (:to, :from, :subject, :message, :datestamp, '.$item_id.')',
		array(
			array(':to', $_request->post('to')->show(), PDO::PARAM_INT),
			array(':from', $_user->get('id'), PDO::PARAM_INT),
			array(':subject', $_request->post('subject')->strip(), PDO::PARAM_STR),
			array(':message', $_request->post('message')->strip(), PDO::PARAM_STR),
			array(':datestamp', time(), PDO::PARAM_INT)
		)
	);

	$_request->redirect(ADDR_SITE.'send_message,send.html');
}

*/