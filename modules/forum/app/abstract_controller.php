<?php

abstract class Abstract_Controller {

	protected $action, $params = array();

	protected $_helper = array();

	public function __construct($action, $params)
	{
		$this->action = $action;
		$this->params = $params;
	}

	public function set($name, $obj)
	{
		$this->_helper[$name] = $obj;

		return $this;
	}

	public function get($name)
	{
		if (isset($this->_helper[$name]))
		{
			return $this->_helper[$name];
		}

		throw new systemException('Undefined helper usage.');
	}

	public function __get($name)
	{
		return $this->get($name);
	}

	public function render()
	{
		return $this->{$this->action}();
	}

	public function model($name, $params = array())
	{
		$name = ucfirst($name).'_Data';

		$model = new ReflectionClass($name);

		return $model->newInstanceArgs($params);
	}

	public function view($filename, array $data = array())
	{
		$_path = F_VIEW.$filename.'.phtml';

		if ( ! file_exists($_path))
		{
			throw new systemException('View '.$filename.' not found');
		}

		extract($data, EXTR_SKIP);

		include realpath($_path);
	}

}