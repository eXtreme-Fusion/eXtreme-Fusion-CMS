<?php

abstract class Abstract_View {

	protected $_data = array();

	public function template($name)
	{
		include realpath(F_TPL.$name.DS.$name.'.phtml');
	}

	public function assign($key, $value = NULL)
	{
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->assign($k, $v);
			}
		}
		else
		{
			$this->_data[$key] = $value;
		}

		return $this;
	}

	public function get($key)
	{
		return $this->_data[$key];
	}

	public function __get($key)
	{
		return $this->get($key);
	}

}