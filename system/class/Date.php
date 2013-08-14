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
class Date
{
	protected $_sett = array();

	/* Konstruktor klasy Date
	 * Przechowuje obiekt _lang i _sett
	 */
	public function __construct($_sett, $_lang)
	{
		$this->_sett = $_sett;
	}

	public function getDate($format, $timestamp = NULL)
	{
		$lang = array(
			'l' => array(
				'form' => 'N',
				'string' => __('IndexDays')
			 ),
			'F' => array(
				'form' => 'n',
				'string' => __('IndexMonths')
			 ),
			'f' => array(
				'form' => 'n',
				'string' => __('IndexNiceMonths')
			 )
		);
		
		if ($data = preg_split('#[.-\/:, ]#', $format, NULL, PREG_SPLIT_NO_EMPTY))
		{
			if ($timestamp === NULL)
			{
				$timestamp = time();
			}
			
			foreach ($data as $var)
			{
				if (array_key_exists($var, $lang))
				{
					$replace[] = $lang[$var]['string'][(date($lang[$var]['form'], $timestamp)-1)];
				}
				else
				{
					$replace[] = date($var, $timestamp);
				}
			}

			return strtr($format, array_combine($data, $replace));
		}
	}
}
?>
