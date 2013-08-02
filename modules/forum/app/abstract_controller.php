<?php defined('EF5_SYSTEM') || exit;

abstract class Abstract_Controller {

	protected $action = array();
	protected $params = array();

	protected $_helpers = array();

	public function __construct($action, $params)
	{
		$this->action = $action;
		$this->params = $params;
	}

	public function set($name, $object)
	{
		$this->_helpers[$name] = $object;

		return $this;
	}

	public function get($name)
	{
		if ( ! isset($this->_helpers[$name]))
		{
			throw new systemException(__('Helper :name does not exist',
				array(':name' => $name)));
		}

		return $this->_helpers[$name];
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
		if ( ! file_exists($path = F_SRC.$name.F_EXT))
		{
			throw new systemException(__('Model :name does not exist',
				array(':name' => $name)));
		}

		// Załączanie klasy
		require_once $path;

		$name = ucfirst($name).'_Model';

		$model = new ReflectionClass($name);

		return $model->newInstanceArgs($params);
	}

	public function view($filename, array $data = array())
	{
		if ( ! file_exists($_path = F_VIEW.$filename.F_EXT))
		{
			throw new systemException(__('View :filename does not exist',
				array(':filename' => $filename)));
		}

		extract($data, EXTR_SKIP);

		require_once realpath($_path);
	}

}