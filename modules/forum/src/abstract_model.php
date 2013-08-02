<?php defined('EF5_SYSTEM') || exit;

abstract class Abstract_Model {

	protected $_pdo;

	public function __construct(){}

	public function setData(Data $_pdo)
	{
		$this->_pdo = $_pdo;

		return $this;
	}

}