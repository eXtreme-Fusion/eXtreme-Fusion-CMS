<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/

class SmileyBBcode
{
	protected $_sett;
	protected $_pdo;
	protected $_locale;
	protected $_head;
	protected $_user;


	public function __construct($_sett, $_pdo, $_locale, $_head, $_user)
	{
		$this->_pdo = $_pdo;
		$this->_user = $_user;
		$this->_locale = $_locale;
		$this->_head = $_head;
		$this->_sett = $_sett;
	}

	public function bbcodes($textarea = 'message')
	{
		$bbcode_used = FALSE;
		$this->_locale->setSubDir('bbcodes');

		$query = $this->_pdo->getData('SELECT `name` FROM [bbcodes] WHERE `name` != \'autolink\' ORDER BY `order` ASC');
		if ($this->_pdo->getRowsCount($query))
		{
			$bbcodes = array();
			foreach ($query as $row)
			{
				$bbcode_name[] = $row['name'];
			}
		}
		else
		{
			return FALSE;
		}

		$bbcode_info = array();
		$_locale = $this->_locale;
		foreach ($bbcode_name as $bbcode)
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

	public function parseBBCode($text, $parse = TRUE)
	{
		$bbcode_used = $parse;
		$this->_locale->setSubDir('bbcodes');

		$query = $this->_pdo->getData('SELECT `name` FROM [bbcodes] ORDER BY `order` ASC');
		if ($this->_pdo->getRowsCount($query))
		{
			$bbcodes = array();
			foreach ($query as $row)
			{
				$bbcode_name[] = $row['name'];
			}
		}
		else
		{
			return FALSE;
		}

		$_locale = $this->_locale;
		$_user = $this->_user;
		$_head = $this->_head;
		foreach ($bbcode_name as $bbcode)
		{
			if (file_exists(DIR_SYSTEM.'bbcodes'.DS.$bbcode.'.php'))
			{
				include DIR_SYSTEM.'bbcodes'.DS.$bbcode.'.php';
			}
		}

		$text = HELP::descript($text, FALSE);
		$this->_locale->setSubDir('');
		return $text;
	}
}