<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/

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