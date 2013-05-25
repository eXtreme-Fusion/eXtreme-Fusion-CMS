<?php defined('EF5_SYSTEM') || exit;
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
*********************************************************/
$_locale->moduleLoad('lang', 'contact');

$_mail = new Mailer($_sett->get('smtp_username'), $_sett->get('smtp_password'), $_sett->get('smtp_host'));

$_protection = NULL;

//TODO: Dorobić zarzadzanie typem zabezpieczenia
if ($validate_method = 'sfs_protection')
{
	$_security = new Security($_pdo, $_request);

	// Zwraca referencje obiektu klasy zabezpieczejącej
	if ($_protection = $_security->getCurrentModule($validate_method))
	{
		// Przekazywanie referencji do obiektów
		$_protection->setObjects(new Basic, $_pdo, $_sett, $_system);
	}
}

$id = $_route->getByID(1);
$title = $_route->getByID(2);
$action = $_route->getByID(3);

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

if ($id && $title)
{
	if ($id && $title && $action === 'send')
	{
		$_tpl->printMessage('valid', __('Wiadomość została wysłana.'));
	}
	elseif ($id && $title && $action === 'error2')
	{
		$_tpl->printMessage('error', __('Wystąpił błąd podczas wysyłania wiadomości. Prosimy o kontakt z Administratorem.'));
	}

	$row = $_pdo->getRow('SELECT * FROM [contact] WHERE `id` = :id',
		array(':id', $id, PDO::PARAM_INT)
	);
	
	if ($_request->post('send_message')->show())
	{
		$error = array();

		if ( ! $_request->post('send_mail')->isEmail())
		{
			throw new systemException('Adres e-mail odbiorcy wiadomości jest niepoprawny.');
		}

		if (! $_request->post('email')->show() || ! $_request->post('subject')->show() || ! $_request->post('message')->show())
		{
			$error[] = '1';
		}

		if ($_protection)
		{
			if ( ! $_protection->isValidAnswer($_security->getUserAnswer($_protection->getResponseInputs())))
			{
				$error['security'] = '2';
			}
		}

		if ( ! $_request->post('email')->isEmail())
		{
			$error[] = '3';
		}

		if ( ! $error)
		{
			if ($_mail->send($_request->post('send_mail')->show(), $_request->post('email')->show(), $_request->post('subject')->filters('trim', 'strip'), $_request->post('message')->show()))
			{
				// Czy wysłać kopię do nadawcy?
				if ($_request->post('sendme_copy')->show())
				{
					$message = 'Wiadomośc do: '.$row['title'].'<br /><br />'.$_request->post('message')->show();
					if ($_mail->send($_request->post('email')->show(), $_sett->get('contact_email'), $_request->post('subject')->filters('trim', 'strip'), $message))
					{
						$_tpl->printMessage('valid', 'Wysłano wiadomość.');
					}
					else
					{
						$error[] = '4';
					}
				}
				else
				{
					$_tpl->printMessage('valid', 'Wysłano wiadomość.');
				}
			}
			else
			{
				$error[] = '4';
			}
		}

		if ($error)
		{
			$_tpl->assignGroup(array(
				'error' => $error,
				'email' => $_request->post('email')->show(),
				'subject' => $_request->post('subject')->show(),
				'form_message' => $_request->post('message')->show(),
				'sendme_copy' => $_request->post('sendme_copy')->show() ? $_request->post('sendme_copy')->show() : NULL
			));
		}
	}
	elseif (iUSER)
	{
		$_tpl->assign('email', $_user->get('email'));
	}



	if ($row)
	{
		$theme = array(
			'Title' => __('Contact with :contact', array(':contact' => $row['title'])),
			'Keys' => 'contact, '.$row['title'].', form',
			'Desc' => __('Contact with :contact', array(':contact' => $row['title']))
		);
	}

	$_tpl->assign('contact', $row);
	
	if ($_protection)
	{
		$_tpl->assign('security', isset($error['security']) ? $_protection->getView_wrongAnswer() : $_protection->getView());
	}
}
else
{
	$theme = array(
		'Title' => __('Contact'),
		'Keys' => 'contact, form',
		'Desc' => __('Contact')
	);

	$query = $_pdo->getData('SELECT `id`, `title` FROM [contact] ORDER BY `id` DESC');

	$i = 0; $contacts = array();
	foreach($query as $row)
	{
		$contacts[] = array(
			'id' => $row['id'],
			'title' => $row['title'],
			'link' => $_route->path(array('controller' => 'contact', 'action' => $row['id'], HELP::Title2Link($row['title'])))
		);
		
		if ($_pdo->getRowsCount($query) === 1)
		{
			HELP::redirect($_route->path(array('controller' => 'contact', 'action' => $row['id'], HELP::Title2Link($row['title']))));
		}
		
		$i++;
	}

	$_tpl->assign('contacts', $contacts);
}

$_tpl->assign('Theme', $theme);

// Definiowanie katalogu templatek modułu
$_tpl->setPageCompileDir(DIR_MODULES.'contact'.DS.'templates'.DS);