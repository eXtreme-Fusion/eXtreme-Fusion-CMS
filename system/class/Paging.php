<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/

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

	// Nalezy pamiętać, aby każdy parametr przesłany do konstruktora był parsowany przez funkcję intval()!!
	public function __construct($count, $current, $per_page)
	{
		if ($count == 0)
		{
			throw new systemException('Błąd! Parametr pierwszy nie może być zerem.');
		}

		if ($current > 0)
		{
			$this->_current = $current;
		}
		else
		{
			throw new systemException('Błąd! Parametr drugi nie może być zerem.');
		}

		if ($per_page > 0)
		{
			$this->_per_page = $per_page;
		}
		else
		{
			throw new systemException('Błąd! Parametr trzeci nie może być zerem.');
		}

		$this->setPagesCount($count, $per_page);
	}

	// Zapisuje ilość podstron, jaka może zostać wygenerowana
	private function setPagesCount($count, $per_page)
	{
		$this->_count = ceil($count/$per_page);

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
}