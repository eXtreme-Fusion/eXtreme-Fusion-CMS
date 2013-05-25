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
 * Przyk³ad dzia³ania:
 *
	// Drugi parametr koniecznie 8 znaków.
	$c = new Crypt('klucz szyfruj¹cy', '=thn<.?!');

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
	 * Przy pominiêciu drugiego parametru, kodowanie udbywa siê w jedn¹ stronê.
	 * Wektor inicjuj¹cy `iv` jest zmienny z prze³adowaniem strony.

	 * Jeœli dane maj¹ byæ rozkodowane w przysz³oœci, nale¿y posiadaæ sta³y wektor inicjuj¹cy i podaæ go drugim parametrem.
	 * Niezmienny musi byæ te¿ `key` - klucz szyfruj¹cy.
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
		return base64_encode(mcrypt_encrypt($this->_cipher, $this->_key, $data, $this->_mode, $this->_iv));
	}

	public function decrypt($data)
	{
		return mcrypt_decrypt($this->_cipher, $this->_key, base64_decode($data), $this->_mode, $this->_iv);
	}
	
	public function correctAnswer($user_answer, $answer)
	{
		return trim($user_answer) === trim($this->decrypt($answer));
	}
}