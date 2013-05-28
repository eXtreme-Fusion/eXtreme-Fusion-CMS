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
if (!defined("EF5_SYSTEM")) { die("Access denied"); }

class PhpBB
{
	private $_pdo;
	private $_system;
	private $_user;

	public function __construct($pdo, $system, $user)
	{
		$this->_pdo = $pdo;
		$this->_system = $system;
		$this->_user = $user;
	}

	public function bridgeOn()
	{
		$data = $this->_pdo->getRow('SELECT * FROM [bridge_phpbb] LIMIT 1');
		if ($data['status'] === '1')
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*-------------------
	| Create new account in phpBB 3
	+------------------*/
	public function registerPhpBB($name, $password, $email, $prefix = 'phpbb_')
	{
		$lang = $this->_pdo->getRow('SELECT * FROM '.$prefix.'config WHERE `config_name` = "default_lang"');
		$lang = $lang['config_value'];
		
		$style = $this->_pdo->getRow('SELECT * FROM '.$prefix.'config WHERE `config_name` = "default_style"');
		$style = $style['config_value'];
		
		$dateformat = $this->_pdo->getRow('SELECT * FROM '.$prefix.'config WHERE `config_name` = "default_dateformat"');
		$dateformat = $dateformat['config_value'];
		
		$timezone = $this->_pdo->getRow('SELECT * FROM '.$prefix.'config WHERE `config_name` = "board_timezone"');
		$timezone = $timezone['config_value'];
		
		$this->_pdo->exec('INSERT INTO '.$prefix.'users (`username`, `username_clean`, `user_password`, `user_email`, `user_email_hash`, `user_options`, `user_form_salt`, `group_id`, `user_regdate`, `user_passchg`, `user_lastmark`, `user_lang`, `user_style`, `user_dateformat`, `user_timezone`) VALUES (:username, :username_clean, :password, :email, :email_hash, :hash, :salt, 2, '.time().', '.time().', '.time().', :lang, :style, :dateformat, :timezone)',
			array(
				array(':username', $name, PDO::PARAM_STR),
				array(':username_clean', HELP::Title2Link($name), PDO::PARAM_STR),
				array(':password', $this->phpbb_hash($password), PDO::PARAM_STR),
				array(':email', $email, PDO::PARAM_STR),
				array(':email_hash', $this->phpbb_email_hash($email), PDO::PARAM_STR),
				array(':hash', 230271, PDO::PARAM_INT),
				array(':salt', $this->unique_id(), PDO::PARAM_INT),
				array(':lang', $lang, PDO::PARAM_STR),
				array(':style', $style, PDO::PARAM_INT),
				array(':dateformat', $dateformat, PDO::PARAM_STR),
				array(':timezone', $timezone, PDO::PARAM_STR)
			)
		);
		
		$user = $this->_pdo->getRow('SELECT `user_id`, `group_id`, `username` FROM '.$prefix.'users WHERE `username_clean` = :username',
			array(':username', HELP::Title2Link($name), PDO::PARAM_STR)
		);
		
		$this->_pdo->exec('INSERT INTO '.$prefix.'user_group (`group_id`, `user_id`, `user_pending`) VALUES (:group, :user, 0)',
			array(
				array(':group', $user['group_id'], PDO::PARAM_INT),
				array(':user', $user['user_id'], PDO::PARAM_INT)
			)
		);
		
		$this->_pdo->exec('UPDATE '.$prefix.'config SET `config_value` = :user_id WHERE `config_name` = "newest_user_id"',
			array(':user_id', $user['user_id'], PDO::PARAM_INT)
		);
		
		$this->_pdo->exec('UPDATE '.$prefix.'config SET `config_value` = :username WHERE `config_name` = "newest_username"',
			array(':username', $user['username'], PDO::PARAM_INT)
		);
		
		$this->_pdo->exec('UPDATE '.$prefix.'config SET `config_value` = `config_value`+1 WHERE `config_name` = "num_users"');
		
		return TRUE;
	}

	/*-------------------
	| Hash password based on phpbb 3
	+------------------*/
	public function phpbb_hash($password)
	{
		$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		$random_state = $this->unique_id();
		$random = '';
		$count = 6;

		if (strlen($random) < $count)
		{
			$random = '';

			for ($i = 0; $i < $count; $i += 16)
			{
				$random_state = md5($this->unique_id() . $random_state);
				$random .= pack('H*', md5($random_state));
			}
			$random = substr($random, 0, $count);
		}

		$hash = $this->_hash_crypt_private($password, $this->_hash_gensalt_private($random, $itoa64), $itoa64);

		if (strlen($hash) == 34)
		{
			return $hash;
		}
		
		return md5($password);
	}

	public function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
	{
		if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
		{
			$iteration_count_log2 = 8;
		}

		$output = '$H$';
		$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
		$output .= $this->_hash_encode64($input, 6, $itoa64);

		return $output;
	}

	public function _hash_encode64($input, $count, &$itoa64)
	{
		$output = '';
		$i = 0;

		do
		{
			$value = ord($input[$i++]);
			$output .= $itoa64[$value & 0x3f];

			if ($i < $count)
			{
				$value |= ord($input[$i]) << 8;
			}

			$output .= $itoa64[($value >> 6) & 0x3f];

			if ($i++ >= $count)
			{
				break;
			}

			if ($i < $count)
			{
				$value |= ord($input[$i]) << 16;
			}

			$output .= $itoa64[($value >> 12) & 0x3f];

			if ($i++ >= $count)
			{
				break;
			}

			$output .= $itoa64[($value >> 18) & 0x3f];
		}
		while ($i < $count);

		return $output;
	}

	public function _hash_crypt_private($password, $setting, &$itoa64)
	{
		$output = '*';

		// Check for correct hash
		if (substr($setting, 0, 3) != '$H$' && substr($setting, 0, 3) != '$P$')
		{
			return $output;
		}

		$count_log2 = strpos($itoa64, $setting[3]);

		if ($count_log2 < 7 || $count_log2 > 30)
		{
			return $output;
		}

		$count = 1 << $count_log2;
		$salt = substr($setting, 4, 8);

		if (strlen($salt) != 8)
		{
			return $output;
		}
		
		/**
		* We're kind of forced to use MD5 here since it's the only
		* cryptographic primitive available in all versions of PHP
		* currently in use.  To implement our own low-level crypto
		* in PHP would result in much worse performance and
		* consequently in lower iteration counts and hashes that are
		* quicker to crack (by non-PHP code).
		*/
		if (PHP_VERSION >= 5)
		{
			$hash = md5($salt . $password, true);
			do
			{
				$hash = md5($hash . $password, true);
			}
			while (--$count);
		}
		else
		{
			$hash = pack('H*', md5($salt . $password));
			do
			{
				$hash = pack('H*', md5($hash . $password));
			}
			while (--$count);
		}

		$output = substr($setting, 0, 12);
		$output .= $this->_hash_encode64($hash, 16, $itoa64);

		return $output;
	}
	
	public function phpbb_email_hash($email)
	{
		return sprintf('%u', crc32(strtolower($email))) . strlen($email);
	}
	
	/**
	* Return unique id
	* @param string $extra additional entropy
	*/
	public function unique_id($extra = 'c', $prefix = 'phpbb_')
	{
		$dss_seeded = false;
		
		$config = $this->_pdo->getRow('SELECT * FROM '.$prefix.'config WHERE `config_name` = "rand_seed"');
		$configs = $this->_pdo->getRow('SELECT * FROM '.$prefix.'config WHERE `config_name` = "rand_seed_last_update"');

		$val = $config['config_name'] . microtime();
		$val = md5($val);
		$rand_seed = md5($config['config_name'] . $val . $extra);

		if ($dss_seeded !== true && ($configs['config_name'] < time() - rand(1,10)))
		{
			$this->_pdo->exec('UPDATE '.$prefix.'config SET `config_value` = :time WHERE `config_name` = "rand_seed_last_update"',
				array(':time', time(), PDO::PARAM_INT)
			);
			$this->_pdo->exec('UPDATE '.$prefix.'config SET `config_value` = :rand WHERE `config_name` = "rand_seed"',
				array(':rand', $rand_seed, PDO::PARAM_STR)
			);
			$dss_seeded = true;
		}

		return substr($val, 4, 16);
	}
}
