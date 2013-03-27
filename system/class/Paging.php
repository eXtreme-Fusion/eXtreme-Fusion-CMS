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

/*
Użycie:

require DIR_CLASS.'Paging.php';

$page = new PageNav(new Paging(100, 2, 5), $_tpl, 10);

$_tpl->assignPageNav($page->getPageNav(), 'page_nav');
	lub (np. dla modulow, ktore moga z tego korzystac lub nie)
$_tpl->assignPageNav($page->getPageNav(), 'moj_szablon', DIR_MODULES.'moj_modul'.DS);

Plik TPL:

{$page_nav}

*/

interface PagingIntf
{
	// Zwróci ilość podstron, jaka może zostać wygenerowana
	public function getPagesCount();

	// Zwraca numer podstrony, którą przeglądamy
	public function getCurrentPage();

	// Zwraca numer ostatniej podstrony, jaką możemy przejrzeć
	public function getLastPage();

	// Zwraca numer pierwszej podstrony, jaką możemy przejrzeć
	public function getFirstPage();

	// Zwraca ilośc materiałów wyświetlanych na jednej podstronie
	public function getPerPage();

	//public function getRowStart();
}

class Paging implements PagingIntf
{
	protected
		$_count,						// Ilość wszystkich materiałów, które trzeba rozłożyć na podstrony
		$_current,						// Aktualna podstrona
		$_per_page;						// Ilość materiałów wyświetlana na podstronie

	protected $options = array();

	public function __construct()
	{
		$this->options['current_page_intval'] = TRUE;
	}

	// Zapisuje ilość podstron, jaka może zostać wygenerowana
	public function setPagesCount($count, $current, $per_page, $default = NULL)
	{
		if ($count == 0)
		{
			throw new systemException('Błąd! Parametr pierwszy nie może być zerem.');
		}

		// NULL jest zwracany przez metody getParam oraz getByID Routera, gdy parametr nie zostanie odnaleziony.
		if ($current === $default)
		{
			$this->_current = 1;
		}
		// Sprawdzanie, czy parametr jest numeryczny.
		elseif (is_numeric($current) && $current)
		{
			$this->_current = intval($current);
		}
		// Zwracanie błędu gdy parametr nie jest numeryczny lub jest równy 0 i inny niż NULL.
		else
		{
			throw new systemException('Błąd! Parametr drugi nie może być zerem.');
		}

		if ($per_page > 0)
		{
			$this->_per_page = intval($per_page);
		}
		else
		{
			throw new systemException('Błąd! Parametr trzeci nie może być zerem.');
		}

		$this->_count = ceil(intval($count)/$this->_per_page);

		if ($this->getCurrentPage() > $this->getPagesCount())
		{
			throw new pageNavException('Podana podstrona nie istnieje');
		}
	}

	public function getPagesCount()
	{
		return $this->_count;
	}

	public function getCurrentPage()
	{
		return $this->_current;
	}

	public function getLastPage()
	{
		return $this->getPagesCount();
	}

	public function getFirstPage()
	{
		return 1;
	}

	public function getPrevPage()
	{
		if ($this->_current > 1)
		{
			return $this->_current-1;
		}

		return NULL;
	}

	public function getNextPage()
	{
		$next = $this->_current + 1;

		if ($next <= $this->getLastPage())
		{
			return $next;
		}

		return NULL;
	}

	public function getPerPage()
	{
		return $this->_per_page;
	}

	public static function getRowStart($current, $per_page)
	{
		return ($current-1)*$per_page;
	}

	public function getLimit($filename)
	{
		if (in_array($filename, array('news', 'comments')))
		{
			return intval($this->_sett->get($filename.'_per_page'));
		}
	}
}