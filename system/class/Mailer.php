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
**********************************************************

.---------------------------------------------------------------------------.
|  Software: PHPMailer - PHP email class                                    |
|   Version: 5.2.1                                                          |
|      Site: https://code.google.com/a/apache-extras.org/p/phpmailer/       |
| ------------------------------------------------------------------------- |
|     Admin: Jim Jagielski (project admininistrator)                        |
|   Authors: Andy Prevost (codeworxtech) codeworxtech@users.sourceforge.net |
|          : Marcus Bointon (coolbru) coolbru@users.sourceforge.net         |
|          : Jim Jagielski (jimjag) jimjag@gmail.com                        |
|   Founder: Brent R. Matzelle (original founder)                           |
| Copyright (c) 2010-2012, Jim Jagielski. All Rights Reserved.              |
| Copyright (c) 2004-2009, Andy Prevost. All Rights Reserved.               |
| Copyright (c) 2001-2003, Brent R. Matzelle                                |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Lesser General Public License (LGPL)     |
|            http://www.gnu.org/copyleft/lesser.html                        |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'--------------------------------------------------------------------------*/
/*	Notatka dla rozwijających klasę:

	Aby wysłać ukryte kopie wiadomości (Bbc), trzeba skorzystać z funkcji mail() lub protokołu `sendmail`.
	Usuwają one z nagłówka wiadomości adresy wszystkich odbiorców, zostawiając tylko docelowy dla konkretnej osoby.

	Obecna klasa nie obsługuje `sendmail`, jedynie zachowuje się jakby wysyłała Bcc.
	Do użytku zewnetrznego są trzy metody:

	send() - wysyła pojedynczego maila do jednego odbiorcy
	sendCc() - wysyła maila do wielu odbiorców bez ukrycia ich adresów
	sendBcc() - wysyła przez wewnętrzną pętlę maile do wielu odbiorców ukrywając ich adresy

	Nie należy stosować send() w pętli,
	gdyż send() po każdym wykonaniu zrywa połączenie z serwerem, zaś sendBcc() utrzymuje je
	do wysłania wszystkich maili lub pojawienia się błędu.
*/
class Mailer
{
	protected
		$_to,
		$_reply_to,
		$_mail_from,
		$_from,
		$_subject,
		$_message,
		$_headers = array(),
		$_smtp, // Uchwyt
		$_data = array(),
		$_keep_alive = FALSE,
		$_to_is_array,			// Czy wiadomość ma być wysłana do wielu odbiorców? (bool)
		$_exception;

	private $_eol = "\r\n";

	public function __construct($username = NULL, $password = NULL, $host = NULL, $port = 587, $exception = TRUE)
	{
		// Czy któryś parametr jest NULL lub pusty?
		if (! in_array(NULL, array($username, $password, $host)))
		{
			$this->_data = array(
				'smtp_username' => $username,
				'smtp_password' => $password,
				'smtp_host' => $host,
				'smtp_port' => $port
			);
		}

		$this->_exception = $exception;
	}

	// Todo:: Walidacja
	protected function validate()
	{
		return TRUE;
	}

	protected function addHeaders($headers)
	{
		$this->_headers = array_merge($this->_headers, $headers);
	}

	public function send($to, $from, $subject, $message = NULL, array $headers = array(), $html = TRUE)
	{
		$this->_to = $to;

		/**
		 * Niektóre systemy pocztowe blokują odbiór wiadomości pochodzących z adresu e-mail
		 * nie należacego do domeny, z której wiadomość jest wysyłana.
		 * Dlatego jako nadawce wiadomości (MAIL FROM przy komunikacji z SMTP) zamieszcza się
		 * no-reply@domain.com, natomiast w nagłówku Reply-To przesyła się adres e-mail,
		 * na który mają być wysyłane odpowiedzi pisane przez adresata.
		 */
		$this->_reply_to = $this->_from = $from;
		$this->_mail_from = 'no-reply@'.$_SERVER['HTTP_HOST'];

		$this->_subject = $subject;
		$this->_message = $message;
		$this->_headers = $headers;

		// Czy nie wystąpiły błędy w zapisanych danych?
		if ($this->validate())
		{
			// Nagłówki
			$this->addHeaders(array(
				'To: '.$this->_to.$this->_eol,
				'From: '.$this->_from.$this->_eol,
				'Subject: '.$this->_subject.$this->_eol,
				'Reply-To: '.$this->_reply_to.$this->_eol,
				'X-Mailer: PHP eXtreme-Fusion 5'.$this->_eol,
				'Return-Path: '.$this->_from.$this->_eol
			));

			$this->_to_is_array = FALSE;

			if ($html)
			{
				$this->addHeaders(array(
					'MIME-Version: 1.0'.$this->_eol,
					'Content-type: text/html; charset=UTF-8'.$this->_eol
				));
			}

			// Wysyłanie wiadomości przez serwer SMTP
			if ($this->_data)
			{
				return $this->sendBySMTP();
			}

			// Wysyłanie wiadomości przez funkcję mail()
			// todo: co się dzieje bez @? Wg dokumentacji funkcja zwraca false albo true bez (bool) i bez @.
			return (bool) @mail($this->_to, $this->_subject, $this->_message, implode('', $this->_headers));
		}

		throw new userException('Nieprawidłowe dane do wysyłki maila.');
	}

	public function sendCc(array $to, $from, $subject, $message = NULL, array $headers = array(), $html = TRUE)
	{
		/** Zapis danych do zmiennych klasowych **/
		$this->_to = $to;
		$this->_from = $from;
		$this->_subject = $subject;
		$this->_message = $message;
		$this->_headers = $headers;

		// Czy nie wystąpiły błędy w zapisanych danych?
		if ($this->validate())
		{
			// Nagłówki
			$this->addHeaders(array(
				'From: '.$this->_from.$this->_eol,
				'Subject: '.$this->_subject.$this->_eol,
				'Reply-To: '.$this->_reply_to.$this->_eol,
				'X-Mailer: PHP eXtreme-Fusion 5'.$this->_eol,
				'Return-Path: '.$this->_from.$this->_eol
			));

			$this->_to_is_array = TRUE;
			$this->addHeaders(array('Cc: '.implode($this->_to, ', ').$this->_eol));

			if ($html)
			{
				$this->addHeaders(array(
					'MIME-Version: 1.0'.$this->_eol,
					'Content-type: text/html; charset=UTF-8'.$this->_eol
				));
			}

			// Wysyłanie wiadomości przez serwer SMTP
			if ($this->_data)
			{
				return $this->sendBySMTP();
			}

			// Wysyłanie wiadomości przez funkcję mail()
			return mail($this->_to, $this->_subject, $this->_message, implode('', $this->_headers));
		}

		throw new userException('Nieprawidłowe dane do wysyłki maila.');
	}

	public function sendBcc(array $to, $from, $subject, $message = NULL, array $headers = array(), $html = TRUE)
	{
		/** Zapis danych do zmiennych klasowych **/
		$this->_from = $from;
		$this->_subject = $subject;
		$this->_message = $message;
		$this->_headers = $headers;

		// Czy nie wystąpiły błędy w zapisanych danych?
		if ($this->validate())
		{
			$this->_to_is_array = FALSE;

			// Nagłówki
			$this->addHeaders(array(
				'From: '.$this->_from.$this->_eol,
				'Subject: '.$this->_subject.$this->_eol,
				'Reply-To: '.$this->_reply_to.$this->_eol,
				'X-Mailer: PHP eXtreme-Fusion 5'.$this->_eol,
				'Return-Path: '.$this->_from.$this->_eol
			));

			if ($html)
			{
				$this->addHeaders(array(
					'MIME-Version: 1.0'.$this->_eol,
					'Content-type: text/html; charset=UTF-8'.$this->_eol
				));
			}

			// Wysyłanie wiadomości przez serwer SMTP
			if ($this->_data)
			{
				// Połączenie z serwerem ma być utrzymywane aż do wysłania wszystkich maili lub wystąpienia błędu
				$this->_keep_alive = TRUE;

				// Wysyłanie maili wg odbiorców
				foreach($to as $rcpt)
				{
					$this->_to = $rcpt;
					$this->addHeaders(array('To: '.$rcpt.$this->_eol));
					if ( ! $this->sendBySMTP())
					{
						throw new systemException(__('System nie może nawiązać polączenia z serwerem :host :port lub nazwa użytkownika i hasło jest nie prawidłowe.', array(':host' => $this->_data['smtp_host'],':port' => $this->_data['smtp_port'],)));
					}

					// Usuwanie odbiorcy wysłanego maila z tablicy nagłówka
					array_pop($this->_headers);
				}
			}
			else
			{
				foreach($to as $rcpt)
				{
					// Wysyłanie wiadomości przez funkcję mail()
					if ( ! @mail($rcpt, $this->_subject, $this->_message, implode('', $this->_headers)))
					{
						throw new systemException(__('System nie ma skonfigurowanej opcji \'mail\', zmień ustawienia w php.ini lub użyj funkcji ini_set()'));
					}
				}
			}

			// Zamyka połączenie z serwerem
			$this->quit();

			return TRUE;
		}

		throw new userException('Nieprawidłowe dane do przesłania maila.');
	}

	// Wysyłanie wiadomości przez zewnętrzny serwer SMTP dla pojedynczego odbiorcy lub jawnej kopii (CC)
	protected function sendBySMTP()
	{
		if ($this->connect())
		{
			// Wysyłanie do serwera SMTP informacji od kogo jest mail
			$this->sendFrom($this->_mail_from);

			// Wysyłanie do serwera SMTP informacji do kogo jest mail
			if ($status = $this->sendRecipient($this->_to))
			{
				// Czy wysłano wiadomość?
				if ($this->sendMessage(implode('', $this->_headers), $this->_message))
				{
					// Czy połączenie ma zostać utrzymane?
					if ($this->_keep_alive)
					{
						$this->reset();
					}
					else
					{
						$this->close();
					}

					return TRUE;
				}
			}
		}

		return FALSE;
	}

	// Nawiązywanie połączenia z serwerem SMTP
	protected function connect()
	{
		// Jeśli połączenie nie jest nawiązane
		if (! $this->isConnected())
		{
			$this->_smtp = fsockopen($this->_data['smtp_host'], $this->_data['smtp_port'], $errno, $errstr, 45);

			// Jeśli wystąpiły błędy
			if (! $this->_smtp)
			{
				throw new systemException('Błąd! Nie można połączyć się z serwerem SMTP.');
			}

			// Odbieranie odpowiedzi z serwera
			$this->getReply();

			// Wysyłanie zapytania na przywitanie ;)
			if (! $this->sendHello('EHLO', $this->_data['smtp_host'])) // nowy typ komunikacji: extendedSMTP -> poprawne odpowiedzi serwera mają kod 250
			{
				if (! $this->sendHello('HELO', $this->_data['smtp_host'])) // starszy typ komunikacji jeśli nowy niedostępny; poprawne odpowiedzi serwera mają rózne kody
				{
					throw new systemException('Helo/Ehlo zakończone niepowodzeniem.');
				}
			}

			// Autoryzacja - zwróci TRUE, albo rzuci wyjątkiem
			return $this->auth();
		}

		return TRUE; // Połączenie nawiązane ;)
	}

	// Zrywa połączenie z SMTP
	protected function close($quit = TRUE)
	{
		if ($this->_smtp)
		{
			if ($quit)
			{
				$this->quit();
			}

			fclose($this->_smtp);
			$this->_smtp = NULL;
		}
	}

	// Sprawdzanie, czy serwer SMTP zwrócił prawidłowy kod odpowiedzi
	public function isValidResp($valid, $resp)
	{
		if (is_array($valid))
		{
			foreach($valid as $val)
			{
				if ($val === $resp)
				{
					return TRUE;
				}
			}
		}
		else
		{
			return $valid === $resp;
		}

		return FALSE;
	}

	// Zwraca kod odpowiedzi serwera SMTP
	public function getCode()
	{
		return intval(substr($this->getReply(), 0, 3));
	}

	// Pobiera odpowiedź od serwera SMTP.
	public function getReply()
	{
		$data = '';
		while($str = fgets($this->_smtp, 515))
		{
			$data .= trim($str);
			if (substr($str, 3, 1) === ' ')
			{
				break;
			}
		}

		return $data;
	}

	// Logowanie do SMTP
	public function auth()
	{
		fwrite($this->_smtp, 'AUTH LOGIN'.$this->_eol);
		if (!$this->isValidResp(334, $this->getCode()))
		{
			throw new systemException('Błąd: Serwer SMTP nie zaakceptował próby autoryzacji.');
		}

		fwrite($this->_smtp, base64_encode($this->_data['smtp_username']).$this->_eol);
		if (!$this->isValidResp(334, $this->getCode()))
		{
			throw new systemException('Błąd: Nazwa użytkownika nie została zaakceptowana przez serwer SMTP.');
		}

		fwrite($this->_smtp, base64_encode($this->_data['smtp_password']).$this->_eol);
		if (!$this->isValidResp(235, $this->getCode()))
		{
			throw new systemException('Błąd: Hasło nie zostało zaakceptowana przez serwer SMTP.');
		}

		return TRUE;
	}



	// Sprawdza stan połączenia z SMTP.
	public function isConnected()
	{
		if ($this->_smtp)
		{
			$status = stream_get_meta_data($this->_smtp);
			if ($status['eof'])
			{
				// End of file status.
				$this->close();
				return FALSE;
			}

			return TRUE;
		}

		return FALSE;
	}

	## KOMENDY ##

	public function reset()
	{
		fwrite($this->_smtp, 'RSET'.$this->_eol);
		if (!$this->isValidResp(250, $this->getCode()))
		{
			throw new systemException('Błąd: komenda RSET nie została zaakceptowana przez serwer SMTP.');
		}

		return TRUE;
	}

	public function sendFrom($from)
	{
		fwrite($this->_smtp, 'MAIL FROM: <'.$from.'>'.$this->_eol);
		if (!$this->isValidResp(250, $this->getCode()))
		{
			throw new systemException('Błąd: Nadawca wiadomości został odrzucony przez serwer SMTP.');
		}

		return TRUE;
	}

	public function sendRecipient($to)
	{
		if ($this->_to_is_array)
		{
			foreach ($to as $rcpt)
			{
				fwrite($this->_smtp, 'RCPT TO: <'.$rcpt.'>'.$this->_eol);
				if (!$this->isValidResp(array(250, 251), $this->getCode()))
				{
					if ($this->_exception)
					{
						throw new systemException('Błąd: Odbiorca niezaakceptowany przez serwer SMTP.');
					}

					return FALSE;
				}
			}
		}
		else
		{
			fwrite($this->_smtp, 'RCPT TO: <'.$to.'>'.$this->_eol);
			if (!$this->isValidResp(array(250, 251), $this->getCode()))
			{
				if ($this->_exception)
				{
					throw new systemException('Błąd: Odbiorca niezaakceptowany przez serwer SMTP.');
				}

				return FALSE;
			}
		}

		return TRUE;
	}

	public function sendMessage($headers, $message)
	{
		fwrite($this->_smtp, 'DATA'.$this->_eol);
		if (!$this->isValidResp(354, $this->getCode()))
		{
			throw new systemException('Błąd: Komenda DATA niezaakceptowana przez serwer SMTP.');
		}

		/** server ready for work ;) **/

		// Wysyłanie wiadomości
		fwrite($this->_smtp,$headers.$this->_eol.$this->_eol.$message.$this->_eol.'.' . $this->_eol);
		if (!$this->isValidResp(250, $this->getCode()))
		{
			throw new systemException('Błąd '.$this->getCode().': Komenda kończąca wymianę treści nie została zaakceptowana przez serwer SMTP.');
		}

		return TRUE;
	}

	/**
	 * Wysyłanie pierwszego żądania po nawiązaniu połączenia.
	 * Nie rzuca wyjątkiem, gdyż jest on wywoływany poza tą metodą
	 * dopiero po dwukrotnym zwróceniu FALSE.
	 */
	protected function sendHello($hello, $host)
	{
		fwrite($this->_smtp, $hello.' '.$host.$this->_eol);

		if (!$this->isValidResp(250, $this->getCode()))
		{
			return FALSE;
		}

		return TRUE;
	}

	// Zamyka połączenie z SMTP
	protected function quit()
	{
		fwrite($this->_smtp, 'QUIT'.$this->_eol);
		if (!$this->isValidResp(221, $this->getCode()))
		{
			throw new systemException('Błąd! Połączenie z serwerem nie może zostać zamknięte.');
		}

		return TRUE;
	}
}