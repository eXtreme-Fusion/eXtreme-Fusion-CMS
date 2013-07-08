<?php

class Admin
{
	protected $_pdo;

	public function __construct($_pdo)
	{
		$this->_pdo = $_pdo;
	}

	public function setCountUp($page_name)
	{
		$_pdo->exec('UPDATE [admin_favourites] SET `count` = `count` + 1 WHERE `page_id` = (SELECT `id` FROM [admin] WHERE `link` = :link)', array(':link'))
	}
}