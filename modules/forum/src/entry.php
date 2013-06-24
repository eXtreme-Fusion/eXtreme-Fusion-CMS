<?php

class Entry_Data {

	protected $_pdo;

	public function __construct(Data $_pdo)
	{
		$this->_pdo = $_pdo;
	}

}