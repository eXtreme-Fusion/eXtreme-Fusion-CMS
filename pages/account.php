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
if ( ! iUSER) HELP::redirect(ADDR_SITE);

if ($_route->getAction() === 'logout')
{
	$_user->userLogout();
	HELP::redirect(ADDR_SITE);
}

$_head->set('<script src="'.ADDR_JS.'account.js"></script>');
$_locale->load('account');

$status = $_route->getByID(1);
$error  = $_route->getByID(2);

$theme = array(
	'Title' => 'Ustawienia konta użytkownika - '.$_user->get('username').' » '.$_sett->get('site_name'),
	'Keys'  => 'Ustawienia profilu, ustawienia konta, edycja konta, profil',
	'Desc'  => 'W tym miescju możesz dokonać aktualizacji swoich danych osobowych.',
);

$_sbb = $ec->getService('Sbb');

if (isset($status) && $status == 'ok')
{
	$_tpl->printMessage('valid', __('Konto edytowane prawidłowo'));
}
elseif (isset($status) && $status == 'error')
{
	if (isset($status) && $status == 'error' && isset($error) && $error == 1)
	{
		$_tpl->printMessage('error', __('Pola z nazwą użytkownika i emailem nie mogą być puste.'));
	}
	elseif (isset($status) && $status == 'error' && isset($error) && $error == 2)
	{
		$_tpl->printMessage('error', __('Nazwa użytkownika zawiera niedozwolone znaki.'));
	}
	elseif (isset($status) && $status == 'error' && isset($error) && $error == 3)
	{
		$_tpl->printMessage('error', __('Podano nieprawidłowe aktualne hasło.'));
	}
	elseif (isset($status) && $status == 'error' && isset($error) && $error == 4)
	{
		$_tpl->printMessage('error', __('Hasła użytkownika nie pasują do siebie.'));
	}
	elseif (isset($status) && $status == 'error' && isset($error) && $error == 5)
	{
		$_tpl->printMessage('error', __('Wystąpił błąd przy próbie zmiany hasła. Prosimy o kontakt z Administracją.'));
	}
	elseif (isset($status) && $status == 'error' && isset($error) && $error == 6)
	{
		$_tpl->printMessage('error', __('Adres email zawiera niedozwolone znaki.'));
	}
	elseif (isset($status) && $status == 'error' && isset($error) && $error == 7 && $_route->getByID(3))
	{
		$_tpl->printMessage('error', $_user->getAvatarErrorByCode($_route->getByID(3)));
	}
	else
	{
		$_tpl->printMessage('error', __('Błąd podczas edycji konta'));
	}
}

// Zapis danych
if ($_request->postIsset('email'))
{
	if ($_user->checkOldPass($_user->get('id'), $_request->post('old_password')->show()))
	{
		if ($_sett->get('change_name') == 1)
		{
			$username = trim(preg_replace("/ +/i", " ", $_request->post('username')->show()));
		}
		else
		{
			$username = $_user->get('username');
		}

		if ($username === '' || trim($_request->post('email')->show()) === '')
		{
			HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '1')));
		}

		if ( ! preg_match("/^[-0-9A-Z_@\s]+$/i", $username))
		{
			HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '2')));
		}

		if ( ! $_request->post('email')->isEmail())
		{
			HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '6')));
		}

		// Aktualizacja nazwy użytkownika
		if ($_sett->get('change_name') == 1)
		{
			if ($username != $_user->get('username'))
			{
				$_user->newName($username, $_user->get('id'));
			}
		}

		// Aktualizacja adresu e-mail
		if ($_request->post('email') != $_user->get('email'))
		{
			$_user->newEmail($_request->post('email')->show(), $_user->get('id'));
		}

		// Aktualizuje hasło użytkownika
		if ($_request->post('old_password')->show() && $_request->post('password1')->show())
		{
			if ($_request->post('password1')->show() !== $_request->post('password2')->show())
			{
				HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '4')));
			}

			if ( ! $_user->changePass($_user->get('id'), $_request->post('password1')->show()))
			{
				HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '5')));
			}
		}

		// Aktualizacja avatara
		if ($_request->upload('avatar'))
		{
			if ( ! $_user->saveNewAvatar($_user->get('id'), $_request->files('avatar')->show()))
			{
				HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '7', $_user->getAvatarErrorCode())));
			}
		}

		$fields  = $_pdo->getData('SELECT * FROM [user_fields] WHERE `edit` = 0');

		$_fields = array();
		if ($fields)
		{
			$data = $_request->post('data')->show();
			foreach($fields as $field)
			{
				if (isset($data[$field['index']]))
				{
					$value = new Edit($data[$field['index']]);
					$value = HELP::wordsProtect($value->filters('trim', 'strip'));

					$_fields[$field['index']] = $value;
				}
			}
		}

		$_user->update(NULL, array(
			'hide_email' => $_request->post('hideemail')->isNum(TRUE),
			'theme'      => $_request->post('theme')->show(),
			'lang'       => $_request->post('language')->show(),
		), $_fields);

		$_system->clearCache('profiles');
		HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'ok')));
	}
	else
	{
		HELP::redirect($_route->path(array('controller' => 'account', 'action' => 'error', '3')));
	}
}

$user = array(
	'ID'        => $_user->get('id'),
	'username'  => $_user->get('username'),
	'email'     => $_user->get('email'),
	'hide_email' => $_user->get('hide_email'),
	'theme'     => $_user->get('theme'),
	'avatar'    => $_user->get('avatar') ? $_user->getAvatarAddr() : FALSE,
);

$_tpl->assignGroup(array(
	'theme_set'  => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'themes', array('templates'), TRUE, 'folders'), $_user->get('theme')),
	// Zaznaczony zostanie język w zależności od preferencji użytkownika, dostępności danego języka w systemie oraz ustawień.
	'locale_set' => $_tpl->createSelectOpts($_files->createFileList(DIR_SITE.'locale', array(), TRUE, 'folders'), $_user->getLang()),
	'user'       => $user,
	'change_name' => $_sett->get('change_name'),
	'avatar_filesize' => $_sett->get('avatar_filesize')/1024,
	'avatar_height' => $_sett->get('avatar_height'),
	'avatar_width' => $_sett->get('avatar_width')
));

// Pobieranie dodatkowych pól
$data = $_user->getCustomData($_user->get('id'), array(), 0);

$_tpl->assignGroup(array(
	'bbcodes' => $_sbb->bbcodes(),
	'smileys' => $_sbb->smileys()
));

$_tpl->assign('fields', $data['fields']);
$_tpl->assign('cats', $data['categories']);