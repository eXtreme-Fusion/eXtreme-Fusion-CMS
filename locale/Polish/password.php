<?php defined('EF5_SYSTEM') || exit;

return array(
	'Password recovery' => 'Przywracanie hasła',
	
	//Logs
	'Thank you! Further instructions were sent by an e-mail.' => 'Dziękujemy! Dalsze instrukcje zostały wysłane w wiadomości e-mail.',
	'Your account requires the activation by the administrator. Please contact us.' => 'Twoje konto wymaga akceptacji administratora. Prosimy o kontakt.',
	'Your account is currently banned. Please contact the administrator.' => 'Twoje konto jest obecnie zablokowane. Proszę o kontakt z administratorem.',
	'News password has been sent by an e-mail.' => 'Nowe hasło zostało wysłane w wiadomości e-mail.',
	'Incorrect confirmation link.' => 'Nieprawidłowy link potwierdzający.',
	'New password has been sent by an e-mail, but there is also requirement of activation by the administrator.' => 'Nowe hasło zostało wysłane w wiadomości e-mail, lecz wymagana jest również aktywacja administratora.',
	'New password has been sent by an e-mail, but your account is currently banned. Please contact the administrator.' => 'Nowe hasło zostało wysłane drogą mailową, ale twoje konto jest obecnie zablokowane. Proszę o kontakt z administratorem.',
	'Error! New password has not been saved to the database. Please contact the administrator.' => 'Wystąpił błąd! Nie udało się zapisać nowego hasła w bazie. Proszę o kontakt z administratorem.',
	'Link that generates new password is outdated. Please start password recovery operation from the begining.' => 'Link generujący nowe hasło jest przestarzały. Należy rozpocząć od nowa procedurę odzyskiwania hasła.',
	'Error! This e-mail address exists in our database.' => 'Wystąpił błąd! Podany adres e-mail istnieje w naszej bazie danych.',
	'Error! The value given in form is not compatible with format of an e-mail address.' => 'Wystąpił błąd! Podana w polu formularza wartość nie jest zgodna z formatem adresu e-mail.',
	'Error! Failed to download all the data required to change your password.' => 'Wystąpił błąd! Nie udało się pobrać wszystkich danych wymaganych do zmiany hasła.',
	
	
	//E-mail messages
	'New password generating' => 'Generowanie hasła',
	'Password message' => 'Poproszono o zmianę hasła w serwisie :site_name dostępnym pod adresem :address. :eol
							Jeżeli nie korzystałeś/aś z procedury odzyskiwania dostępu do konta, prosimy o zignorowanie tej wiadomości.:eol
							W celu wygenerowania nowego hasła, proszę przejść pod nastepujący adres: :eol
							<a href=":link">:link</a>',
	'New password' => 'Nowe hasło',
	'Your new pass' => '<p>Twoje nowe hasło: :password</p><p>Dziękujemy za skorzystanie z systemu przywracania hasła.</p>',
							
	
);