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
class ServiceContainer implements ArrayAccess
{
	protected static $shared = array();

	protected $parameters = array();

	public function __construct($parameters = array())
	{
		$this->parameters = $parameters;
	}

	public function hasParameter($name)
	{
		return isset($this->parameters[$name]);
	}

	public function getParameter($name)
	{
		if (!isset($this->parameters[$name]))
		{
			return NULL;
		}

		return $this->parameters[$name];
	}

	public function setParameter($name, $value)
	{
		$this->parameters[$name] = $value;
	}

	// implementation ArrayAccess
	public function offsetExists($name)
	{
		return $this->hasParameter($name);
	}

	public function offsetGet($name)
	{
		return $this->getParameter($name);
	}

	public function offsetSet($name, $value)
	{
		return $this->setParameter($name, $value);
	}

	public function offsetUnset($name)
	{
		unset($this->parameters[$name]);
	}
	// end of implementation ArrayAccess

	public function __get($id)
	{
		return $this->getService($id);
	}

	protected function camelize($string)
	{
		$first = strtoupper($string[0]);
		return $first.substr($string, 1, strlen($string)-1);
	}

	public function getService($id)
	{
		$id = strtolower($id);

		if (isset(self::$shared[$id]))
		{
			return self::$shared[$id];
		}

		if (method_exists($this, $method = 'get'.$this->camelize($id).'Service') && !$this->arguments)
		{
			return self::$shared[$id] = $this->$method();
		}

		$class = new ReflectionClass($this->camelize($id));
		$obj = $class->newInstanceArgs($this->arguments);

		if ($this->save_shared)
		{
			self::$shared[$id] = $obj;
		}

		return $obj;
	}

	public function register($name)
	{
		$ec = new Container($this->parameters);
		$ec->setClass($name);
		return $ec;
	}

	public function get()
	{
		return $this->getService($this->class);
	}
}