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
	
	public function view(array $data)
	{
		if (isset($data['class']))
		{
			if (file_exists(F_VIEW.$data['class'].F_EXT))
			{
				include F_VIEW.$data['class'].F_EXT;
				$name = ucfirst($data['class']).'_View';
				if (isset($data['construct']))
				{
					$_class = new ReflectionClass($name);
					$_class = $_class->newInstanceArgs($data['construct']);
				}
				else
				{
					$_class = new $name;
				}
				
				if (isset($data['method']))
				{
					$temp = array();
					foreach($data['models'] as $name => $class)
					{
						$name = ucfirst($name).'_Data';
						$model = new ReflectionClass($name);
						$model = $model->newInstanceArgs($class);
						$temp[] = $model;
					}
					
					call_user_func_array(array($_class, $data['method']), $temp);
					
				}
				
				return $_class;
			}
		}
		
		throw new systemException('View '.$name.' not found');
	}
}