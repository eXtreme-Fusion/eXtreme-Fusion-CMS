<?php

abstract class Abstract_View {

	protected $_data = array();

	public function template($name)
	{
		include realpath(F_TPL.$name.DS.$name.'.phtml');
	}

	public function assign($key, $value)
	{
		$this->_data[$key] = $value;

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