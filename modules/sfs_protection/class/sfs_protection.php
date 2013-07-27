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
 * Klasa ta zawiera zbiór metod odpowiedzialnych
 * za ochronę formularzy przed botami.
 */
class sfs_protection implements Security_Intf
{
	protected $_method;

	protected $_api = array();
	
	protected $_url;
	
	protected $_ip;
	
	protected $_email;
	
	protected $_name;
	
	protected $_appears = TRUE;
	
	protected $_logs = TRUE; // Wyłaczenie dodawania rekordów z spambotami
	
	protected $_tpl;
	
	protected $_pdo;
	
	protected $_locale;
	
	public function __construct()
	{
		$this->detectGetMethod();
	}

	public function getView_wrongAnswer()
	{
		return $this->getView();
	}

	public function getResponseInputs()
	{
		return array(
			'username',
			'user_email',
			'user_ip'
		);
	}
	
	public function getView()
	{
		$this->_tpl->assignGroup(array(
			'info' => __('The forms protected by :name', array(':name' => '<a href="http://rafik.eu/">SFSProtection&trade;</a>.')),
			'user_ip' => $_SERVER['REMOTE_ADDR'],
			'answer' => $this->getUserAnswer()
		));

		ob_start();
		$this->_tpl->template('view.tpl');
		$data = ob_get_contents();
		ob_end_clean();

		return $data;
	}

	public function isValidAnswer($answer)
	{
		if($this->setAllField($answer[0], $answer[1], $answer[2]))
		{
			return $this->checkAllField();
		}	
		elseif($this->setName($answer[0]))
		{
			return $this->checkName();
		}	
		elseif($this->setEmail($answer[1]))
		{
			return $this->checkEmail();
		} 
		elseif($this->setIP($answer[2]))
		{
			return $this->checkIP();
		}
	}

	public function getUserAnswer()
	{
		if($this->_appears)
		{
			return FALSE;
		}
		
		return TRUE;
	}

	public function setObjects($_tpl, $_pdo, $_locale)
	{
		$this->_pdo = $_pdo;
		
		$this->_locale = $_locale;
		$this->_locale->moduleLoad('view', 'sfs_protection');
		
		$this->_tpl = $_tpl;
		$this->_tpl->root = DIR_MODULES.'sfs_protection'.DS.'templates'.DS;
		$this->_tpl->compile = DIR_CACHE;
	}

	private function checkEmail()
	{
		$this->_url = 'http://www.stopforumspam.com/api?email='.$this->_email;
		$this->getContent();
		return $this->_appears;
	}
	
	private function setEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) 
		{
			$this->_email = $email;
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	private function checkIP()
	{
		$this->_url = 'http://www.stopforumspam.com/api?ip='.$this->_ip;
		$this->getContent();
		
		if($this->_appears === FALSE && $this->_logs === TRUE)
		{
			$this->_pdo->exec('INSERT INTO [sfs_protection] (`name`, `email`, `ip`, `datestamp`) VALUES (:name, :email, :ip, '.time().')',
				array(
					array(':name', '----', PDO::PARAM_STR),
					array(':email', '----', PDO::PARAM_STR),
					array(':ip', $this->_ip, PDO::PARAM_STR)
				)
			);
		}
		
		return $this->_appears;
	}
	
	private function setIP($ip)
	{
		if (filter_var($ip, FILTER_VALIDATE_IP))
		{
			$this->_ip = $ip;
			return TRUE;
		}
		
		return FALSE;
	}
	
	private function checkName()
	{
		$this->_url = 'http://www.stopforumspam.com/api?username='.$this->_name;
		$this->getContent();
		
		if($this->_appears === FALSE && $this->_logs === TRUE)
		{
			$this->_pdo->exec('INSERT INTO [sfs_protection] (`name`, `email`, `ip`, `datestamp`) VALUES (:name, :email, :ip, '.time().')',
				array(
					array(':name', $this->_name, PDO::PARAM_STR),
					array(':email', '----', PDO::PARAM_STR),
					array(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR)
				)
			);
		}
		
		return $this->_appears;
	}
	
	private function setName($name)
	{
		if ( ! preg_match("#[^\w\d-]+#i", $name))
		{
			$this->_name = $name;
			return TRUE;
		}
		
		return FALSE;
	}
	
	private function checkAllField()
	{
		$this->_url = 'http://www.stopforumspam.com/api?username='.$this->_name.'&email='.$this->_email.'&ip='.$this->_ip;
		$this->getContent();
		
		$this->_name = isset($this->_name) ? $this->_name : '----';
		
		if($this->_appears === FALSE && $this->_logs === TRUE)
		{
			$this->_pdo->exec('INSERT INTO [sfs_protection] (`name`, `email`, `ip`, `datestamp`) VALUES (:name, :email, :ip, '.time().')',
				array(
					array(':name', $this->_name, PDO::PARAM_STR),
					array(':email', $this->_email, PDO::PARAM_STR),
					array(':ip', $this->_ip, PDO::PARAM_STR)
				)
			);
		}
		
		return $this->_appears;
	}
	
	private function setAllField($name, $email, $ip)
	{
		if ( ! preg_match("#[^\w\d-]+#i", $name) && filter_var($email, FILTER_VALIDATE_EMAIL) && filter_var($ip, FILTER_VALIDATE_IP))
		{
			$this->_name = $name;
			$this->_email = $email;
			$this->_ip = $ip;
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	
	private function detectGetMethod()
	{
		if (function_exists('curl_version'))
		{
			$this->_method = 'curl';
		}
		elseif(function_exists('file_get_contents'))
		{
			$this->_method = 'fgc';
		}
		else
		{
			throw new userException('Uwaga, brak obsługiwanych funkcji, system ochrony wymaga obsługi modułu cURL lub funkcji file_get_contents');
		}
	}
	
	private function getContent()
	{
		if($this->_method === 'fgc')
		{
			$xml = new SimpleXMLElement(file_get_contents(stripslashes($this->_url)));
			foreach($xml->appears as $d)
			{
				if ($d == 'yes') 
				{
					$this->_appears = FALSE;
				}
			}
		}
		else
		{
			$_init = curl_init(); 
			curl_setopt($_init, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($_init, CURLOPT_URL, stripslashes($this->_url)); 
			$xml = new SimpleXMLElement(curl_exec($_init));
			foreach($xml->appears as $d)
			{
				if ($d == 'yes') 
				{
					$this->_appears = FALSE;
				}
			}
			curl_close($_init);
		}
		
	}
	
	
	/* Być może będzie w przyszłości możliwość zgłaszania nowych wpisów z bazy które uznaliśmy za spam
	public function raportNewSpamBot($username, $ip, $email, $api, $evidence) 
	{
		$data = 'username='.$username.'&ip_addr='.$ip.'&email='.$email.'&api_key='.$api.'&evidence='.$evidence;
		
		$fp = fsockopen('www.stopforumspam.com',80);
		fputs($fp, 'POST /add.php HTTP/1.1\n' );
		fputs($fp, 'Host: www.stopforumspam.com\n' );
		fputs($fp, 'Content-type: application/x-www-form-urlencoded\n' );
		fputs($fp, 'Content-length: '.strlen($data).'\n' );
		fputs($fp, 'Connection: close\n\n' );
		fputs($fp, $data);
		fclose($fp);
	}
	*/
}