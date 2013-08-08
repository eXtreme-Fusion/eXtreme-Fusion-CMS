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
$_locale->load('password');
$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/password.css" media="screen" rel="stylesheet">');

$theme = array(
	'Title' => __('Recover your password » :sitename', array(':sitename' => $_sett->get('site_name'))),
	'Keys' => __('Recovering passwords, forgotten passwords, password'),
	'Desc' => __('If you forget the password to your profile, you can send a new password to the address provided when registering')
);

if ($_user->iGuest())
{
	// Inicjacja obiektu obsługi mailingu
	$_mail = new Mailer($_sett->get('smtp_username'), $_sett->get('smtp_password'), $_sett->get('smtp_host'), $_sett->get('smtp_port'));

	if ($_route->getAction() === 'message')
	{
		if ($_route->getParam('status') === 'ok')
		{
			$_tpl->printMessage('valid', __('Thank you! Further instructions were sent by an e-mail.'));
		}
		elseif ($_route->getParam('status') === '2')
		{
			$_tpl->printMessage('error', __('Your account requires the activation by the administrator. Please contact us.'));
		}
		elseif ($_route->getParam('status') === '3')
		{
			$_tpl->printMessage('error', __('Your account is currently banned. Please contact the administrator.'));
		}
		elseif ($_route->getParam('status') === 'ready')
		{
			$_tpl->printMessage('valid', __('News password has been sent by an e-mail.'));
		}
		elseif ($_route->getParam('status') === 'not-ready')
		{
			$_tpl->printMessage('error', __('Incorrect confirmation link.'));
		}
		elseif ($_route->getParam('status') === 'to-active')
		{
			$_tpl->printMessage('error', __('New password has been sent by an e-mail, but there is also requirement of activation by the administrator.'));
		}
		elseif ($_route->getParam('status') === 'banned')
		{
			$_tpl->printMessage('error', __('New password has been sent by an e-mail, but your account is currently banned. Please contact the administrator.'));
		}
		elseif ($_route->getParam('status') === 'db-error')
		{
			$_tpl->printMessage('error', __('Error! New password has not been saved to the database. Please contact the administrator.'));
		}
		elseif ($_route->getParam('status') === 'time-error')
		{
			$_tpl->printMessage('error', __('Link that generates new password is outdated. Please start password recovery operation from the begining.'));
		}
	}

	// Domyślna akcja
	if ($_route->getAction(NULL) === NULL)
	{
		// Wysłanie żądania POST
		if ($_request->post('check')->show())
		{
			// Sprawdzanie poprawności formatu adresu e-mail
			if ($_request->post('email')->isEmail())
			{
				if ($_user->emailExists($_request->post('email')->show()))
				{
					// Pozyskiwanie statusu i identyfikatora użytkownika po adresie e-mail
					$data = $_user->getByEmail($_request->post('email')->show(), array('status', 'id'));

					// Konto aktywne lub nieaktywne (w przypadku nieaktywnego nastąpi aktywacja mailowa)
					if ($data['status'] <= 1)
					{
						$hash = time().'_'.substr(uniqid(md5(time())), 1, 10);
						$_user->update($data['id'], array('valid_code' => $hash));

						$status = $_mail->send(
								$_request->post('email')->show(), 
								$_sett->get('contact_email'), 
								__('Generate new password » :sitename', array(':sitename' => $_sett->get('site_name'))),
								__('Asked to change your password on website: :sitename avaible at address: <a href=":addressite">:addressite</a>.:eol If you did not use password recovery option, please ignore this message.:eol To generate new password, please go to the following website address: :eol <a href=":addrgen">:addrgen</a>', 
									array(
										':sitename' => $_sett->get('site_name'),
										':eol' => PHP_EOL,
										':addressite' => ADDR_SITE,
										':addrgen' => $_route->path(array('controller' => 'password', 'action' => 'renew', 'user-'.$data['id'], $hash))
									)
								)
						);
						if ($status)
						{
							$_route->redirect(array('action' => 'message', 'status' => 'ok'));
						}
						else
						{
							$_tpl->printMessage('error', __('E-mail has not been sent. Please contact an administrator and report the problem to him'));
						}
					}
					// Konto wymaga akceptacji Administratora
					elseif ($data['status'] === '2')
					{
						$_route->redirect(array('action' => 'message', 'status' => '2'));
					}
					// Konto jest zablokowane
					elseif ($data['status'] === '3')
					{
						$_route->redirect(array('action' => 'message', 'status' => '3'));
					}
				}
				else
				{
					$_tpl->printMessage('error', __('Error! This e-mail address exists in our database.'));
				}

			}
			else
			{
				$_tpl->printMessage('error', __('Error! The value given in form is not compatible with format of an e-mail address.'));
			}
		}
	}
	// Kliknięcie w link z maila
	elseif ($_route->getAction() === 'renew')
	{
		$data = $_user->getByID(isNum($_route->getParam('user')), array('valid_code', 'email', 'status'));

		// Sprawdzanie, czy pobrano wszystkie wymagane dane
		if (count($data) === 3)
		{
			// Sprawdzanie czy kod bezpieczeństwa jest prawidłowy
			if ($_route->getParamVoid(1) != '' && $_route->getParamVoid(1) === $data['valid_code'])
			{
				$time = explode('_', $data['valid_code']);

				// Sprawdzanie, czy odzyskanie hasła jest jeszcze możliwe z podanego adresu URL
				if ((time()-(60*60*48)) < $time[0])
				{
					$salt = substr(sha512(uniqid(rand(), true)), 0, 5);
					$password = substr(md5(uniqid(time())), 2, 15);

					if ($data['status'] === '1' || $data['status'] === '2')
					{
						if ($_sett->get('admin_activation') || $data['status'] === '2')
						{
							if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'status' => 2, 'valid_code' => '')))
							{
								$_mail->send(
									$data['email'], 
									$_sett->get('contact_email'),
									__('New Password » :sitename', array(':sitename' => $_sett->get('site_name'))),
									__('Your new password: :pass. Thank you for using password recovery system', array(':pass' => $password))
								);

								$_route->redirect(array('action' => 'message', 'status' => 'to-active'));
							}
						}
						else
						{
							if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'status' => 0, 'valid_code' => '')))
							{
								$_mail->send(
									$data['email'], 
									$_sett->get('contact_email'),
									__('New Password » :sitename', array(':sitename' => $_sett->get('site_name'))),
									__('Your new password: :pass. Thank you for using password recovery system', array(':pass' => $password))
								);
								
								$_route->redirect(array('action' => 'message', 'status' => 'ready'));
							}
						}
					}
					elseif ($data['status'] === '3')
					{
						if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'valid_code' => '')))
						{
							$_mail->send(
								$data['email'], 
								$_sett->get('contact_email'),
								__('New Password » :sitename', array(':sitename' => $_sett->get('site_name'))),
								__('Your new password: :pass. Thank you for using password recovery system', array(':pass' => $password))
							);
							
							$_route->redirect(array('action' => 'message', 'status' => 'banned'));
						}
					}
					else
					{
						if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'valid_code' => '')))
						{
							$_mail->send(
								$data['email'], 
								$_sett->get('contact_email'),
								__('New Password » :sitename', array(':sitename' => $_sett->get('site_name'))),
								__('Your new password: :pass. Thank you for using password recovery system', array(':pass' => $password))
							);
							
							$_route->redirect(array('action' => 'message', 'status' => 'ready'));
						}
					}

					$_route->redirect(array('action' => 'message', 'status' => 'db-error'));
				}
				else
				{
					$_route->redirect(array('action' => 'message', 'status' => 'time-error'));
				}
			}
			else
			{
				$_route->redirect(array('action' => 'message', 'status' => 'not-ready'));
			}
		}
		else
		{
			throw new systemException(__('Error! Failed to download all the data required to change your password.'));
		}
	}
}
else
{
	$_route->redirect(array('controller' => 'profile', $_user->get('id'), $_user->get('username')));
}
