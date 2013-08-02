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
		$this->arguments = array();
		
		foreach($arguments as $argument)
		{
			$this->addArgument($argument);
		}

		return $this;
	}

	public function addArgument($argument)
	{
		if ($argument instanceof Reference)
		{
			$argument = $argument->get();
		}
		
		$this->arguments[] = $argument;

		return $this;
	}

	public function saveShared($shared)
	{
		$this->save_shared = (bool) $shared;

		return $this;
	}
}