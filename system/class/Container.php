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
class Container extends ServiceContainerBuilder
{
	protected function getCommentService()
	{
		return new Comment(new Basic, $this->getService('Pdo'), $this->getService('User'), $this->getService('Sett'), $this->getService('Sbb'), $this->getService('Header'));
	}

	protected function getUserService()
	{
		return new User($this->getService('Sett'), $this->getService('Pdo'));
	}

	protected function getSettService()
	{
		return new Sett($this->getService('System'), $this->getService('Pdo'));
	}

	protected function getSystemService()
	{
		return new System;
	}

	protected function getPagingService()
	{
		return new Paging;
	}

	protected function getPageNavService()
	{
		return new PageNav($this->getService('Paging'));
	}

	protected function getPDOService()
	{
		$_dbconfig = $this['pdo.config'];

		$pdo = new Data('mysql:host='.$_dbconfig['host'].';dbname='.$_dbconfig['database'].';port='.$_dbconfig['port'].';charset='.$_dbconfig['charset'], $_dbconfig['user'], $_dbconfig['password']);
		$pdo->config($_dbconfig['prefix']);

		// MYSQL_ATTR_INIT_COMMAND is available for PHP >= 5.3.1, so we are using dsn charset.
		// http://stackoverflow.com/a/4348744/1794927
		// Charset by dsn available after php 5.3.6, so we are using set names.
		// http://php.net/manual/en/ref.pdo-mysql.connection.php
		$pdo->query('SET NAMES '.$_dbconfig['charset'], NULL, FALSE);

		return $pdo;
	}

	protected function getHeaderService()
	{
		return new Header;
	}

	protected function getSBBService()
	{
		include_once DIR_CLASS.'Sbb.php';

		return SmileyBBcode::getInstance($this->getService('Sett'), $this->getService('Pdo'), $this->getService('Locales'), $this->getService('Header'), $this->getService('User'), $this->getService('System'));
	}

	protected function getLocalesService()
	{
		return new Locales($this->getService('User')->getLang(), DIR_LOCALE);
	}

	protected function getTagService()
	{
		return new Tag($this->getService('System'), $this->getService('Pdo'));
	}

	protected function getModulesService()
	{
		return new Modules($this->getService('Pdo'), $this->getService('Sett'), $this->getService('User'), $this->getService('Tag'), $this->getService('Locales'));
	}

	protected function getStatisticsService()
	{
		return new Statistics($this->getService('Pdo'), $this->getService('System'));
	}
}