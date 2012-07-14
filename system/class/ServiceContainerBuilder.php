<?php

class ServiceContainerBuilder extends ServiceContainer
{
	protected
		$class,
		$save_shared = TRUE,
		$method,
		$arguments = array();

	public function setConstructor($method)
	{
		$this->method = $method;

		return $this;
	}

	public function setClass($name)
	{
		$this->class = $name;

		return $this;
	}

	public function setArguments(array $arguments)
	{
		$this->arguments = $arguments;

		return $this;
	}

	public function addArgument($argument)
	{
		$this->arguments[] = $argument;

		return $this;
	}

	public function saveShared($shared)
	{
		$this->save_shared = (bool) $shared;

		return $this;
	}
}