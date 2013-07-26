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
| 
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Wooya
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

class SmileyBBcode
{
	protected $_sett;
	protected $_pdo;
	protected $_locale;
	protected $_head;
	protected $_user;
	protected $_system;
	// Przechowuje zaserializowane ustawienia
	protected $_cache = array('bbcodes' => '', 'smileys' => '');

	// Spoiler ładuje kod js, więc może byc tylko raz wywołany
	protected $_spo_loaded = FALSE;

	//** Singleton pattern implementation **/
	private static $_inst;

	private function __construct($_sett, $_pdo, $_locale, $_head, $_user, $_system)
	{
		$this->_pdo = $_pdo;
		$this->_user = $_user;
		$this->_locale = $_locale;
		$this->_head = $_head;
		$this->_sett = $_sett;
		$this->_system = $_system;
	}

	public static function getInstance($_sett, $_pdo, $_locale, $_head, $_user, $_system)
	{
		if ( ! self::$_inst)
		{
			self::$_inst = new SmileyBBcode($_sett, $_pdo, $_locale, $_head, $_user, $_system);
		}

		return self::$_inst;
	}
	/** end of Singleton implementation **/

	public function bbcodes($textarea = 'message')
	{
		$bbcode_used = FALSE;
		$this->_locale->setSubDir('bbcodes');
		
		$this->_cache['bbcodes'] = $this->_system->cache('bbcodes', NULL, 'system');
		if ($this->_cache['bbcodes'] === NULL)
		{
			$query = $this->_pdo->getData('SELECT `name` FROM [bbcodes] WHERE `name` != \'autolink\' ORDER BY `order` ASC');
			if ($this->_pdo->getRowsCount($query))
			{
				foreach ($query as $row)
				{
					$this->_cache['bbcodes'][] = $row['name'];
				}
			}
			
			$this->_system->cache('bbcodes', $this->_cache['bbcodes'], 'system');
		}

		$bbcode_info = array();
		$_locale = $this->_locale;
		foreach ($this->_cache['bbcodes'] as $bbcode)
		{
			include DIR_SYSTEM.'bbcodes'.DS.$bbcode.'.php';

			if ($bbcode_info)
			{
				if (file_exists(DIR_IMAGES."bbcodes/".$bbcode_info['value'].".png"))
				{
					$image = ADDR_IMAGES.'bbcodes/'.$bbcode_info['value'].'.png';
				}
				elseif (file_exists(DIR_IMAGES."bbcodes/".$bbcode_info['value'].".gif"))
				{
					$image = ADDR_IMAGES.'bbcodes/'.$bbcode_info['value'].'.gif';
				}
				elseif (file_exists(DIR_IMAGES."bbcodes/".$bbcode_info['value'].".jpg"))
				{
					$image = ADDR_IMAGES.'bbcodes/'.$bbcode_info['value'].'.jpg';
				}
				else
				{
					$image = FALSE;
				}

				$bbcodes[] = array(
					'textarea' => $textarea,
					'value' => $bbcode_info['value'],
					'description' => $bbcode_info['description'],
					'image' => $image,
				);
			}
			unset ($bbcode_info);
		}

		$this->_locale->setSubDir('');
		return $bbcodes;
	}

	public function smileys($textarea = 'message')
	{
		$this->_cache['smileys'] = $this->_system->cache('smileys', NULL, 'system');
		if ($this->_cache['smileys'] === NULL)
		{
			$query = $this->_pdo->getData('SELECT * FROM [smileys] WHERE `id` != 15 ORDER BY `id` ASC');
			if ($this->_pdo->getRowsCount($query))
			{
				$i = 1;
				foreach ($query as $row)
				{
					$this->_cache['smileys'][] = array(
						'i' => $i,
						'text' => $row['text'],
						'code' => $row['code'],
						'image' => $row['image'],
						'textarea' => $textarea
					);
					
					$i++;
				}
			}
			
			$this->_system->cache('smileys', $this->_cache['smileys'], 'system');
		}
		
		return $this->_cache['smileys'];
	}

	public function parseBBCode($text, $parse = TRUE)
	{
		$bbcode_used = $parse;
		$this->_locale->setSubDir('bbcodes');
		
		$this->_cache['bbcodes'] = $this->_system->cache('parse_bbcodes', NULL, 'system');
		if ($this->_cache['bbcodes'] === NULL)
		{
			$query = $this->_pdo->getData('SELECT `name` FROM [bbcodes] WHERE `name` != \'autolink\' ORDER BY `order` ASC');
			if ($this->_pdo->getRowsCount($query))
			{
				foreach ($query as $row)
				{
					$this->_cache['bbcodes'][] = $row['name'];
				}
			}
			
			$this->_system->cache('parse_bbcodes', $this->_cache['bbcodes'], 'system');
		}

		$_locale = $this->_locale;
		$_user = $this->_user;
		$_head = $this->_head;
		$_system = $this->_system;
		foreach ($this->_cache['bbcodes'] as $bbcode)
		{
			if (file_exists(DIR_SYSTEM.'bbcodes'.DS.$bbcode.'.php'))
			{
				if ($bbcode !== 'spo' || !$this->_spo_loaded)
				{
					include DIR_SYSTEM.'bbcodes'.DS.$bbcode.'.php';
					if ($bbcode === 'spo') $this->_spo_loaded = TRUE;
				}
			}
		}

		$text = nl2br(HELP::descript($text, FALSE));
		
		$this->_locale->setSubDir('');
		
		return $text;
	}

	public function parseSmiley($text)
	{
		if ( ! preg_match("#\<div class='code'>#sie", $text) && ! preg_match("#\<a href=#sie", $text))
		{
			$this->_cache['smileys'] = $this->_system->cache('parse_smileys', NULL, 'system');
			if ($this->_cache['smileys'] === NULL)
			{
				$query = $this->_pdo->getData('SELECT * FROM [smileys] WHERE `id` != 15 ORDER BY `id` ASC');
				if ($this->_pdo->getRowsCount($query))
				{
					foreach ($query as $row)
					{
						$this->_cache['smileys'][] = array(
							'text' => $row['text'],
							'code' => $row['code'],
							'image' => $row['image']
						);
					}
				}
				
				$this->_system->cache('parse_smileys', $this->_cache['smileys'], 'system');
			}
			
			foreach($this->_cache['smileys'] as $smileys)
			{
				$code = preg_quote($smileys['code'], '#');
				$image = '<img src="'.ADDR_IMAGES.'smiley/'.$smileys['image'].'" alt="'.$smileys['text'].'">';
				$text = preg_replace("#{$code}#si", $image, $text);
			}
		}

		return $text;
	}

	public function parseAllTags($text)
	{
		$text = $this->parseBBCode($text);
		$text = $this->parseSmiley($text);

		return $text;
	}
}