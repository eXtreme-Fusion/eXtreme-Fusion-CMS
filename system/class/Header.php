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

class Header
{
	protected $_values = array();

	public function set($value)
	{
		if (is_array($value))
		{
			$this->_values = array_merge($this->_values, $value);
		}
		else
		{
			$this->_values[] = $value;
		}
	}

	public function get($id = NULL)
	{	
		if (isNum($id, FALSE))
		{
			return isset($this->_values[$id]) ? $this->_values[$id] : FALSE;
		}

		return implode(PHP_EOL, $this->_values).PHP_EOL;
	}
}