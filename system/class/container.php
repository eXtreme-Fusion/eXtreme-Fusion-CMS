<?php

class Container extends ServiceContainerBuilder
{
	protected function getCommentService()
	{
		return new Comment(new Basic, $this->getService('pdo'), $this->getService('user'), $this->getService('sett'), $this->getService('sbb'), $this->getService('header'));
	}

	protected function getUserService()
	{
		return new User($this->getService('Sett'), $this->getService('pdo'));
	}

	protected function getSettService()
	{
		return new Sett($this->getService('system'), $this->getService('pdo'));
	}

	protected function getSystemService()
	{
		return new System;
	}

	protected function getPDOService()
	{
		$_dbconfig = $this['pdo.config'];

		$pdo = new PDO_EXT('mysql:host='.$_dbconfig['host'].';dbname='.$_dbconfig['database'].';port='.$_dbconfig['port'], $_dbconfig['user'], $_dbconfig['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$_dbconfig['charset']));
		$pdo->config($_dbconfig['prefix']);

		return $pdo;
	}

	protected function getHeaderService()
	{
		return new Header;
	}

	protected function getSBBService()
	{
		include_once DIR_CLASS.'sbb.php';

		return SmileyBBcode::getInstance($this->getService('sett'), $this->getService('pdo'), $this->getService('locale'), $this->getService('header'), $this->getService('user'));
	}

	protected function getLocaleService()
	{
		return new Locales($this->getService('sett')->get('locale'), DIR_LOCALE);
	}
}