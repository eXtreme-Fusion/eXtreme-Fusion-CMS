<?php

// Komunikator do tabeli `forum`
class Forum_Data
{
	protected $_pdo;
	
	public function __construct(Data $_pdo)
	{
		$this->_pdo = $_pdo;
	}
	
	public function fetchAll()
	{
	
	}
	
	public function get($id = NULL)
	{
		return $this->_pdo->getField('SELECT `value` FROM [settings] WHERE `key` = :key', array(':key', $id, PDO::PARAM_STR));
	}
	
	public function save(array $data)
	{
	
	}
}