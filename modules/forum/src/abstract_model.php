<?php

abstract class Abstract_Model {

	protected $_pdo;

	public function setData(Data $_pdo)
	{
		$this->_pdo = $_pdo;

		return $this;
	}

}