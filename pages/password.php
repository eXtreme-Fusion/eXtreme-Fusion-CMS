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
		'Title' => __('Odzyskaj swoje hasło').' » '.$_sett->get('site_name'),
		'Keys' => 'Odzyskiwanie hasła, zapomniałem hasła, hasło',
		'Desc' => 'Jeśli nie pamiętasz hasła do swojego proflu możesz wysłać nowe hasło na adres podany podczas rejestracji.'
	);

if ($_user->iGuest())
{
	// Inicjacja obiektu obsługi mailingu
	$_mail = new Mailer($_sett->get('smtp_username'), $_sett->get('smtp_password'), $_sett->get('smtp_host'), $_sett->get('smtp_port'));

	if ($_route->getAction() === 'message')
	{
		if ($_route->getParam('status') === 'ok')
		{
			$_tpl->printMessage('valid', __('Dziękujemy! Dalsze intrukcje zostały przesłane mailem.'));
		}
		elseif ($_route->getParam('status') === '2')
		{
			$_tpl->printMessage('error', __('Twoje konto wymaga akceptacji Administratora. Prosimy o kontakt.'));
		}
		elseif ($_route->getParam('status') === '3')
		{
			$_tpl->printMessage('error', __('Twoje konto jest obecnie zablokowane. Proszę o kontakt z Administratorem.'));
		}
		elseif ($_route->getParam('status') === 'ready')
		{
			$_tpl->printMessage('valid', __('Nowe hasło zostało wysłane drogą mailową.'));
		}
		elseif ($_route->getParam('status') === 'not-ready')
		{
			$_tpl->printMessage('error', __('Nieprawidłowy link potwierdzający własność konta mailowego.'));
		}
		elseif ($_route->getParam('status') === 'to-active')
		{
			$_tpl->printMessage('error', __('Nowe hasło zostało wysłane drogą mailową, ale wymagana jest aktywacja konta przez Administratora.'));
		}
		elseif ($_route->getParam('status') === 'banned')
		{
			$_tpl->printMessage('error', __('Nowe hasło zostało wysłane drogą mailową, ale twoje konto jest obecnie zablokowane. Proszę o kontakt z Administratorem.'));
		}
		elseif ($_route->getParam('status') === 'db-error')
		{
			$_tpl->printMessage('error', __('Nie udało się zapisać nowego hasła w bazie. Proszę o kontakt z Administratorem.'));
		}
		elseif ($_route->getParam('status') === 'time-error')
		{
			$_tpl->printMessage('error', __('Link generujący nowe hasło jest przestarzały. Należy rozpocząć od nowa procedurę Odzyskiwania dostępu do konta.'));
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

						// Do Tymcio - jak bedziesz robił tutaj locale, to zrób short_index - myślę że wiesz co mam na myśli ;)
						$status = $_mail->send($_request->post('email')->show(), $_sett->get('contact_email'), __('Generowanie nowego hasła'),
						'Poproszono o zmianę hasła w serwisie '.$_sett->get('site_name').' dostępnym pod adresem '.ADDR_SITE.'.'.PHP_EOL.'
						Jeżeli nie korzystałeś/aś z procedury odzyskiwania dostępu do konta, prosimy o zignorowanie tej wiadomości.'.PHP_EOL.PHP_EOL.'
							W celu wygenerowania nowego hasła, proszę przejść pod nastepujący adres:'.PHP_EOL.PHP_EOL.'
							<a href="'.$_route->path(array('controller' => 'password', 'action' => 'renew', 'user-'.$data['id'], $hash)).'">'.$_route->path(array('controller' => 'password', 'action' => 'renew', 'user-'.$data['id'], $hash)).'</a>'
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
					$_tpl->printMessage('error', __('Podany adres e-mail nie istnieje w bazie danych.'));
				}

			}
			else
			{
				$_tpl->printMessage('error', __('Podana w polu formularza wartość nie jest zgodna z formatem adresu e-mail.'));
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

					$new_status = '0';

					if ($data['status'] === '1' || $data['status'] === '2')
					{
						if ($_sett->get('admin_activation') || $data['status'] === '2')
						{
							if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'status' => 2, 'valid_code' => '')))
							{
								// Do Tymcio - jak bedziesz robił tutaj locale, to zrób short_index - myślę że wiesz co mam na myśli ;)
								$_mail->send($data['email'], $_sett->get('contact_email'), __('Nowe hasło'), '<p>Twoje nowe hasło: '.$password.'</p><p>Dziękujemy za skorzystanie z Systemu odzyskiwania konta.</p>');

								$_route->redirect(array('action' => 'message', 'status' => 'to-active'));
							}
						}
						else
						{
							if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'status' => 0, 'valid_code' => '')))
							{
								// Do Tymcio - jak bedziesz robił tutaj locale, to zrób short_index - myślę że wiesz co mam na myśli ;)
								$_mail->send($data['email'], $_sett->get('contact_email'), __('Nowe hasło'), '<p>Twoje nowe hasło: '.$password.'</p><p>Dziękujemy za skorzystanie z Systemu odzyskiwania konta.</p>');

								$_route->redirect(array('action' => 'message', 'status' => 'ready'));
							}
						}
					}
					elseif ($data['status'] === '3')
					{
						if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'valid_code' => '')))
						{
							// Do Tymcio - jak bedziesz robił tutaj locale, to zrób short_index - myślę że wiesz co mam na myśli ;)
							$_mail->send($data['email'], $_sett->get('contact_email'), __('Nowe hasło'), '<p>Twoje nowe hasło: '.$password.'</p><p>Dziękujemy za skorzystanie z Systemu odzyskiwania konta.</p>');

							$_route->redirect(array('action' => 'message', 'status' => 'banned'));
						}
					}
					else
					{
						if ($_user->update($_route->getParam('user'), array('password' => sha512($salt.'^'.$password), 'salt' => $salt, 'valid_code' => '')))
						{
							// Do Tymcio - jak bedziesz robił tutaj locale, to zrób short_index - myślę że wiesz co mam na myśli ;)
							$_mail->send($data['email'], $_sett->get('contact_email'), __('Nowe hasło'), '<p>Twoje nowe hasło: '.$password.'</p><p>Dziękujemy za skorzystanie z Systemu odzyskiwania konta.</p>');

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
			throw new systemException(__('Błąd! Nie udało się pobrać wszystkich danych wymaganych do zmiany hasła.'));
		}
	}
}
else
{
	$_route->redirect(array('controller' => 'profile', $_user->get('id'), $_user->get('username')));
}
