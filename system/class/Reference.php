<?php

class Reference extends ServiceContainer
{
	private $inst;

	public function __construct($class)
	{
		$this->inst = $this->getService($class);
	}

	public function get()
	{
		return $this->inst;
	}
}