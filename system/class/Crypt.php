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
*********************************************************/

/**
 * Przykład działania:
 *
	// Drugi parametr koniecznie 8 znaków.
	$c = new Crypt('klucz szyfrujący', '=thn<.?!');

	echo $e = $c->encrypt('test');
	echo $c->decrypt($e);
 *
 */
class Crypt
{
	protected $_key;
	protected $_cipher;
	protected $_mode;
	protected $_source;
	protected $_iv;

	/**
	 * Przy pominięciu drugiego parametru, kodowanie udbywa się w jedną stronę.
	 * Wektor inicjujący `iv` jest zmienny z przeładowaniem strony.

	 * Jeśli dane mają być rozkodowane w przyszłości, należy posiadać stały wektor inicjujący i podać go drugim parametrem.
	 * Niezmienny musi być też `key` - klucz szyfrujący.
	 */
	public function __construct($key, $iv = NULL, $cipher = MCRYPT_BLOWFISH, $mode = MCRYPT_MODE_CBC, $source = MCRYPT_DEV_URANDOM)
	{
		$this->_key = $key;
		$this->_cipher = $cipher;
		$this->_mode = $mode;
		$this->_source = $source;

		$this->setIv($iv);
	}

	public function setIv($iv = NULL)
	{
		if ($iv !== NULL)
		{
			$this->_iv = $iv;
		}
		else
		{
			$this->_iv = mcrypt_create_iv(mcrypt_get_iv_size($this->_cipher, $this->_mode), $this->_source);
		}
	}

	public function encrypt($data)
	{
		return urlencode(base64_encode(mcrypt_encrypt($this->_cipher, $this->_key, $data, $this->_mode, $this->_iv)));
	}

	public function decrypt($data)
	{
		return mcrypt_decrypt($this->_cipher, $this->_key, base64_decode(urldecode($data)), $this->_mode, $this->_iv);
	}
	
	public function correctAnswer($user_answer, $answer)
	{
		return trim($user_answer) === trim($this->decrypt($answer));
	}
}