<?php

abstract class Abstract_View
{
	protected $data = array();
	
	public function template($name)
	{
		include realpath(F_TPL.$name.DS.$name.'.phtml');
	}
	
	public function assign($name, $data)
	{
		$this->data[$name] = $data;
	}
	
	public function get($name)
	{
		echo $this->data[$name];
	}
}