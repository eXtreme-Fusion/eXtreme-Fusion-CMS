<?php

abstract class Abstract_Controller
{
	protected 
		$action,
		$params;
	
	protected $helper = array();
	
	public function __construct($action, $params)
	{
		$this->action = $action;
		$this->params = $params;
	}
	
	public function set($name, $obj)
	{
		$this->helper[$name] = $obj;
	}
	
	public function get($name)
	{
		if (isset($this->helper[$name]))
		{
			return $this->helper[$name];
		}	
		
		throw new systemException('Undefined helper usage.');
	}
	
	public function view($name)
	{
		if (file_exists(F_VIEW.$name.F_EXT))
		{
			include F_VIEW.$name.F_EXT;
			$name = ucfirst($name).'_View';
			return new $name;
		}
		
		throw new systemException('View '.$name.' not found');
	}
}